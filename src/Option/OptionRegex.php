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
 * Regex cURL option wrapper.
 */
class OptionRegex extends Option
{
    /**
     * Regex to check.
     *
     * @var string
     */
    protected $regex = '/^$/';

    /**
     * Error message.
     *
     * @var string
     */
    protected $message = '"%s" is not a valid value';

    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * Set regex expression.
     *
     * @param $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Set fail message.
     *
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

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

        if (!preg_match($this->regex, $value)) {
            throw new OptionException(sprintf($this->message, $value));
        }

        $this->value = $value;
    }
}
