<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * String cURL option wrapper.
 */
class OptionString extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * {@inheritdoc}
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = trim($value);
    }
}
