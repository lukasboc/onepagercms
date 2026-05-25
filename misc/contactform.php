<?php
$input_data = $_POST;

if (array_key_exists('g-recaptcha-response', $input_data)) {
    if ($input_data['g-recaptcha-response'] == null) header('Location: ../misc/error.php?reason=emailnotsent');
    die();
}


include "../database/SQLContactActions.php";
$contctactions = new SQLContactActions();


$receiver = $contctactions->getReceiverMail($input_data['contactId']);

// Betreff
$subject = 'OPCMS - New Contact Form Message';

// Nachricht
$tabledata = "";
foreach ($input_data as $key => $value) {
    $tabledata .= "<tr>"
        . "<th style='text-align: left'>" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "</th>"
        . "<td>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</td>"
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
if (!empty($input_data['email'])) {
    $header[] = 'Reply-To: ' . $input_data['email'];
}

// verschicke die E-Mail
(mail($receiver, $subject, $message, implode("\r\n", $header))) ? header('Location: ../misc/success.php?reason=emailsent') : header('Location: ../misc/error.php?reason=emailnotsent');
