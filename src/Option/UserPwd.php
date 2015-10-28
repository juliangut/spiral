<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

class UserPwd extends OptionRegex
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_USERPWD;

    /**
     * @inheritdoc
     */
    protected $regex = '/^[^\n:]+:[^\n:]+$/';

    /**
     * @inheritdoc
     */
    protected $message = '%sValue provided to CURLOPT_USERPWD is not valid';
}
