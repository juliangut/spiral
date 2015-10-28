<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\HttpVersion;

/**
 * @cover Jgut\Spiral\Option\HttpVersion
 */
class HttpVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover Jgut\Spiral\Option\HttpVersion::setValue
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testInvalidVersion()
    {
        $option = new HttpVersion(2);
    }

    /**
     * @cover Jgut\Spiral\Option\HttpVersion::setValue
     */
    public function testAccessors()
    {
        $option = new HttpVersion(1);
        $this->assertEquals(CURLOPT_HTTP_VERSION, $option->getOption());
        $this->assertEquals(CURL_HTTP_VERSION_1_0, $option->getValue());

        $option = new HttpVersion(1.0);
        $this->assertEquals(CURL_HTTP_VERSION_1_0, $option->getValue());

        $option = new HttpVersion('1.1');
        $this->assertEquals(CURL_HTTP_VERSION_1_1, $option->getValue());
    }
}
