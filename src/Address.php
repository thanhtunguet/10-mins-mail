<?php
/**
 * Created by PhpStorm.
 * User: thanhtunguet
 * Date: 08/06/2018
 * Time: 21:48
 */

namespace TenMinutesMail;

class Address
{
    /**
     * @var array $mails
     */
    protected $mails;

    /**
     * @var string $user
     */
    protected $user;

    /**
     * @var string $host
     */
    protected $host;

    /**
     * @var int $created_at
     */
    protected $created_at;

    /**
     * @var int $left_time
     */
    protected $left_time;

    /**
     * @var string $recovery_key
     */
    protected $recovery_key;

    /**
     * @var string $permanent_url
     */
    protected $permanent_url;

    /**
     * @var string $permanent_key
     */
    protected $permanent_key;

    /**
     * Address constructor.
     *
     * Create new address
     */
    public function __construct()
    {

    }

    /**
     * @param int $left_time
     * @return Address
     */
    public function setLeftTime(int $left_time): Address
    {
        $this->left_time = $left_time;
        return $this;
    }

    /**
     * @param string $host
     * @return Address
     */
    public function setHost(string $host): Address
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param string $user
     * @return Address
     */
    public function setUser(string $user): Address
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param int $created_at
     * @return Address
     */
    public function setCreatedTime(int $created_at): Address
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @param string $recovery_key
     * @return Address
     */
    public function setRecoveryKey(string $recovery_key): Address
    {
        $this->recovery_key = $recovery_key;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getAddress();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return "{$this->user}@{$this->host}";
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        $current = time();
        return ($current - $this->created_at <= $this->left_time);
    }

    /**
     * @return array
     */
    public function getMails(): array
    {
        return $this->mails;
    }

    /**
     * @param array $mails
     * @return Address
     */
    public function setMails(array $mails): Address
    {
        $this->mails = [];
        foreach ($mails as $mail) {
            $this->mails[] = new Mail($mail);
        }
        return $this;
    }

    /**
     * @param string $file
     */
    public function save(string $file): void
    {
        $fp = fopen($file, 'w');
        $data = json_encode([
            'address' => $this->getAddress(),
            'user' => $this->user,
            'host' => $this->host,
            'key' => $this->recovery_key,
            'created_at' => $this->created_at,
            'permanent_key' => $this->permanent_key,
            'permanent_url' => $this->permanent_url,
        ]);
        fwrite($fp, $data);
        fclose($fp);
    }

    /**
     * @return Address
     */
    public function update(): Address
    {
        TenMinutesMail::$curl->get(TenMinutesMail::ADDRESS_API_URL);
        $mailData = json_decode(TenMinutesMail::$curl->response);
        $this->setMails($mailData->mail_list);
        $this->setPermanentKey($mailData->permalink->key);
        $this->setPermanentUrl($mailData->permalink->url);
    }

    /**
     * @param string $permanent_key
     * @return Address
     */
    public function setPermanentKey(string $permanent_key): Address
    {
        $this->permanent_key = $permanent_key;
        return $this;
    }

    /**
     * @param string $permanent_url
     * @return Address
     */
    public function setPermanentUrl(string $permanent_url): Address
    {
        $this->permanent_url = $permanent_url;
        return $this;
    }

    /**
     * @param int $index
     * @return Mail
     */
    public function read(int $index): Mail
    {
        $mail = $this->getMail($index);
        $url = $this->getMailUrl($mail->getId());
        TenMinutesMail::$curl->get($url);
        $mailBody = TenMinutesMail::getMailBody();
        return $mail->setBody($mailBody['html'])
            ->setPlainText($mailBody['plain_text']);
    }

    /**
     * @param int $index
     * @return Mail
     */
    public function getMail(int $index): Mail
    {
        return $this->mails[$index];
    }

    /**
     * @param string $id
     * @return string
     */
    public function getMailUrl(string $id)
    {
        return sprintf(TenMinutesMail::READ_MAIL_API_URL, $id);
    }

    /**
     * Recover address
     */
    public function recover(): void
    {
        TenMinutesMail::$curl->post($this->permanent_url, [
            'key' => $this->permanent_key
        ]);
        $this->update();
    }
}