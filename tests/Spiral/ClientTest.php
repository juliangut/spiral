<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests;

use Jgut\Spiral\Client;
use Jgut\Spiral\Exception\TransportException;
use Phly\Http\Request;
use Phly\Http\Response;

/**
 * @cover \Jgut\Spiral\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Jgut\Spiral\Client::__construct
     * @covers Jgut\Spiral\Client::setTransport
     * @covers Jgut\Spiral\Client::getTransport
     */
    public function testMutatorAccessor()
    {
        $transport = $this->getMock('\Jgut\Spiral\Transport');

        $client = new Client();

        $client->setTransport($transport);

        $this->assertEquals($transport, $client->getTransport());
    }

    /**
     * @covers Jgut\Spiral\Client::__construct
     * @covers Jgut\Spiral\Client::request
     * @covers Jgut\Spiral\Client::getTransport
     */
    public function testBadRequest()
    {
        $request = new Request('http://fake_made_up_web.com', 'GET');
        $response = new Response;

        $client = new Client();
        try {
            $client->request($request, $response);
        } catch (TransportException $exception) {
            $this->assertEquals(CURLE_COULDNT_RESOLVE_HOST, $exception->getCode());
            $this->assertEquals('', $exception->getCategory());
        }
    }

    /**
     * @covers Jgut\Spiral\Client::__construct
     * @covers Jgut\Spiral\Client::request
     * @covers Jgut\Spiral\Client::getTransport
     * @covers Jgut\Spiral\Client::getTransferHeaders
     * @covers Jgut\Spiral\Client::populateResponse
     */
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

        $transport = $this->getMockBuilder('\Jgut\Spiral\Transport\Curl')->disableOriginalConstructor()->getMock();
        $transport->expects($this->once())->method('request')->will($this->returnValue($responseContent));
        $transport->expects($this->once())->method('responseInfo')->will($this->returnValue($transferInfo));
        $transport->expects($this->once())->method('close');

        $request = new Request('', 'GET');
        $response = new Response;

        $client = new Client($transport);
        $response = $client->request($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('nginx', $response->getHeaderLine('Server'));
        $this->assertFalse($response->hasHeader('Location'));
        $this->assertEquals(1, preg_match('/^<!doctype html>/i', $response->getBody()));
    }
}
