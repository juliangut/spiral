<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Tests\Option;

use Jgut\Spiral\Option\OptionFile;

/**
 * @cover \Jgut\Spiral\Option\OptionFile
 */
class OptionFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover \Jgut\Spiral\Option\OptionFile::setValue
     *
     * @expectedException \Jgut\Spiral\Exception\OptionException
     */
    public function testNoAccess()
    {
        $option = new OptionFile(CURLOPT_COOKIEFILE);

        $this->assertEquals(CURLOPT_COOKIEFILE, $option->getOption());
        $this->assertEquals('', $option->getValue());

        $option->setValue('fake_file');
    }

    /**
     * @cover \Jgut\Spiral\Option\OptionFile::setValue
     */
    public function testAccessors()
    {
        $file = sys_get_temp_dir() . '/JgutSpiralOptionFile';
        touch($file);

        $option = new OptionFile(CURLOPT_COOKIEFILE);

        $option->setValue($file);
        $this->assertEquals($file, $option->getValue());

        unlink($file);
    }
}
