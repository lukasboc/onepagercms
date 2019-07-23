<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 27.05.19
 * Time: 17:46
 */

$datenbank = "../database/SQLiteDatabase.db";
try {
// Datenbank-Datei erstellen
    if (!file_exists($datenbank)) {
        $db = new PDO('sqlite:' . $datenbank);
        $db->exec("CREATE TABLE users
			(
			uid int PRIMARY KEY,
			username VARCHAR(50) NOT NULL,
			password VARCHAR(50) NOT NULL
			)");
        $db->exec("CREATE TABLE sections
			(
			sid int PRIMARY KEY,
			type VARCHAR(255) NOT NULL,
			specialid int NOT NULL
			)");
        $db->exec("CREATE TABLE icons
			(
			specialid int PRIMARY KEY,
			position int NOT NULL,
			sectiontype VARCHAR(255) NOT NULL,
			title VARCHAR NOT NULL,
			mutedtitle VARCHAR NOT NULL,
			date TEXT NOT NULL,
			
			iconone VARCHAR (255) NOT NULL,
			icontwo VARCHAR (255) NOT NULL,
			iconthree VARCHAR (255),
			iconfour VARCHAR (255),
			iconfive VARCHAR (255),
			iconsix VARCHAR (255),
			iconseven VARCHAR (255),
			iconeight VARCHAR (255),
			
			iconheadlineone VARCHAR (255) NOT NULL,
			iconheadlinetwo VARCHAR (255) NOT NULL,
			iconheadlinethree VARCHAR (255),
			iconheadlinefour VARCHAR (255),
			iconheadlinefive VARCHAR (255),
			iconheadlinesix VARCHAR (255),
			iconheadlineseven VARCHAR (255),
			iconheadlineeight VARCHAR (255),
			
			icontextone TEXT NOT NULL,
			icontexttwo TEXT NOT NULL,
			icontextthree TEXT,
			icontextfour TEXT,
			icontextfive TEXT,
			icontextsix TEXT,
			icontextseven TEXT,
			icontexteight TEXT
			)");
        $db->exec("CREATE TABLE standard
			(
			specialid int PRIMARY KEY,
			position int NOT NULL,
			sectiontype VARCHAR(255) NOT NULL,
			title VARCHAR(255) NOT NULL,
			mutedtitle VARCHAR(255) NOT NULL,
			text TEXT NOT NULL,
			date TEXT NOT NULL
			
			)");
        $db->exec("CREATE TABLE contact
			(
			specialid int PRIMARY KEY,
			position int NOT NULL,
			sectiontype VARCHAR(255) NOT NULL,
			title VARCHAR NOT NULL,
			mutedtitle VARCHAR(255) NOT NULL,
			text TEXT NOT NULL,
			date TEXT NOT NULL,
			name BOOLEAN,
			email BOOLEAN,
			message BOOLEAN,
			captcha BOOLEAN
			)");
    } else {
        // Verbindung
        $db = new PDO('sqlite:' . $datenbank);
    }

// Schreibrechte überprüfen
    if (!is_writable($datenbank)) {
        // Schreibrechte setzen
        chmod($datenbank, 0777);
    }
} catch (PDOException $PDOException) {
    echo 'Fehler: ' . $PDOException->getMessage();
}
?>
