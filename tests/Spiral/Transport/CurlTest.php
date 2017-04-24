<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Transport;

use Fig\Http\Message\RequestMethodInterface;
use Jgut\Spiral\Option\OptionFactory;
use Jgut\Spiral\Transport\Curl;

/**
 * Curl transport tests.
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{

    const TESTHOST = 'http://httpbin.org';

    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testGetterSetter()
    {
        $transport = new Curl;

        static::assertFalse($transport->hasOption('fake_option'));

        $options = [CURLOPT_VERBOSE => false];

        $transport->setOptions($options);
        static::assertCount(1, $transport->getOptions());
        static::assertTrue($transport->hasOption(CURLOPT_VERBOSE));
        static::assertTrue($transport->hasOption(OptionFactory::build(CURLOPT_VERBOSE, false)));
        static::assertEquals(
            $options,
            [$transport->getOptions()[0]->getOption() => $transport->getOptions()[0]->getValue()]
        );

        $transport->removeOption('fake_option');
        $transport->removeOption(CURLOPT_VERBOSE);
        $transport->removeOption(OptionFactory::build(CURLOPT_VERBOSE, false));
        static::assertFalse($transport->hasOption(CURLOPT_VERBOSE));

        $transport->setOption('fake_option');
    }

    public function testDefaultCreation()
    {
        $transport = Curl::createFromDefaults();

        static::assertTrue($transport->hasOption(CURLOPT_VERBOSE));
        static::assertTrue($transport->hasOption('timeout'));
    }

    /**
     * @expectedException \Jgut\Spiral\Exception\TransportException
     * @expectedExceptionMessageRegExp /^Could( not|n't) resolve host/
     */
    public function testErrorRequest()
    {
        $transport = Curl::createFromDefaults();

        $transport->request(RequestMethodInterface::METHOD_HEAD, 'http://fake_made_up_web.com');
    }

    public function testForgeAndInfo()
    {
        $transport = Curl::createFromDefaults();
        $transport->setOption('user_password', 'user:pass');

        static::assertNull($transport->responseInfo());

        $transport->request(RequestMethodInterface::METHOD_GET, static::TESTHOST, ['Accept-Charset' => 'utf-8']);

        static::assertInternalType('array', $transport->responseInfo());
        static::assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * @param string $method
     * @param string $shorthand
     * @param int    $expectedCode
     *
     * @dataProvider methodProvider
     */
    public function testRequestMethods($method, $shorthand, $expectedCode, $path)
    {
        $transport = Curl::createFromDefaults();

        $transport->request($method, static::TESTHOST . $path);
        static::assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->{$shorthand}(static::TESTHOST . $path);
        static::assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * Provide HTTP methods.
     *
     * @return array[]
     */
    public function methodProvider()
    {
        return [
            [RequestMethodInterface::METHOD_OPTIONS, 'options', 200, '/'],
            [RequestMethodInterface::METHOD_HEAD, 'head', 200, '/'],
            [RequestMethodInterface::METHOD_GET, 'get', 200, '/'],
            [RequestMethodInterface::METHOD_DELETE, 'delete', 200, '/delete'],
        ];
    }

    /**
     * @param string $method
     * @param string $shorthand
     * @param int    $expectedCode
     *
     * @dataProvider methodPayloadProvider
     */
    public function testRequestWithPayload($method, $shorthand, $expectedCode, $path)
    {
        $transport = Curl::createFromDefaults();

        $transport->request($method, static::TESTHOST . $path, [], 'var=value');
        static::assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->{$shorthand}(static::TESTHOST . $path, [], 'var=value');
        static::assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    /**
     * Provide HTTP methods with payload.
     *
     * @return array[]
     */
    public function methodPayloadProvider()
    {
        return [
            [RequestMethodInterface::METHOD_POST, 'post', 200, '/post'],
            [RequestMethodInterface::METHOD_PUT, 'put', 200, '/put'],
            [RequestMethodInterface::METHOD_PATCH, 'patch', 200, '/patch'],
        ];
    }
}
