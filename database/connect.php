<?php

$datenbank = "../database/SQLiteDatabase.db";
try {
    if (!file_exists($datenbank)) {
        $db = new PDO('sqlite:' . $datenbank);
        $db->exec("CREATE TABLE users
            (
            uid int PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(50) NULL
            )");
        $db->exec("CREATE TABLE sections
            (
            id int PRIMARY KEY,
            type VARCHAR(255) NOT NULL,
            specialid int NOT NULL,
            position int NOT NULL DEFAULT 0
            )");
        $db->exec("CREATE TABLE icons
            (
            specialid int PRIMARY KEY,
            position int NOT NULL,
            sectiontype VARCHAR(255) NOT NULL,
            title VARCHAR NOT NULL,
            mutedtitle VARCHAR NOT NULL,
            date TEXT NOT NULL,

            iconone VARCHAR(255) NOT NULL,
            icontwo VARCHAR(255) NOT NULL,
            iconthree VARCHAR(255),
            iconfour VARCHAR(255),
            iconfive VARCHAR(255),
            iconsix VARCHAR(255),
            iconseven VARCHAR(255),
            iconeight VARCHAR(255),

            iconheadlineone VARCHAR(255) NOT NULL,
            iconheadlinetwo VARCHAR(255) NOT NULL,
            iconheadlinethree VARCHAR(255),
            iconheadlinefour VARCHAR(255),
            iconheadlinefive VARCHAR(255),
            iconheadlinesix VARCHAR(255),
            iconheadlineseven VARCHAR(255),
            iconheadlineeight VARCHAR(255),

            icontextone TEXT NOT NULL,
            icontexttwo TEXT NOT NULL,
            icontextthree TEXT,
            icontextfour TEXT,
            icontextfive TEXT,
            icontextsix TEXT,
            icontextseven TEXT,
            icontexteight TEXT,

            background varchar(100) NULL
            )");
        $db->exec("CREATE TABLE standard
            (
            specialid int PRIMARY KEY,
            position int NOT NULL,
            sectiontype VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            mutedtitle VARCHAR(255) NOT NULL,
            text TEXT NOT NULL,
            date TEXT NOT NULL,
            background varchar(100)
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
            captcha BOOLEAN,
            background varchar(100) NULL,
            receiverMail varchar(50) NULL
            )");
        $db->exec("CREATE TABLE header
            (
            specialid int PRIMARY KEY,
            mutedtitle TEXT,
            title TEXT,
            background varchar(100) NULL,
            customrow TEXT NULL
            )");
        $db->exec("CREATE TABLE footer
            (
            fid int PRIMARY KEY,
            custom TEXT,
            facebook_page VARCHAR(50),
            twitter_page VARCHAR(50),
            linkedin_page VARCHAR(50),
            custom_page varchar(100),
            copyright boolean,
            custom_icon VARCHAR(30) NULL
            )");
        $db->exec("CREATE TABLE settings
            (
            id int PRIMARY KEY,
            setting VARCHAR(255),
            value TEXT
            )");
        $db->exec("CREATE TABLE error
            (
            id int PRIMARY KEY,
            reason VARCHAR(255),
            headline VARCHAR(255),
            message TEXT
            )");
        $db->exec("CREATE TABLE success
            (
            id int PRIMARY KEY,
            reason VARCHAR(50),
            headline VARCHAR(100),
            message TEXT
            )");
        $db->exec("CREATE TABLE faq
            (
            id int PRIMARY KEY,
            question text,
            answer text,
            category VARCHAR(255) NULL
            )");
        $db->exec("CREATE TABLE additionalPages
            (
            id int PRIMARY KEY,
            title VARCHAR(40),
            content TEXT,
            showInFooter boolean
            )");
    } else {
        $db = new PDO('sqlite:' . $datenbank);
    }

    if (!is_writable($datenbank)) {
        chmod($datenbank, 0640);
    }
} catch (PDOException $PDOException) {
    echo 'Fehler: ' . $PDOException->getMessage();
}
?>
