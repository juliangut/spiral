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
 * Boolean cURL option wrapper.
 */
class OptionBool extends DefaultOption
{
    /**
     * Create boolean cURL option.
     *
     * @param int $option
     */
    public function __construct($option)
    {
        parent::__construct($option);

        $this->value = false;
    }

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
