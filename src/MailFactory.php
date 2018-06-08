<?php
/**
 * Created by PhpStorm.
 * User: thanhtunguet
 * Date: 08/06/2018
 * Time: 23:00
 */

namespace TenMinutesMail;

class MailFactory
{
    /**
     * Create new email address
     *
     * @return Address
     */
    public static function create(): Address
    {
        $address = new Address();
        TenMinutesMail::$curl->get(TenMinutesMail::NEW_ADDRESS_API_URL);
        $response = TenMinutesMail::$curl->response;
        $mailData = json_decode($response);

        $mails = [];
        foreach ($mailData->mail_list as $mail) {
            $mails[] = new Mail($mail);
        }

        return $address
            ->setUser($mailData->mail_get_user)
            ->setHost($mailData->mail_get_host)
            ->setRecoveryKey($mailData->mail_get_key)
            ->setPermanentKey($mailData->permalink->key)
            ->setPermanentUrl($mailData->permalink->url)
            ->setCreatedTime($mailData->mail_get_time)
            ->setLeftTime($mailData->mail_left_time)
            ->setMails($mailData->mail_list);
    }

    /**
     * @param string $json
     * @return Address
     */
    public static function fromJSON(string $json): Address
    {
        $mailData = json_decode($json);
        $address = new Address();
        $address->setPermanentKey($mailData->permanent_key)
            ->setPermanentUrl($mailData->permanent_url)
            ->setUser($mailData->user)
            ->setHost($mailData->host)
            ->setRecoveryKey($mailData->key)
            ->setCreatedTime($mailData->created_at);
        return $address;
    }
}
