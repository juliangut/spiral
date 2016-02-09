<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionFactory;

/**
 * @cover \Jgut\Spiral\Option\OptionFactory
 */
class OptionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::getOptionKey
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testUnknownOptionKey()
    {
        OptionFactory::getOptionKey('fictitious_option');
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::getOptionKey
     */
    public function testOptionKey()
    {
        $this->assertEquals(CURLOPT_TIMEOUT, OptionFactory::getOptionKey('timeout'));
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::create
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testWrongCreation()
    {
        OptionFactory::build('fictitious_option', 'value');
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::build
     */
    public function testRegexCreation()
    {
        $option = OptionFactory::build(CURLOPT_USERPWD, 'user:password');

        $this->assertEquals(CURLOPT_USERPWD, $option->getOption());
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::build
     */
    public function testIntCreation()
    {
        $option = OptionFactory::build(CURLOPT_PORT, 100);

        $this->assertEquals(CURLOPT_PORT, $option->getOption());
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::build
     */
    public function testDefaultCreation()
    {
        $option = OptionFactory::build(CURLOPT_STDERR, 'location');

        $this->assertEquals(CURLOPT_STDERR, $option->getOption());
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::build
     */
    public function testCallbackCreation()
    {
        $option = OptionFactory::build(CURLOPT_HTTPAUTH, true);

        $this->assertEquals(CURLOPT_HTTPAUTH, $option->getOption());
        $this->assertEquals(CURLAUTH_BASIC, $option->getValue());

        $cookies = [
            'cookieOne' => 'one',
            'cookieTwo' => 'two',
        ];
        $option = OptionFactory::build(CURLOPT_COOKIE, $cookies);

        $this->assertEquals(CURLOPT_COOKIE, $option->getOption());
        $this->assertEquals('cookieOne=one; cookieTwo=two', $option->getValue());
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::build
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testHttpVersionCallbackCreation()
    {
        $option = OptionFactory::build(CURLOPT_HTTP_VERSION, 1.1);

        $this->assertEquals(CURLOPT_HTTP_VERSION, $option->getOption());
        $this->assertEquals(CURL_HTTP_VERSION_1_1, $option->getValue());

        OptionFactory::build(CURLOPT_HTTP_VERSION, '1.5');
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFactory::create
     */
    public function testCreation()
    {
        $option = OptionFactory::build(CURLOPT_REFERER, 'referer');

        $this->assertEquals(CURLOPT_REFERER, $option->getOption());
    }
}
