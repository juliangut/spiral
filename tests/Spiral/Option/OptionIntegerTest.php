<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\MaxRedirs;

/**
 * @cover \Jgut\Spiral\Option\OptionInteger
 */
class OptionIntegerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionInt::setValue
     */
    public function testAccessors()
    {
        $option = new MaxRedirs(10);
        $this->assertEquals(CURLOPT_MAXREDIRS, $option->getOption());
        $this->assertEquals(10, $option->getValue());

        $option = new MaxRedirs(-10);
        $this->assertEquals(0, $option->getValue());
    }
}
