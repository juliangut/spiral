<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Option;

/**
 * Default option tests.
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $option = new Option(CURLOPT_ENCODING);

        static::assertEquals(CURLOPT_ENCODING, $option->getOption());
        static::assertNull($option->getValue());

        $option->setValue(true);
        static::assertEquals(true, $option->getValue());

        $option->setValue(1);
        static::assertEquals(1, $option->getValue());

        $option->setValue('true');
        static::assertEquals('true', $option->getValue());
    }
}
