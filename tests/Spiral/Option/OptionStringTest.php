<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionString;

/**
 * String option tests.
 */
class OptionStringTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $option = new OptionString(CURLOPT_REFERER);

        static::assertEquals(CURLOPT_REFERER, $option->getOption());
        static::assertEquals('', $option->getValue());

        $option->setValue('value');
        static::assertEquals('value', $option->getValue());
    }
}
