<?php
/**
 * Created by PhpStorm.
 * User: thanhtunguet
 * Date: 08/06/2018
 * Time: 21:48
 */

namespace TenMinutesMail;

use Sunra\PhpSimple\HtmlDomParser as SunraParser;

class Mail
{
    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var bool $read
     */
    protected $read;

    /**
     * @var string $date_time
     */
    protected $date_time;

    /**
     * @var string $human_support_date_time
     */
    protected $human_support_date_time;

    /**
     * @var string $from
     */
    protected $from;

    /**
     * Mail's subject
     *
     * @var string $subject
     */
    protected $subject;

    /**
     * Mail's body
     *
     * @var string body
     */
    protected $body;

    /**
     * Mail's body in plain text
     *
     * @var string $plain_text
     */
    protected $plain_text;

    /**
     * @var int $time_ago
     */
    protected $time_ago;

    /**
     * Mail constructor.
     * @param object $mail
     */
    public function __construct(object $mail)
    {
        $this->setSubject($mail->subject)
            ->fromSender($mail->from)
            ->setDateTime($mail->datetime)
            ->setHumanSupportDateTime($mail->datetime2)
            ->setId($mail->mail_id)
            ->setTimeAgo($mail->timeago);
    }

    /**
     * @param int $time_ago
     * @return Mail
     */
    public function setTimeAgo(int $time_ago): Mail
    {
        $this->time_ago = $time_ago;
        return $this;
    }

    /**
     * @param string $human_support_date_time
     * @return Mail
     */
    public function setHumanSupportDateTime(string $human_support_date_time): Mail
    {
        $this->human_support_date_time = $human_support_date_time;
        return $this;
    }

    /**
     * @param string $date_time
     * @return Mail
     */
    public function setDateTime(string $date_time): Mail
    {
        $this->date_time = $date_time;
        return $this;
    }

    /**
     * @param string $from
     * @return Mail
     */
    public function fromSender(string $from): Mail
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param string $subject
     * @return Mail
     */
    public function setSubject(string $subject): Mail
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Mail
     */
    public function setId(string $id): Mail
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $body
     * @return Mail
     */
    public function setBody(string $body): Mail
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $plain_text
     * @return Mail
     */
    public function setPlainText(string $plain_text): Mail
    {
        $this->plain_text = $plain_text;
        return $this;
    }

    /**
     * @param bool $read
     * @return Mail
     */
    public function read(bool $read): Mail
    {
        $this->read = TRUE;
        return $this;
    }

    /**
     * @return string
     */
    public function sender(): string
    {
        return $this->from;
    }
}