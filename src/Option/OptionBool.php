<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * Boolean cURL option wrapper.
 */
class OptionBool extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = false;

    /**
     * {@inheritdoc}
     *
     * @param bool $value
     */
    public function setValue($value)
    {
        $this->value = $value === true;
    }
}
