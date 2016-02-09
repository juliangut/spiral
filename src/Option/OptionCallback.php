<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\OptionException;

/**
 * Special callback cURL option wrapper.
 */
class OptionCallback extends Option
{
    /**
     * Callback to handle value set.
     *
     * @var callable
     */
    protected $callback;

    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * Set value handler callback.
     *
     * @param callable $callback
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public function setValue($value)
    {
        if ($this->callback === null) {
            throw new OptionException('No callback defined');
        }

        $callback = $this->callback;
        $this->value = $callback($value);
    }
}
