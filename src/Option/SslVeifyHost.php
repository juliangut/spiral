<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * cURL CURLOPT_SSL_VERIFYHOST option.
 */
class SslVerifyHost extends OptionRegex
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_SSL_VERIFYHOST;

    /**
     * @inheritdoc
     */
    protected $regex = '/^[12]$/';

    /**
     * @inheritdoc
     */
    protected $message = '"%s" is not valid SSL verify host value';
}
