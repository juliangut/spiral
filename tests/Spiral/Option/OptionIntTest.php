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
 * Integer option tests.
 */
class OptionIntTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $option = new OptionInt(CURLOPT_PORT);

        static::assertEquals(CURLOPT_PORT, $option->getOption());
        static::assertEquals(0, $option->getValue());

        $option->setValue(1);
        static::assertEquals(1, $option->getValue());

        $option->setValue(-10);
        static::assertEquals(0, $option->getValue());

        $option->setMin(20);
        $option->setValue(10);
        static::assertEquals(20, $option->getValue());

        $option->setMax(10);
        $option->setValue(20);
        static::assertEquals(10, $option->getValue());
    }
}
