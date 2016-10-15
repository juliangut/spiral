<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Option;

/**
 * Integer cURL option wrapper.
 */
class OptionInt extends Option
{
    /**
     * Minimum valid value.
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum valid value.
     *
     * @var int
     */
    protected $max;

    /**
     * {@inheritdoc}
     */
    protected $value = 0;

    /**
     * Set minimum value.
     *
     * @param int $min
     */
    public function setMin($min)
    {
        $this->min = (int) $min;
    }

    /**
     * Set maximum value.
     *
     * @param int $max
     */
    public function setMax($max)
    {
        $this->max = (int) $max;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $value
     */
    public function setValue($value)
    {
        $value = max($this->min, $value);

        if ($this->max !== null) {
            $value = min($value, $this->max);
        }

        $this->value = $value;
    }
}
