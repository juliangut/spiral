<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\Referer;

/**
 * @cover \Jgut\Spiral\Option\OptionString
 */
class OptionStringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionString::setValue
     */
    public function testAccessors()
    {
        $option = new Referer('referer');
        $this->assertEquals(CURLOPT_REFERER, $option->getOption());
        $this->assertEquals('referer', $option->getValue());
    }
}
