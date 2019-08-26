<?php

include '../database/SQLUserActions.php';
$useractions = new SQLUserActions();

$username = $_POST["username"] ?? null;
$password = $_POST['password'] ?? null;
$oldEmail = $_POST['oldemail'] ?? null;
$newEmailOne = $_POST['newemailOne'] ?? null;
$newEmailTwo = $_POST['newemailTwo'] ?? null;

if ($oldEmail == null || $newEmailOne == null || $newEmailTwo == null || $username == null || $password == null) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($newEmailOne != $newEmailTwo) {
    header('Location: ../core/error.php?reason=passwordsdontmatch');
    die();
}

if (!$useractions->login($username, $password)) {
    header('Location: ../core/error.php?reason=wrongpassword');
    die();
}

if (!stristr($newEmailOne, "@") || !stristr($newEmailOne, ".")) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($useractions->changeEmail($username, $password, $newEmailOne)) {
    $receiver = $useractions->getEmailByUsername($username);
    $subject = 'OPCMS - Changed E-Mail Adress';
    $message = '
<html>
<head>
  <title>OPCMS - New Login Credentials</title>
</head>
<body>
  <p>Hello ' . $username . ',</p>
  <p>this E-Mail was sent to you, because you changed you successfully changed your E-Mail adress. This adress is now saved in the database.</p>
</body>
</html>';
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';
    mail($receiver, $subject, $message, implode("\r\n", $header));
    header('Location: ../core/success.php?reason=emailchanged');
} else header('Location: ../core/error.php?reason=dberror');
