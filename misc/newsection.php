<?php
$type = $_POST['type'];

if($type == "standard") {
    header('Location: standard.php?action=New');
} elseif ($type == "icons"){
    header('Location: icons.php?action=New');
}
?>