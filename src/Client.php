<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral;

use Jgut\Spiral\Exception\TransportException;
use Jgut\Spiral\Transport\Curl;
use Jgut\Spiral\Transport\TransportInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Stream;

/**
 * PSR7 aware client.
 */
class Client
{
    /**
     * cURL transport handler.
     *
     * @var TransportInterface
     */
    private $transport;

    /**
     * Auto close on error.
     *
     * @var bool
     */
    private $closeOnError = true;

    /**
     * @param TransportInterface|null $transport
     */
    public function __construct(TransportInterface $transport = null)
    {
        $this->transport = $transport;
    }

    /**
     * Set transport handler.
     *
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Retrieve transport handler.
     *
     * @return TransportInterface
     */
    public function getTransport()
    {
        if (!$this->transport instanceof TransportInterface) {
            $this->transport = Curl::createFromDefaults();
        }

        return $this->transport;
    }

    /**
     * Set automatic connection close on error.
     *
     * @param bool $closeOnError
     */
    public function setCloseOnError($closeOnError)
    {
        $this->closeOnError = $closeOnError === true;
    }

    /**
     * Check if automatic connection close on error is active.
     *
     * @return bool
     */
    public function isCloseOnError()
    {
        return $this->closeOnError;
    }

    /**
     * Run PSR7 request.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array             $vars
     * @param array             $flags
     *
     * @throws TransportException
     *
     * @return ResponseInterface
     */
    public function request(
        RequestInterface $request,
        ResponseInterface $response,
        array $vars = [],
        array $flags = []
    ) {
        $transport = $this->getTransport();

        try {
            $transferResponse = $transport->request(
                $request->getMethod(),
                (string) $request->getUri(),
                $request->getHeaders(),
                $vars,
                $flags
            );
        } catch (TransportException $exception) {
            if ($this->closeOnError) {
                $transport->close();
            }

            // Bubble exception
            throw $exception;
        }

        $transferInfo = $transport->responseInfo();
        $transport->close();

        $responseHeaders = '';
        $responseContent = $transferResponse;

        if (isset($transferInfo['header_size']) && $transferInfo['header_size']) {
            $headersSize = $transferInfo['header_size'];

            $responseHeaders = rtrim(substr($transferResponse, 0, $headersSize));
            $responseContent = (strlen($transferResponse) === $headersSize)
                ? ''
                : substr($transferResponse, $headersSize);
        }

        // Split headers blocks
        $responseHeaders = preg_split('/(\\r?\\n){2}/', $responseHeaders);

        $responseHeaders = $this->getTransferHeaders(
            preg_split('/\\r?\\n/', array_pop($responseHeaders)),
            $responseContent,
            $transferInfo
        );

        return $this->populateResponse($response, $responseHeaders, $responseContent);
    }

    /**
     * Get response headers based on transfer information.
     *
     * @param array  $transferHeaders
     * @param string $transferContent
     * @param array  $transferInfo
     *
     * @return array
     */
    protected function getTransferHeaders(array $transferHeaders, $transferContent, array $transferInfo)
    {
        $responseHeaders = [
            'Status'         => $transferInfo['http_code'],
            'Content-Type'   => $transferInfo['content_type'],
            'Content-Length' => strlen($transferContent),
        ];

        foreach ($transferHeaders as $header) {
            if (preg_match('/^HTTP\/(1\.\d) +([1-5]\d{2}) +.+$/', $header, $matches)) {
                $responseHeaders['Protocol-Version'] = $matches[1];
                $responseHeaders['Status'] = $matches[2];
            } elseif (strpos($header, ':') !== false) {
                list($name, $value) = explode(':', $header, 2);
                $responseHeaders[$name] = trim($value);
            }
        }

        return $responseHeaders;
    }

    /**
     * Set response headers and content.
     *
     * @param ResponseInterface $response
     * @param array             $headers
     * @param string            $content
     *
     * @return ResponseInterface
     */
    protected function populateResponse(ResponseInterface $response, array $headers, $content)
    {
        if (array_key_exists('Protocol-Version', $headers)) {
            $response = $response->withProtocolVersion($headers['Protocol-Version']);
            unset($headers['Protocol-Version']);
        }

        if (array_key_exists('Status', $headers)) {
            $response = $response->withStatus((int) $headers['Status']);
            unset($headers['Status']);
        }

        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, (string) $value);
        }

        $body = new Stream('php://temp', 'wb+');
        $body->write($content);

        return $response->withBody($body);
    }
}
