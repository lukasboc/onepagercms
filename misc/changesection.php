<?php
if($_GET['type'] == 'standard'){
    $id = $_GET['id'];
    if($_GET['action'] == 'Edit'){
    header('Location: ../misc/standard.php?id=' . $id . '&action=Edit');
    } elseif ($_GET['action'] == 'Delete'){
        header('Location: ../misc/standard.php?id=' . $id . '&action=Delete');
    }
}

elseif ($_GET['type'] == 'icons'){
    $id = $_GET['id'];
    if($_GET['action'] == 'Edit'){
        header('Location: ../misc/icons.php?id=' . $id . '&action=Edit');
    } elseif ($_GET['action'] == 'Delete'){
        header('Location: ../misc/icons.php?id=' . $id . '&action=Delete');
    }
}

    elseif ($_GET['type'] == 'contact') {
        $id = $_GET['id'];
        if ($_GET['action'] == 'Edit') {
            header('Location: ../misc/contact.php?id=' . $id . '&action=Edit');
        } elseif ($_GET['action'] == 'Delete') {
            header('Location: ../misc/contact.php?id=' . $id . '&action=Delete');
        }

}