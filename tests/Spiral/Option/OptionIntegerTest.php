<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Port;

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
        $option = new Port(10);
        $this->assertEquals(CURLOPT_PORT, $option->getOption());
        $this->assertEquals(10, $option->getValue());

        $option = new Port(-10);
        $this->assertEquals(0, $option->getValue());

        $option = new Port(999999);
        $this->assertEquals(99999, $option->getValue());
    }
}
