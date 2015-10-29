<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\CurlOptionException;
use Jgut\Spiral\Option;

/**
 * cURL option wrappers factory.
 */
abstract class OptionFactory
{
    /**
     * Accepted cURL option aliases.
     *
     * @var array
     */
    private static $optionAliasMap = [
        'http_header'        => CURLOPT_HTTPHEADER,

        'crlf'               => CURLOPT_CRLF,
        'header_out'         => CURLINFO_HEADER_OUT,
        'return_transfer'    => CURLOPT_RETURNTRANSFER,
        'verbose'            => CURLOPT_VERBOSE,
        'user_agent'         => CURLOPT_USERAGENT,
        'ssl_version'        => CURLOPT_SSLVERSION,
        'cookie_file'        => CURLOPT_COOKIEFILE,
        'cookie_jar'         => CURLOPT_COOKIEJAR,
        'referer'            => CURLOPT_REFERER,
        'auto_referer'       => CURLOPT_AUTOREFERER,
        'file_time'          => CURLOPT_FILETIME,
        'user_password'      => CURLOPT_USERPWD,
        'http_version'       => CURLOPT_HTTP_VERSION,
        'port'               => CURLOPT_PORT,
        'encoding'           => CURLOPT_ENCODING,

        'header'             => CURLOPT_HEADER,
        'include'            => CURLOPT_HEADER,

        'connect_timeout'    => CURLOPT_CONNECTTIMEOUT,
        'connection_timeout' => CURLOPT_CONNECTTIMEOUT,

        'timeout'            => CURLOPT_TIMEOUT,
        'max_time'           => CURLOPT_TIMEOUT,

        'ssl_verify_peer'    => CURLOPT_SSL_VERIFYPEER,
        'insecure'           => CURLOPT_SSL_VERIFYPEER,

        'follow_location'    => CURLOPT_FOLLOWLOCATION,
        'follow_redirects'   => CURLOPT_FOLLOWLOCATION,
        'location'           => CURLOPT_FOLLOWLOCATION,

        'max_redirs'         => CURLOPT_MAXREDIRS,
        'max_redirects'      => CURLOPT_MAXREDIRS,

        'cookie'             => CURLOPT_COOKIE,
        'cookies'            => CURLOPT_COOKIE,

        'http_auth'          => CURLOPT_HTTPAUTH,
        'auth'               => CURLOPT_HTTPAUTH,

        'unrestricted_auth'  => CURLOPT_UNRESTRICTED_AUTH,
        'location_trusted'   => CURLOPT_UNRESTRICTED_AUTH,
    ];

    /**
     * cURL accepted options and class mapper.
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     * @see http://www.whatsmyip.org/lib/php-curl-option-guide/
     *
     * @var array
     */
    private static $optionClassMap = [
        // Boolean. Convert Unix newlines to CRLF newlines on transfers
        CURLOPT_CRLF              => 'Crlf',
        // Boolean. Include request header in the output
        CURLINFO_HEADER_OUT       => 'HeaderOut',
        //Boolean. Include response header in the output
        CURLOPT_HEADER            => 'Header',
        // Boolean. Return the transfer as a string instead of outputting
        CURLOPT_RETURNTRANSFER    => 'ReturnTransfer',
        //Boolean. Output verbose information
        CURLOPT_VERBOSE           => 'Verbose',
        // Boolean. False to stop cURL from verifying the peer's certificate
        CURLOPT_SSL_VERIFYPEER    => 'SslVerifyPeer',
        // Boolean. Follow any Location headers sent by server
        CURLOPT_FOLLOWLOCATION    => 'FollowLocation',
        // Boolean. Automatically set the http referer header when following a redirect
        CURLOPT_AUTOREFERER       => 'AutoReferer',
        // Boolean. Keep sending the username and password when following locations
        CURLOPT_UNRESTRICTED_AUTH => 'UnrestrictedAuth',
        // Boolean. Get HTTP header for modification date of file
        CURLOPT_FILETIME          => 'FileTime',
        /*
        Integer. HTTP authentication method(s) to use:
            CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, CURLAUTH_NTLM, CURLAUTH_ANY, CURLAUTH_ANYSAFE
        Currently only CURLAUTH_BASIC is available and implemented as bool
        */
        CURLOPT_HTTPAUTH          => 'HttpAuth',

        // Integer. Number of seconds to wait while trying to connect. 0 to wait indefinitely
        CURLOPT_CONNECTTIMEOUT    => 'ConnectTimeout',
        // Integer. Maximum number of seconds to allow cURL functions to execute
        CURLOPT_TIMEOUT           => 'Timeout',
        // Integer. The maximum amount of HTTP redirections to follow
        CURLOPT_MAXREDIRS         => 'MaxRedirs',
        // Float. Which HTTP version to use. "1.0" for CURL_HTTP_VERSION_1_0 or "1.1" for CURL_HTTP_VERSION_1_1
        CURLOPT_HTTP_VERSION      => 'HttpVersion',
        // Integer. Which SSL version (2 or 3) to use
        CURLOPT_SSLVERSION        => 'SslVersion',
        // Integer. Alternative port number to connect to
        CURLOPT_PORT              => 'Port',

        // String. HTTP header definition
        CURLOPT_HTTPHEADER        => 'HttpHeader',
        // String. Contents of the "User-Agent: " header to be used in a HTTP request
        CURLOPT_USERAGENT         => 'UserAgent',
        // String. Contents of the "Referer: " header to be used in a HTTP request
        CURLOPT_REFERER           => 'Referer',
        // String. Username and password formatted as "[username]:[password]" to use for the connection
        CURLOPT_USERPWD           => 'UserPwd',
        // String|array. Contents of the "Cookie: " header to be used in the HTTP request
        CURLOPT_COOKIE            => 'Cookie',
        // String. Name of the file containing the cookie data
        CURLOPT_COOKIEFILE        => 'CookieFile',
        // String. Name of a file to save all internal cookies to when the handle is closed
        CURLOPT_COOKIEJAR         => 'CookieJar',
        // String Contents of the "Accept-Encoding: " header. This enables decoding of the response
        CURLOPT_ENCODING          => 'Encoding',
    ];

    /**
     * Create cURL option.
     *
     * @param int $option
     * @param mixed $value
     * @throws \Jgut\Exception\CurlOptionException
     * @return \Jgut\Spiral\Option
     */
    public static function create($option, $value)
    {
        if (!array_key_exists($option, self::$optionClassMap)) {
            throw new CurlOptionException(sprintf('"%s" is not valid supported option', $option));
        }

        $optionClass = '\Jgut\Spiral\Option\\' . self::$optionClassMap[$option];

        return new $optionClass($value);
    }

    /**
     * Get mapped option.
     *
     * @param int|string $option
     * @throws \Jgut\Exception\CurlOptionException
     * @return int
     */
    public static function getOptionKey($option)
    {
        if (is_string($option) && array_key_exists($option, self::$optionAliasMap)) {
            $option = self::$optionAliasMap[$option];
        }

        if (!array_key_exists($option, self::$optionClassMap)) {
            throw new CurlOptionException(sprintf('"%s" is not valid supported option', $option));
        }

        return $option;
    }
}
