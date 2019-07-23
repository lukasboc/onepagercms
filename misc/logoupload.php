<?php
$upload_folder = '../img/logo/'; //Das Upload-Verzeichnis
$filename = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

//Überprüfung der Dateiendung
$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
if (!in_array($extension, $allowed_extensions)) {
    header('Location: ../core/error.php?reason=wrongimageformat');
    die();
}

//Überprüfung der Dateigröße
$max_size = 5000 * 1024; //5MB
if ($_FILES['cinemaimage-upload']['size'] > $max_size) {
    header('Location: ../core/error.php?reason=logoimagetoobig');
    die();
}

//Überprüfung dass das Bild keine Fehler enthält
if (function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
    $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $detected_type = exif_imagetype($_FILES['logo']['tmp_name']);
    if (!in_array($detected_type, $allowed_types)) {
        header('Location: ../core/error.php?reason=wrongimageformat');
        die();
    }
}

//Pfad zum Upload
$new_path = $upload_folder . $filename . '.' . $extension;

//Neuer Dateiname falls die Datei bereits existiert
if (file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
    $id = 1;
    do {
        $new_path = $upload_folder . $filename . '_' . $id . '.' . $extension;
        $id++;
    } while (file_exists($new_path));
}

//Alles okay, verschiebe Datei an neuen Pfad
move_uploaded_file($_FILES['logo']['tmp_name'], $new_path);
header("Location: ../core/settings.php?logo=" . $new_path);
