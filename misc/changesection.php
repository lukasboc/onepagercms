<?php
if($_POST['type'] == 'standard'){
    $id = $_POST['id'];
    header('Location: ../misc/standard.php?id=' . $id . '&action=edit');
}