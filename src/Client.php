<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral;

use Jgut\Spiral\Exception\TransportException;
use Jgut\Spiral\Transport\Curl;
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
     * @var \Jgut\Spiral\Transport
     */
    private $transport;

    /**
     * @param \Jgut\Spiral\Transport|null $transport
     */
    public function __construct(Transport $transport = null)
    {
        $this->transport = $transport;
    }

    /**
     * Set transport handler.
     *
     * @param \Jgut\Spiral\Transport $transport
     */
    public function setTransport(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Retrieve transport handler.
     *
     * @return \Jgut\Spiral\Transport
     */
    public function getTransport()
    {
        if (!$this->transport instanceof Transport) {
            $this->transport = Curl::createFromDefaults();
        }

        return $this->transport;
    }

    /**
     * Run PSR7 request.
     *
     * @param \Psr\Http\Message\RequestInterface  $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array                               $vars
     * @param array                               $flags
     *
     * @throws \Jgut\Spiral\Exception\TransportException
     *
     * @return \Psr\Http\Message\ResponseInterface
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
            $transport->close();

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
            if (preg_match('/^HTTP\/(1\.\d) +([1-5][0-9]{2}) +.+$/', $header, $matches)) {
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
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array                               $headers
     * @param string                              $content
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function populateResponse(ResponseInterface $response, array $headers, $content)
    {
        if (array_key_exists('Protocol-Version', $headers)) {
            $response = $response->withProtocolVersion($headers['Protocol-Version']);
            unset($headers['Protocol-Version']);
        }

        if (array_key_exists('Status', $headers)) {
            $response = $response->withStatus((int)$headers['Status']);
            unset($headers['Status']);
        }

        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, (string) $value);
        }

        $body = new Stream('php://temp', 'r+');
        $body->write($content);

        return $response->withBody($body);
    }
}
