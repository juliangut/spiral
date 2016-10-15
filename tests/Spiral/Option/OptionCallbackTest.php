<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionCallback;

/**
 * Call back option tests.
 */
class OptionCallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testBadCallback()
    {
        $option = new OptionCallback(CURLOPT_HTTPAUTH);

        static::assertEquals(CURLOPT_HTTPAUTH, $option->getOption());
        static::assertEquals('', $option->getValue());

        $option->setValue('false');
    }

    public function testAccessors()
    {
        $option = new OptionCallback(CURLOPT_HTTPAUTH);

        $option->setCallback(function ($value) {
            return $value . ' aaa';
        });
        $option->setValue('bbb');
        static::assertEquals('bbb aaa', $option->getValue());
    }
}
