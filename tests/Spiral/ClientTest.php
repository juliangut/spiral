<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests;

use Jgut\Spiral\Client;
use Jgut\Spiral\Exception\TransportException;
use Jgut\Spiral\Transport\Curl;
use Jgut\Spiral\Transport\TransportInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

/**
 * Client handler tests.
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testTransport()
    {
        /* @var TransportInterface $transport */
        $transport = $this->getMockBuilder(TransportInterface::class)
            ->getMock();

        $client = new Client();

        $client->setTransport($transport);

        static::assertEquals($transport, $client->getTransport());
    }

    public function testAutoClose()
    {
        $client = new Client();

        static::assertTrue($client->isCloseOnError());

        $client->setCloseOnError(false);

        static::assertFalse($client->isCloseOnError());
    }

    public function testBadRequest()
    {
        $request = new Request('http://fake_made_up_web.com', 'GET');
        $response = new Response;

        $client = new Client();
        try {
            $client->request($request, $response);
        } catch (TransportException $exception) {
            static::assertEquals(CURLE_COULDNT_RESOLVE_HOST, $exception->getCode());
            static::assertEquals('', $exception->getCategory());
        }
    }

    /**
     * @dataProvider responseProvider
     *
     * @param string $responseContent
     * @param array  $transferInfo
     * @param string $contentRegex
     */
    public function testRequest($responseContent, $transferInfo, $contentRegex)
    {
        $transport = $this->getMockBuilder(Curl::class)
            ->disableOriginalConstructor()
            ->getMock();
        $transport
            ->expects(static::once())
            ->method('request')
            ->will(static::returnValue($responseContent));
        $transport
            ->expects(static::once())
            ->method('responseInfo')
            ->will(static::returnValue($transferInfo));
        $transport
            ->expects(static::once())
            ->method('close');

        $request = new Request('', 'GET');
        $response = new Response;

        $client = new Client($transport);
        $response = $client->request($request, $response);

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals('nginx', $response->getHeaderLine('Server'));
        static::assertFalse($response->hasHeader('Location'));
        static::assertEquals(1, preg_match($contentRegex, $response->getBody()));
    }

    public function responseProvider()
    {
        $emptyResponse = <<<RESP
HTTP/1.1 302 FOUND
Server: nginx
Date: Tue, 09 Feb 2016 11:54:35 GMT
Content-Type: text/html; charset=utf-8
Content-Length: 0
Connection: keep-alive
Location: get
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true

HTTP/1.1 200 OK
Server: nginx
Date: Tue, 09 Feb 2016 11:54:35 GMT
Content-Type: text/html; charset=utf-8
Content-Length: 30
Connection: keep-alive
Access-Control-Allow-Origin: *
Access-Control-Allow-Credentials: true

RESP;
        $bodyResponse = $emptyResponse . '<!doctype html>
<html>
</html>
';

        return [
            [
                $emptyResponse,
                [
                    'header_size' => 451,
                    'http_code' => 200,
                    'content_type' => 'text/html; charset=utf-8',
                ],
                '/^$/',
            ],
            [
                $bodyResponse,
                [
                    'header_size' => strlen($emptyResponse),
                    'http_code' => 200,
                    'content_type' => 'text/html; charset=utf-8',
                ],
                '/^<!doctype html>/i',
            ],
        ];
    }
}
