<?php
include "../database/SQLSpamProtectionActions.php";
include "../database/SQLContactActions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index.php');
    die();
}

$spamprotection = new SQLSpamProtectionActions();
$spamprotection->ensureContactMessages();

// Bots that fill every field land here — pretend success so they don't adapt
if ($spamprotection->isHoneypotFilled($_POST)) {
    header('Location: ../misc/success.php?reason=emailsent');
    die();
}

$contactId = (int)($_POST['contactId'] ?? 0);

switch ($spamprotection->verifyFormToken($_POST['formToken'] ?? '', $contactId)) {
    case 'invalid':
    case 'toofast':
        // missing/forged token or sub-3s submit = bot posting directly — silent drop
        header('Location: ../misc/success.php?reason=emailsent');
        die();
    case 'expired':
        header('Location: ../misc/error.php?reason=contactexpired');
        die();
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if ($spamprotection->isRateLimited($ip)) {
    header('Location: ../misc/error.php?reason=contactratelimited');
    die();
}
$spamprotection->recordSubmission($ip);

$contactactions = new SQLContactActions();
$config = $contactactions->getContactConfig($contactId);
$receiver = ($config !== null) ? $config['receiverMail'] : null;

$name = trim((string)($_POST['Name'] ?? ''));
$mail = trim((string)($_POST['Mail'] ?? ''));
$messagetext = trim((string)($_POST['Message'] ?? ''));

$valid = $config !== null
    && filter_var((string)$receiver, FILTER_VALIDATE_EMAIL) !== false
    && (!$config['name'] || $name !== '')
    && (!$config['email'] || $mail !== '')
    && (!$config['message'] || $messagetext !== '')
    && ($mail === '' || filter_var($mail, FILTER_VALIDATE_EMAIL) !== false)
    && strlen($name) <= 200 && strlen($mail) <= 254 && strlen($messagetext) <= 5000;

if (!$valid) {
    header('Location: ../misc/error.php?reason=contactinvalid');
    die();
}

// Betreff
$subject = 'OPCMS - New Contact Form Message';

// Nachricht
$fields = array('Name' => $name, 'Mail' => $mail, 'Message' => $messagetext);
$tabledata = "";
foreach ($fields as $key => $value) {
    if ($value === '') {
        continue;
    }
    $tabledata .= "<tr>"
        . "<th style='text-align: left'>" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "</th>"
        . "<td>" . nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) . "</td>"
        . "</tr>";
}

$message = '
<html>
<head>
  <title>OPCMS - New Contact Form Message</title>
</head>
<body>
  <p>Hello,</p>
  <p>a visitor filled out the contact form on ' . $_SERVER["SERVER_NAME"] . '!</p>
  <table>' . $tabledata . '
  </table>
</body>
</html>
';

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
$header[] = 'MIME-Version: 1.0';
$header[] = 'Content-type: text/html; charset=utf-8';
$header[] = 'From: noreply@' . $_SERVER['SERVER_NAME'];
if ($mail !== '') {
    $header[] = 'Reply-To: ' . str_replace(array("\r", "\n"), '', $mail);
}

// verschicke die E-Mail
(mail($receiver, $subject, $message, implode("\r\n", $header))) ? header('Location: ../misc/success.php?reason=emailsent') : header('Location: ../misc/error.php?reason=emailnotsent');
