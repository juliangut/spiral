<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\HttpAuth;

/**
 * @cover \Jgut\Spiral\Option\HttpAuth
 */
class HttpAuthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\HttpAuth::setValue
     */
    public function testAccessors()
    {
        $option = new HttpAuth(false);
        $this->assertEquals(CURLOPT_HTTPAUTH, $option->getOption());
        $this->assertFalse($option->getValue());

        $option = new HttpAuth(true);
        $this->assertEquals(CURLAUTH_BASIC, $option->getValue());
    }
}
