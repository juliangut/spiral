<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Option;
use Jgut\Spiral\Exception\CurlOptionException;

/**
 * Regex cURL option wrapper.
 */
abstract class OptionRegex implements Option
{
    use OptionAware;

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
    protected $message = '"%s" is not valid';

    /**
     * Set option value.
     *
     * @param string $value
     * @throws \Jgut\Exception\CurlOptionException
     */
    protected function setValue($value)
    {
        $value = trim($value);

        if (!preg_match($this->regex, $value)) {
            throw new CurlOptionException(sprintf($this->message, $value));
        }

        $this->value = $value;
    }
}
