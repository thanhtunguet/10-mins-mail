<?php
/**
 * Created by PhpStorm.
 * User: thanhtunguet
 * Date: 08/06/2018
 * Time: 21:53
 */

namespace TenMinutesMail;

use ErrorException;
use Curl\Curl;
use Sunra\PhpSimple\HtmlDomParser as SunraParser;

class TenMinutesMail
{
    /**
     * Get current address
     */
    const ADDRESS_API_URL = 'https://10minutemail.net/address.api.php';

    /**
     * Create new email address
     */
    const NEW_ADDRESS_API_URL = 'https://10minutemail.net/address.api.php';

    /**
     * Read an email
     */
    const READ_MAIL_API_URL = 'https://10minutemail.net/readmail.html?mid=%s';

    /**
     * @var Curl $curl
     */
    public static $curl = NULL;

    /**
     * @var string $cookie_file
     */
    protected static $cookie_file;

    /**
     * @return string
     */
    public static function getCookieFile(): string
    {
        return self::$cookie_file;
    }

    /**
     * @param string $cookie_file
     * @return bool
     */
    public static function setCookieFile(string $cookie_file): bool
    {
        return self::$cookie_file = $cookie_file;
    }

    /**
     * Init curl instance
     *
     * @param string|NULL $cookie_file
     */
    public static function init(string $cookie_file = NULL): void
    {
        if ($cookie_file !== NULL) {
            self::setCookieFile($cookie_file);
        }

        if (self::$curl === NULL) {
            try {
                self::$curl = new Curl();

            } catch (ErrorException $exception) {
                die($exception->getMessage());
            }
        }
        self::$curl->setOpt(CURLOPT_COOKIEFILE, self::$cookie_file);
        self::$curl->setOpt(CURLOPT_COOKIEJAR, self::$cookie_file);
    }

    /**
     * @return array
     */
    public static function getMailBody(): array
    {
        $html = self::$curl->response;
        $mailBody = SunraParser::str_get_html($html)->find('.mailinhtml', 0);
        return [
            'html' => $mailBody->outertext(),
            'plain_text' => $mailBody->plaintext
        ];
    }
}
