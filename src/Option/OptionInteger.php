<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Option;

/**
 * Integer cURL option wrapper.
 */
abstract class OptionInteger implements Option
{
    use OptionAware;

    /**
     * Minimum valid value.
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Set option value.
     *
     * @param int $value
     */
    protected function setValue($value)
    {
        $this->value = max($this->min, $value);
    }
}
