<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 21:54
 */

include "ISectionActions.php";
include "Standard.php";

class SQLSectionActions implements ISectionActions
{

    public function getAllSections()
    {
        $secation_array = array();

        include '../database/connect.php';

        $prepare = $db->prepare('select * from sections order by position');
        $prepare->execute();
        $r = $prepare->fetchAll();

        for ($i = 0; $i < sizeof($r); $i++) {
            if($r[$i][1] == "standard"){
                $prepstandard = $db->prepare('select * from standard where specialid=:specialid');
                $prepstandard->bindValue(':specialid', $r[$i][2]);
                $prepstandard->execute();
                $selection = $prepstandard->fetchAll();

                $standard = new Standard($selection[$i][0], $selection[$i][1], $selection[$i][2], $selection[$i][3], $selection[$i][4], $selection[$i][5], $selection[$i][6]);
                array_push($secation_array, $standard);
            }

            if($r[$i][1] == "icons"){
                $prepstandard = $db->prepare('select * from icons where specialid=:specialid');
                $prepstandard->bindValue(':specialid', $r[$i][2]);
                $prepstandard->execute();
                $selection = $prepstandard->fetchAll();

                $icons = new Icons($selection[$i][0], $selection[$i][1], $selection[$i][2], $selection[$i][3], $selection[$i][4], $selection[$i][5], $selection[$i][6], $selection[$i][7], $selection[$i][8]);
                array_push($secation_array, $icons);

            }

            if($r[$i][1] == "contact"){
                $prepstandard = $db->prepare('select * from contact where specialid=:specialid');
                $prepstandard->bindValue(':specialid', $r[$i][2]);
                $prepstandard->execute();
                $selection = $prepstandard->fetchAll();

                $contact = new Contact($selection[$i][0], $selection[$i][1], $selection[$i][2], $selection[$i][3], $selection[$i][4], $selection[$i][5], $selection[$i][6], $selection[$i][7], $selection[$i][8]);
                array_push($secation_array, $contact);
            }
        }
        return $secation_array;

    }
}