<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests;

use Jgut\Spiral\Client;
use Jgut\Spiral\Transport\Curl;
use Phly\Http\Request;
use Phly\Http\Response;

/**
 * @cover Jgut\Spiral\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
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
        $response = $client->request($request, $response);

        $this->assertEquals(500, $response->getStatusCode());
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
HTTP/1.1 200 OK
Server: nginx

<!doctype html>
<html>
</html>
RESP;
        $transferInfo = [
            'header_size' => 31,
            'http_code' => 200,
            'content_type' => 'text/html; charset=utf-8',
        ];

        $transport = $this->getMockBuilder('\Jgut\Spiral\Transport\Curl')->disableOriginalConstructor()->getMock();
        $transport->method('setOption')->will($this->returnValue(true));
        $transport->method('request')->will($this->returnValue($responseContent));
        $transport->method('responseInfo')->will($this->returnValue($transferInfo));
        $transport->method('close')->will($this->returnValue(true));
        $transport->method('hasOption')->will($this->returnValue(true));

        $request = new Request('', 'GET');
        $response = new Response;

        $client = new Client($transport);
        $response = $client->request($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/^<!doctype html>/i', $response->getBody()));
    }
}
