<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 17:25
 */

class Contact
{
    private $id;
    private $position;
    private $type;
    private $title;
    private $mutedtitle;
    private $text;
    private $date;

    private $name;
    private $email;
    private $message;
    private $captcha;
    private $superid;

    private $background;
    private $receiverMail;

    /**
     * Contact constructor.
     * @param $id
     * @param $position
     * @param $type
     * @param $title
     * @param $mutedtitle
     * @param $date
     * @param $name
     * @param $email
     * @param $phone
     * @param $message
     * @param $captcha
     */
    public function __construct($id, $position, $type, $title, $mutedtitle, $text, $date, $name, $email, $message, $captcha, $superid, $background, $receiverMail)
    {
        $this->id = $id;
        $this->position = $position;
        $this->type = $type;
        $this->title = $title;
        $this->mutedtitle = $mutedtitle;
        $this->text = $text;
        $this->date = $date;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->captcha = $captcha;
        $this->superid = $superid;
        $this->background = $background;
        $this->receiverMail = $receiverMail;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMutedtitle()
    {
        return $this->mutedtitle;
    }

    /**
     * @param mixed $mutedtitle
     */
    public function setMutedtitle($mutedtitle): void
    {
        $this->mutedtitle = $mutedtitle;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * @param mixed $captcha
     */
    public function setCaptcha($captcha): void
    {
        $this->captcha = $captcha;
    }

    /**
     * @return mixed
     */
    public function getSuperid()
    {
        return $this->superid;
    }

    /**
     * @param mixed $superid
     */
    public function setSuperid($superid): void
    {
        $this->superid = $superid;
    }

    /**
     * @return mixed
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background): void
    {
        $this->background = $background;
    }

    /**
     * @return mixed
     */
    public function getReceiverMail()
    {
        return $this->receiverMail;
    }

    /**
     * @param mixed $receiverMail
     */
    public function setReceiverMail($receiverMail): void
    {
        $this->receiverMail = $receiverMail;
    }

}