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
 * @cover Jgut\Spiral\Option\SslVersion
 */
class SslVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover Jgut\Spiral\Option\SslVersion::setValue
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testTooLow()
    {
        $option = new SslVersion(-1);
    }

    /**
     * @cover Jgut\Spiral\Option\SslVersion::setValue
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testTooHigh()
    {
        $option = new SslVersion(7);
    }

    /**
     * @cover Jgut\Spiral\Option\SslVersion::setValue
     */
    public function testAccessors()
    {
        $option = new SslVersion(3);

        $this->assertEquals(CURLOPT_SSLVERSION, $option->getOption());
        $this->assertEquals(3, $option->getValue());
    }
}
