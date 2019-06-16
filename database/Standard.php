<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 05.06.19
 * Time: 22:57
 */

class Standard
{
    private $id;
    private $position;
    private $type;
    private $title;
    private $mutedtitle;
    private $text;
    private $date;

    /**
     * Section constructor.
     * @param $id
     * @param $position
     * @param $type
     * @param $title
     * @param $text
     * @param $date
     */
    public function __construct($id, $position, $type, $title, $mutedtitle, $text, $date)
    {
        $this->id = $id;
        $this->position = $position;
        $this->type = $type;
        $this->title = $title;
        $this->mutedtitle = $mutedtitle;
        $this->text = $text;
        $this->date = $date;
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


}