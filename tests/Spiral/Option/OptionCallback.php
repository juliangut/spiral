<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionCallback;

/**
 * @cover \Jgut\Spiral\Option\OptionCallback
 */
class OptionCallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionCallback::setValue
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testBadCallback()
    {
        $option = new OptionCallback(CURLOPT_HTTPAUTH);

        $this->assertEquals(CURLOPT_HTTPAUTH, $option->getOption());
        $this->assertEquals('', $option->getValue());

        $option->setValue(false);
    }

    /**
     * @cover \Jgut\Spiral\Option\UserPwd::setValue
     */
    public function testAccessors()
    {
        $option = new OptionCallback(CURLOPT_HTTPAUTH);

        $option->setCallback(function ($value) {
            return 'aaa';
        });
        $option->setValue('bbb');
        $this->assertEquals('aaa', $option->getValue());
    }
}
