<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 06.06.19
 * Time: 00:26
 */

class Icons
{
    private $id;
    private $position;
    private $type;
    private $title;
    private $mutedtitle;
    private $date;
    private $icons = Array();
    private $iconHeadline = Array();
    private $iconTexts = Array();

    /**
     * Icons constructor.
     * @param $id
     * @param $position
     * @param $type
     * @param $title
     * @param $mutedtitle
     * @param $date
     * @param array $icons
     * @param array $iconHeadline
     */
    public function __construct($id, $position, $type, $title, $mutedtitle, $date, array $icons, array $iconHeadline, array $iconTexts)
    {
        $this->id = $id;
        $this->position = $position;
        $this->type = $type;
        $this->title = $title;
        $this->mutedtitle = $mutedtitle;
        $this->date = $date;
        $this->icons = $icons;
        $this->iconHeadline = $iconHeadline;
        $this->iconTexts = $iconTexts;

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
     * @return array
     */
    public function getIcons(): array
    {
        return $this->icons;
    }

    /**
     * @param array $icons
     */
    public function setIcons(array $icons): void
    {
        $this->icons = $icons;
    }

    /**
     * @return array
     */
    public function getIconHeadline(): array
    {
        return $this->iconHeadline;
    }

    /**
     * @param array $iconHeadline
     */
    public function setIconHeadline(array $iconHeadline): void
    {
        $this->iconHeadline = $iconHeadline;
    }

    /**
     * @return array
     */
    public function getIconTexts(): array
    {
        return $this->iconTexts;
    }

    /**
     * @param array $iconTexts
     */
    public function setIconTexts(array $iconTexts): void
    {
        $this->iconTexts = $iconTexts;
    }


}