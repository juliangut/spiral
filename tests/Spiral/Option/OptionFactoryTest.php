<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionFactory;

/**
 * Option factory tests.
 */
class OptionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testUnknownOptionKey()
    {
        OptionFactory::getOptionKey('fictitious_option');
    }

    public function testOptionKey()
    {
        static::assertEquals(CURLOPT_TIMEOUT, OptionFactory::getOptionKey('timeout'));
    }

    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testWrongCreation()
    {
        OptionFactory::build('fictitious_option', 'value');
    }

    public function testRegexCreation()
    {
        $option = OptionFactory::build(CURLOPT_USERPWD, 'user:password');

        static::assertEquals(CURLOPT_USERPWD, $option->getOption());
    }

    public function testIntCreation()
    {
        $option = OptionFactory::build(CURLOPT_PORT, 100);

        static::assertEquals(CURLOPT_PORT, $option->getOption());
    }

    public function testDefaultCreation()
    {
        $option = OptionFactory::build(CURLOPT_STDERR, 'location');

        static::assertEquals(CURLOPT_STDERR, $option->getOption());
    }

    public function testCallbackCreation()
    {
        $cookies = [
            'cookieOne' => 'one',
            'cookieTwo' => 'two',
        ];
        $option = OptionFactory::build(CURLOPT_COOKIE, $cookies);

        static::assertEquals(CURLOPT_COOKIE, $option->getOption());
        static::assertEquals('cookieOne=one; cookieTwo=two', $option->getValue());
    }

    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testHttpVersionCallbackCreation()
    {
        $option = OptionFactory::build(CURLOPT_HTTP_VERSION, 1.1);

        static::assertEquals(CURLOPT_HTTP_VERSION, $option->getOption());
        static::assertEquals(CURL_HTTP_VERSION_1_1, $option->getValue());

        OptionFactory::build(CURLOPT_HTTP_VERSION, '1.5');
    }

    public function testCreation()
    {
        $option = OptionFactory::build(CURLOPT_REFERER, 'referer');

        static::assertEquals(CURLOPT_REFERER, $option->getOption());
    }
}
