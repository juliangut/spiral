<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionBool;

/**
 * Boolean option tests.
 */
class OptionBoolTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $option = new OptionBool(CURLOPT_AUTOREFERER);

        static::assertEquals(CURLOPT_AUTOREFERER, $option->getOption());
        static::assertFalse($option->getValue());

        $option->setValue(true);
        static::assertTrue($option->getValue());

        $option->setValue('string');
        static::assertFalse($option->getValue());
    }
}
