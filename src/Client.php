<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral;

use Jgut\Spiral\Transport\Curl;
use Jgut\Spiral\Exception\CurlException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * cURL transport manager.
     *
     * @var \Jgut\Spiral\Transport
     */
    private $transport;

    /**
     * @param \Jgut\Spiral\Transport $transport
     */
    public function __construct(Transport $transport = null)
    {
        $this->transport = $transport;
    }

    /**
     * Run cURL request.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $vars
     * @param array $flags
     * @return \Psr\Http\Message\RequestInterface
     */
    public function request(
        RequestInterface $request,
        ResponseInterface $response,
        array $vars = [],
        array $flags = []
    ) {
        $transport = $this->getTransport();
        $transport->setOption(CURLOPT_HTTP_VERSION, $request->getProtocolVersion());

        try {
            $cURLResponse = $transport->request(
                $request->getMethod(),
                (string) $request->getUri(),
                $request->getHeaders(),
                $vars,
                $flags
            );

            $transferInfo = $transport->responseInfo();

            $transport->close();
        } catch (CurlException $exception) {
            return $response->withStatus(500, $exception->getMessage());
        }

        $responseHeaders = '';
        $responseContent = $cURLResponse;

        if ($transport->hasOption(CURLOPT_HEADER, true)) {
            $headersSize = $transferInfo['header_size'];

            $responseHeaders = rtrim(substr($cURLResponse, 0, $headersSize));
            $responseContent = (strlen($cURLResponse) === $headersSize) ? '' : substr($cURLResponse, $headersSize);
        }

        $responseHeaders = $this->getTransferHeaders(
            preg_split('/(\\r?\\n)/', $responseHeaders),
            $responseContent,
            $transferInfo
        );

        return $this->populateResponse($response, $responseHeaders, $responseContent);
    }

    /**
     * Retrieve transport object
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
     * Get response headers based on transfer information.
     *
     * @param array transferHeaders
     * @param string transferContent
     * @param array $transferInfo
     * @return array
     */
    protected function getTransferHeaders(array $transferHeaders, $transferContent, array $transferInfo)
    {
        $responseHeaders = [
            'Status' => $transferInfo['http_code'],
            'Content-Type' => $transferInfo['content_type'],
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
     * @param array $headers
     * @param string $content
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function populateResponse(ResponseInterface $response, array $headers, $content)
    {
        if (isset($headers['Protocol-Version'])) {
            $response = $response->withProtocolVersion($headers['Protocol-Version']);
            unset($headers['Protocol-Version']);
        }

        if (isset($headers['Status'])) {
            $response = $response->withStatus($headers['Status']);
            unset($headers['Status']);
        }

        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, (string) $value);
        }

        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $body->write($content);

        return $response;
    }
}
