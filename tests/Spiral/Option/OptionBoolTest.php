<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionBool;

/**
 * @cover \Jgut\Spiral\Option\OptionBoolean
 */
class OptionBoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionBoolean::setValue
     */
    public function testAccessors()
    {
        $option = new OptionBool(CURLOPT_AUTOREFERER);

        $this->assertEquals(CURLOPT_AUTOREFERER, $option->getOption());
        $this->assertFalse($option->getValue());

        $option->setValue(true);
        $this->assertTrue($option->getValue());

        $option->setValue('true');
        $this->assertFalse($option->getValue());
    }
}
