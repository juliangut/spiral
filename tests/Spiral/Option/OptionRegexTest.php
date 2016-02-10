<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionRegex;

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
        $option = new OptionRegex(CURLOPT_USERPWD);

        $this->assertEquals(CURLOPT_USERPWD, $option->getOption());
        $this->assertEquals('', $option->getValue());

        $option->setValue('/^a$/');
        $this->assertEquals('a', $option->getValue());

        $option->setValue('value');
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionRegex::setValue
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     * @expectedExceptionMessage Invalid!
     */
    public function testAccessors()
    {
        $option = new OptionRegex(CURLOPT_USERPWD);

        $option->setRegex('/^a$/');
        $option->setMessage('Invalid!');

        $option->setValue('b');
    }
}
