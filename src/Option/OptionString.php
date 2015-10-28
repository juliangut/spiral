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
 * String cURL option wrapper.
 */
abstract class OptionString implements Option
{
    use OptionAware;

    /**
     * Set option value.
     *
     * @param string $value
     */
    protected function setValue($value)
    {
        $this->value = trim($value);
    }
}
