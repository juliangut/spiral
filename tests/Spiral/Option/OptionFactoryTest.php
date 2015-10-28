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
 * @cover Jgut\Spiral\Option\OptionFactory
 */
class OptionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover Jgut\Spiral\Option\OptionFactory::getOptionKey
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testUnknownOptionKey()
    {
        OptionFactory::getOptionKey('ficticiuos_option');
    }

    /**
     * @cover Jgut\Spiral\Option\OptionFactory::getOptionKey
     */
    public function testOptionKey()
    {
        $this->assertEquals(CURLOPT_TIMEOUT, OptionFactory::getOptionKey('timeout'));
    }

    /**
     * @cover Jgut\Spiral\Option\OptionFactory::create
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testWrongCreation()
    {
        $option = OptionFactory::create('referer', 'referer');
    }

    /**
     * @cover Jgut\Spiral\Option\OptionFactory::create
     */
    public function testCreation()
    {
        $option = OptionFactory::create(CURLOPT_REFERER, 'referer');

        $this->assertEquals(CURLOPT_REFERER, $option->getOption());
    }
}
