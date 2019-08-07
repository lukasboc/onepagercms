<?php

include '../database/SQLUserActions.php';
$useractions = new SQLUserActions();

$username = $_POST["username"] ?? null;
$oldPassword = $_POST['oldpassword'] ?? null;
$newPasswordOne = $_POST['newpassword1'] ?? null;
$newPasswordTwo = $_POST['newpassword2'] ?? null;

if ($oldPassword == null || $newPasswordOne == null || $newPasswordTwo == null || $username == null) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($newPasswordOne != $newPasswordTwo) {
    header('Location: ../core/error.php?reason=passwordsdontmatch');
    die();
}

if (!$useractions->login($username, $oldPassword)) {
    header('Location: ../core/error.php?reason=wrongpassword');
    die();
}

if (strlen($newPasswordTwo) < 8) {
    header('Location: ../core/error.php?reason=weakpassword');
    die();
}

if (!preg_match("#[0-9]+#", $newPasswordTwo)) {
    header('Location: ../core/error.php?reason=weakpassword');
    die();
}

if (!preg_match("#[a-zA-Z]+#", $newPasswordTwo)) {
    header('Location: ../core/error.php?reason=weakpassword');
    die();
}

if ($useractions->changePassword($username, $newPasswordOne)) {
    $receiver = $useractions->getEmailByUsername($username); // beachte das Komma
    $subject = 'OPCMS - Changed Password';
    $message = '
<html>
<head>
  <title>OPCMS - New Login Credentials</title>
</head>
<body>
  <p>Hello ' . $username . ',</p>
  <p>this E-Mail was sent to you, because you changed your password. If you didn\'t cause this, your account may have been captured and you should reset your password.</p>
</body>
</html>';
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';
    mail($receiver, $subject, $message, implode("\r\n", $header));
    header('Location: ../core/success.php?reason=passwordchanged');
} else header('Location: ../core/error.php?reason=dberror');
