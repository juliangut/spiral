<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\OptionException;
use Jgut\Spiral\Option;

/**
 * File cURL option wrapper.
 */
abstract class OptionFile implements Option
{
    use OptionAware;

    /**
     * Set option value.
     *
     * @param bool $value
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    protected function setValue($value)
    {
        $value = trim($value);
        if (!is_file($value) || !is_readable($value)) {
            throw new OptionException(sprintf('"%s" is not a readable file', $value));
        }

        $this->value = $value;
    }
}
