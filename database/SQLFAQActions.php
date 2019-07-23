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
    public function getAllQuestionsAndAnswers()
    {
        include '../database/connect.php';

        try {
            $pairArray = Array();
            $pairs = $db->prepare('select * from faq ORDER BY category');
            $pairs->execute();
            $all = $pairs->fetchAll();
            for ($i = 0; $i < sizeof($all); $i++) {
                $qAndA = new QuestionAndAnswer($all[$i][0], $all[$i][1], $all[$i][2], $all[$i][3]);
                array_push($pairArray, $qAndA);
            }
            return $pairArray;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getAllCategories()
    {
        include '../database/connect.php';

        try {
            $categoryArray = Array();
            $cats = $db->prepare('select category from faq ORDER BY category');
            $cats->execute();
            $all = $cats->fetchAll();
            for ($i = 0; $i < sizeof($all); $i++) {
                array_push($categoryArray, $all[$i]['category']);
            }
            return $categoryArray;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

}