<?php
$upload_folder = '../img/backgrounds/';
$filename = pathinfo($_FILES['background-image']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['background-image']['name'], PATHINFO_EXTENSION));
$agreed = 'nope';

$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
if (!in_array($extension, $allowed_extensions)) {
    header('Location: ../core/error.php?reason=wrongimageformat');
    die();
}

$max_size = 10000 * 1024; //500 KB
if ($_FILES['background-image']['size'] > $max_size) {
    header('Location: ../core/error.php?reason=filetoolarge');
    die();
}

if (function_exists('exif_imagetype')) {
    $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $detected_type = exif_imagetype($_FILES['background-image']['tmp_name']);
    if (!in_array($detected_type, $allowed_types)) {
        header('Location: ../core/error.php?reason=wrongimageformat');
        die();
    }
}

$new_path = $upload_folder . $filename . '.' . $extension;

if (file_exists($new_path)) {
    $id = 1;
    do {
        $new_path = $upload_folder . $filename . '_' . $id . '.' . $extension;
        $id++;
    } while (file_exists($new_path));
}

move_uploaded_file($_FILES['background-image']['tmp_name'], $new_path);
header("Location: " . $_SERVER['HTTP_REFERER'] . "&background-image=" . $new_path);