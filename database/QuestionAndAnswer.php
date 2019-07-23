<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 21.07.19
 * Time: 11:59
 */

class QuestionAndAnswer
{
    private $id;
    private $question;
    private $answer;
    private $category;

    /**
     * QuestionAndAnswer constructor.
     * @param $id
     * @param $question
     * @param $answer
     */
    public function __construct($id, $question, $answer, $category)
    {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
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
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

}