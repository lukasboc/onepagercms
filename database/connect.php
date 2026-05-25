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
        $db->exec(<<<'SQL'
INSERT INTO faq (id, question, answer, category) VALUES
(0, 'How do I use the Captcha?', 'To use the Captcha, you need to set a Google reCAPTCHA API key in the Settings area. You can get your key from <a href="https://developers.google.com/recaptcha/intro">google.com/recaptcha</a>. <strong>Important:</strong> while configuring reCAPTCHA, please select "reCAPTCHA Version 2" and "Checkbox".', 'Settings'),
(1, 'Is OPCMS free?', 'Yes. OPCMS is — and always will be — completely free. In the future, there may be optional premium themes or plugins available for purchase.', 'General'),
(2, 'How do I log into the backend?', 'You can access the backend by visiting:<br><br>http://yourdomain.tld/opcms-login.php<br><br>For example, the login page for the OPCMS demo can be found here:<br><a href="http://demo.onepagercms.de/opcms-login.php">http://demo.onepagercms.de/opcms-login.php</a>', 'General'),
(3, 'Can I support the project?', 'Absolutely. Reporting bugs helps us a lot. We would appreciate it if you created an issue on our GitHub page. You can also use the contact form on <a href="https://onepagercms.de">https://onepagercms.de</a>.', 'General'),
(4, 'OPCMS is really cool — do you accept donations?', 'If you would like to support the project financially, feel free to use the "Support OPCMS" button at the bottom of every backend page.', 'General'),
(5, 'How do I change colors?', 'You can change colors on the Design page, where you will find several color options that can be adjusted directly. Please note that you should only change one color at a time, because the page reloads after each update and any unsaved changes will be lost.<br><br>You can also customize colors through the Extra CSS field on the Design page. CSS entered there has a higher priority than the individual color inputs.', 'Design'),
(6, 'How do I change the order of my sections?', 'You can change the order of your sections on the Sections page. Simply adjust the values in the Position column and click "Save Positions".', 'Sections'),
(7, 'My sections are not displayed in the correct order. What should I do?', 'Please make sure there are no duplicate values in the Position column on the Sections page.', 'Sections'),
(8, 'How do I embed buttons or videos?', 'You can embed any HTML code through the editor, for example in Standard Sections. Click the first icon in the top row of the editor ("View HTML") and paste your code there. For more information, visit the <a href="https://alex-d.github.io/Trumbowyg/documentation/">Trumbowyg documentation</a>.', 'Sections'),
(9, 'Can I add custom CSS to specific pages?', 'Yes. Every section on your page has a unique ID (based on its headline) and can be targeted through the Extra CSS field on the Design page.', 'Design'),
(10, 'How can I upload and embed images?', 'A media gallery for uploading and managing images is planned, but has not yet been implemented.<br><br>For now, you can manually upload images via FTP (for example into the img directory) and embed them using their direct URL.<br><br>If you upload a file to the img directory of your webspace, the URL would look like this:<br>http://yourdomain.tld/img/yourfile.aaa', 'General'),
(11, 'Will there be more section types in the future?', 'We are planning to add many more section types, such as Portfolio, Timeline, About Us, and others. If you have specific ideas or suggestions, feel free to contact us!', 'General')
SQL
        );
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
