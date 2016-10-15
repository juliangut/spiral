<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Transport;

use Jgut\Spiral\Option\OptionFactory;
use Jgut\Spiral\Transport;
use Jgut\Spiral\Transport\Curl;

/**
 * Curl transport tests.
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testGetterSetter()
    {
        $transport = new Curl;

        static::assertFalse($transport->hasOption('fake_option'));

        $options = [CURLOPT_VERBOSE => false];

        $transport->setOptions($options);
        static::assertEquals(1, count($transport->getOptions()));
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

        $transport->request(Transport::METHOD_HEAD, 'http://fake_made_up_web.com');
    }

    public function testForgeAndInfo()
    {
        $transport = Curl::createFromDefaults();
        $transport->setOption('user_password', 'user:pass');

        static::assertNull($transport->responseInfo());

        $transport->request(Transport::METHOD_GET, 'http://www.php.net', ['Accept-Charset' => 'utf-8']);

        static::assertInternalType('array', $transport->responseInfo());
        static::assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->close();
    }

    public function testRequestWithVars()
    {
        $transport = Curl::createFromDefaults();

        $transport->request(Transport::METHOD_GET, 'http://www.php.net', [], ['var' => 'value']);
        static::assertEquals(200, $transport->responseInfo(CURLINFO_HTTP_CODE));

        $transport->request(Transport::METHOD_POST, 'http://www.php.net', [], ['var' => 'value']);
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
    public function testRequestMethods($method, $shorthand, $expectedCode)
    {
        $transport = Curl::createFromDefaults();
        $transport->setOption(CURLINFO_HEADER_OUT, true);

        $transport->request($method, 'http://www.php.net');
        if ($transport->responseInfo(CURLINFO_HTTP_CODE) !== $expectedCode) {
            var_dump($transport->responseInfo());
        }
        static::assertEquals($expectedCode, $transport->responseInfo(CURLINFO_HTTP_CODE));

        call_user_func([$transport, $shorthand], 'http://www.php.net');
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
