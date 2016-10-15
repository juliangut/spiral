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
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

/**
 * Client handler tests.
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testGettersSetters()
    {
        $transport = $this->getMock('\Jgut\Spiral\Transport');

        $client = new Client();

        $client->setTransport($transport);

        static::assertEquals($transport, $client->getTransport());
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

    public function testRequest()
    {
        $responseContent = <<<RESP
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

<!doctype html>
<html>
</html>
RESP;
        $transferInfo = [
            'header_size' => 452,
            'http_code' => 200,
            'content_type' => 'text/html; charset=utf-8',
        ];

        $transport = $this->getMockBuilder('\Jgut\Spiral\Transport\Curl')
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
        static::assertEquals(1, preg_match('/^<!doctype html>/i', $response->getBody()));
    }
}
