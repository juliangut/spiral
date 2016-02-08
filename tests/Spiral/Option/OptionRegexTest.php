<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\SslVersion;

/**
 * @cover \Jgut\Spiral\Option\OptionRegex
 */
class OptionRegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionRegex::setValue
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testBadFormatted()
    {
        new SslVersion(10);
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionRegex::setValue
     */
    public function testAccessors()
    {
        $option = new SslVersion(2);
        $this->assertEquals(CURLOPT_SSLVERSION, $option->getOption());
        $this->assertEquals(2, $option->getValue());
    }
}
