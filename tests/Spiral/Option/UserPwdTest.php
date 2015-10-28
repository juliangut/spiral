<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\UserPwd;

/**
 * @cover Jgut\Spiral\Option\UserPwd
 */
class UserPwdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover Jgut\Spiral\Option\UserPwd::setValue
     * @expectedException Jgut\Spiral\Exception\CurlOptionException
     */
    public function testBadFormatted()
    {
        $option = new UserPwd('Username Password');
    }

    /**
     * @cover Jgut\Spiral\Option\UserPwd::setValue
     */
    public function testAccessors()
    {
        $option = new UserPwd('user:passwd');

        $this->assertEquals(CURLOPT_USERPWD, $option->getOption());
        $this->assertEquals('user:passwd', $option->getValue());
    }
}
