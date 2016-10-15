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
 * Default cURL option.
 */
class DefaultOption implements OptionInterface
{
    /**
     * Option.
     *
     * @var int
     */
    protected $option;

    /**
     * Option value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Create cURL option.
     *
     * @param int $option
     */
    public function __construct($option)
    {
        $this->option = $option;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
