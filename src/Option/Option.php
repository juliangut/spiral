<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Option as OptionInterface;

/**
 * Basic cURL options.
 */
class Option implements OptionInterface
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
     * @inheritdoc
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }
}
