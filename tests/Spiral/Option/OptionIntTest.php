<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionInt;

/**
 * @cover \Jgut\Spiral\Option\OptionInt
 */
class OptionIntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionInt::setValue
     */
    public function testAccessors()
    {
        $option = new OptionInt(CURLOPT_PORT);

        $this->assertEquals(CURLOPT_PORT, $option->getOption());
        $this->assertEquals(0, $option->getValue());

        $option->setValue(1);
        $this->assertEquals(1, $option->getValue());

        $option->setValue(-10);
        $this->assertEquals(0, $option->getValue());

        $option->setMin(20);
        $option->setValue(10);
        $this->assertEquals(20, $option->getValue());

        $option->setMax(10);
        $option->setValue(20);
        $this->assertEquals(10, $option->getValue());
    }
}
