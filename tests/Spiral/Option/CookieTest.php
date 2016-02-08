<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Cookie;

/**
 * @cover \Jgut\Spiral\Option\Cookie
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\Cookie::setValue
     */
    public function testAccessors()
    {
        $cookies = [
            'cookieOne' => 'one',
            'cookieTwo' => 'two',
        ];

        $option = new Cookie($cookies);
        $this->assertEquals(CURLOPT_COOKIE, $option->getOption());
        $this->assertEquals(http_build_query($cookies, '', '; '), $option->getValue());
    }
}
