<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionRegex;

/**
 * Regex option tests.
 */
class OptionRegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testBadFormatted()
    {
        $option = new OptionRegex(CURLOPT_USERPWD);

        static::assertEquals(CURLOPT_USERPWD, $option->getOption());
        static::assertEquals('', $option->getValue());

        $option->setValue('/^a$/');
        static::assertEquals('a', $option->getValue());

        $option->setValue('value');
    }

    /**
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
