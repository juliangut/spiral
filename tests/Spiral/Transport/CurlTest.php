<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Transport;

use Jgut\Spiral\Transport;
use Jgut\Spiral\Transport\Curl;

/**
 * @cover \Jgut\Spiral\Transport\Curl
 * @cover \Jgut\Spiral\Transport\TransportAware
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Transport\Curl::setOptions
     * @cover \Jgut\Spiral\Transport\Curl::setOption
     * @cover \Jgut\Spiral\Transport\Curl::hasOption
     * @cover \Jgut\Spiral\Transport\Curl::getOptions
     */
    public function testAccessorsMutators()
    {
        $transport = new Curl;

        $this->assertFalse($transport->hasOption('fake_option'));

        $options = [CURLOPT_VERBOSE => false];

        $transport->setOptions($options);
        $this->assertEquals(1, count($transport->getOptions()));
        $this->assertTrue($transport->hasOption(CURLOPT_VERBOSE));
        $this->assertEquals(
            $options,
            [$transport->getOptions()[0]->getOption() => $transport->getOptions()[0]->getValue()]
        );

        $transport->removeOption('fake_option');
        $transport->removeOption(CURLOPT_VERBOSE);
        $this->assertFalse($transport->hasOption(CURLOPT_VERBOSE));
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::createFromDefaults
     */
    public function testDefaultCreation()
    {
        $transport = Curl::createFromDefaults();

        $this->assertTrue($transport->hasOption(CURLOPT_VERBOSE));
        $this->assertTrue($transport->hasOption('timeout'));
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::close
     * @cover \Jgut\Spiral\Transport\Curl::request
     *
     * @expectedException \Jgut\Spiral\Exception\TransportException
     */
    public function testBadMethod()
    {
        $transport = Curl::createFromDefaults();

        $transport->request('FAKE', 'http://example.com');
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::request
     *
     * @expectedException \Jgut\Spiral\Exception\TransportException
     * @expectedExceptionMessageRegExp /^Could( not|n't) resolve host/
     */
    public function testErrorRequest()
    {
        $transport = Curl::createFromDefaults();

        $transport->request(Transport::METHOD_HEAD, 'http://fake_made_up_web.com');
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::request
     * @cover \Jgut\Spiral\Transport\Curl::forgeOptions
     * @cover \Jgut\Spiral\Transport\Curl::forgeHeaders
     */
    public function testForgeAndInfo()
    {
        $transport = Curl::createFromDefaults();
        $transport->setOption('user_password', 'user:pass');

        $this->assertNull($transport->responseInfo());

        $transport->request(Transport::METHOD_GET, 'http://www.linuxfoundation.org', ['Accept-Charset' => 'utf-8']);

        $this->assertInternalType('array', $transport->responseInfo());
        $this->assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::request
     */
    public function testRequestWithVars()
    {
        $transport = Curl::createFromDefaults();

        $transport->request(Transport::METHOD_GET, 'http://www.linuxfoundation.org', [], ['var' => 'value']);
        $this->assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->request(Transport::METHOD_POST, 'http://www.linuxfoundation.org', [], ['var' => 'value']);
        $this->assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * @cover \Jgut\Spiral\Transport\Curl::request
     * @cover \Jgut\Spiral\Transport\Curl::setMethod
     * @cover \Jgut\Spiral\Transport\TransportAware::options
     * @cover \Jgut\Spiral\Transport\TransportAware::head
     * @cover \Jgut\Spiral\Transport\TransportAware::get
     * @cover \Jgut\Spiral\Transport\TransportAware::post
     * @cover \Jgut\Spiral\Transport\TransportAware::put
     * @cover \Jgut\Spiral\Transport\TransportAware::delete
     * @cover \Jgut\Spiral\Transport\TransportAware::patch
     *
     * @param string $method
     * @param string $shorthand
     *
     * @dataProvider methodProvider
     */
    public function testRequestMethods($method, $shorthand, $expectedCode)
    {
        $transport = Curl::createFromDefaults();

        $transport->request($method, 'http://www.linuxfoundation.org');
        $this->assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        call_user_func([$transport, $shorthand], 'http://www.linuxfoundation.org');
        $this->assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * Provide HTTP methods.
     *
     * @return array
     */
    public function methodProvider()
    {
        return [
            [Transport::METHOD_OPTIONS, 'options', 200],
            [Transport::METHOD_HEAD, 'head', 200],
            [Transport::METHOD_GET, 'get', 200],
            [Transport::METHOD_POST, 'post', 200],
            [Transport::METHOD_PUT, 'put', 200],
            [Transport::METHOD_DELETE, 'delete', 200],
            [Transport::METHOD_PATCH, 'patch', 200],
        ];
    }
}
