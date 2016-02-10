<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\OptionException;

/**
 * File cURL option wrapper.
 */
class OptionFile extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * {@inheritdoc}
     *
     * @param string $value
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public function setValue($value)
    {
        $value = trim($value);
        if (!is_file($value) || !is_readable($value)) {
            throw new OptionException(sprintf('"%s" is not a readable file', $value));
        }

        $this->value = $value;
    }
}
