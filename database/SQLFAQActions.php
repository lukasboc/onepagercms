<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 21.07.19
 * Time: 11:58
 */

include "../database/QuestionAndAnswer.php";

class SQLFAQActions
{
    public function getAllQuestionsAndAnswers(): ?array
    {
        include '../database/connect.php';

        try {
            $pairArray = Array();
            $pairs = $db->prepare('select * from faq ORDER BY category');
            $pairs->execute();
            $all = $pairs->fetchAll();
            foreach ($all as $iValue) {
                $qAndA = new QuestionAndAnswer($iValue[0], $iValue[1], $iValue[2], $iValue[3]);
                $pairArray[] = $qAndA;
            }
            return $pairArray;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getAllCategories(): ?array
    {
        include '../database/connect.php';

        try {
            $categoryArray = Array();
            $cats = $db->prepare('select category from faq ORDER BY category');
            $cats->execute();
            $all = $cats->fetchAll();
            foreach ($all as $i => $iValue) {
                $categoryArray[] = $all[$i]['category'];
            }
            return $categoryArray;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

}