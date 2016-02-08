<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Encoding;
use Jgut\Spiral\Option\HeaderOut;

/**
 * @cover \Jgut\Spiral\Option\OptionAware
 */
class OptionAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionAware::__construct
     * @cover \Jgut\Spiral\Option\OptionAware::getOption
     * @cover \Jgut\Spiral\Option\OptionAware::getValue
     * @cover \Jgut\Spiral\Option\OptionAware::__toString
     */
    public function testAccessors()
    {
        $option = new Encoding('UTF8');
        $this->assertEquals(CURLOPT_ENCODING, $option->getOption());
        $this->assertEquals('UTF8', $option->getValue());

        $option = new HeaderOut(true);
        $this->assertEquals(CURLINFO_HEADER_OUT, $option->getOption());
    }
}
