<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Option;

/**
 * @cover \Jgut\Spiral\Option\OptionAware
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionAware::__construct
     * @cover \Jgut\Spiral\Option\OptionAware::getOption
     * @cover \Jgut\Spiral\Option\OptionAware::getValue
     * @cover \Jgut\Spiral\Option\OptionAware::setValue
     */
    public function testAccessors()
    {
        $option = new Option(CURLOPT_ENCODING);

        $this->assertEquals(CURLOPT_ENCODING, $option->getOption());
        $this->assertNull($option->getValue());

        $option->setValue(true);
        $this->assertEquals(true, $option->getValue());

        $option->setValue(1);
        $this->assertEquals(1, $option->getValue());

        $option->setValue('true');
        $this->assertEquals('true', $option->getValue());
    }
}
