<?php
$upload_folder = '../img/favicon/';
$filename = pathinfo($_FILES['180x180png']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['180x180png']['name'], PATHINFO_EXTENSION));

$allowed_extensions = array('png');
if (!in_array($extension, $allowed_extensions)) {
    header('Location: ../core/error.php?reason=wrongimageformat');
    die();
}

$max_size = 5000 * 1024; //5MB
if ($_FILES['cinemaimage-upload']['size'] > $max_size) {
    header('Location: ../core/error.php?reason=logoimagetoobig');
    die();
}

if (function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
    $allowed_types = array(IMAGETYPE_PNG);
    $detected_type = exif_imagetype($_FILES['180x180png']['tmp_name']);
    if (!in_array($detected_type, $allowed_types)) {
        header('Location: ../core/error.php?reason=wrongimageformat');
        die();
    }
}

$new_path = $upload_folder . 'favicon180x180' . '.' . $extension;

move_uploaded_file($_FILES['180x180png']['tmp_name'], $new_path);
header('Location: ../core/success.php?reason=imageuploaded');
