<?php
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;

if ($username == null || $email == null) {
    header('Location: ../misc/error.php?reason=criticalinput');
    die();
}

include_once "../database/SQLUserActions.php";
$userActions = new SQLUserActions();

// First-run install only: once any account exists this endpoint must be inert,
// otherwise anyone could keep minting admin accounts on a live site.
$existingUsers = $userActions->getAllUsernames();
if (is_array($existingUsers) && count($existingUsers) > 0) {
    header('Location: ../core/opcms-login.php');
    die();
}

$safeUsername = htmlspecialchars((string)$username, ENT_QUOTES, 'UTF-8');
$safeHost = htmlspecialchars($_SERVER['SERVER_NAME'] ?? '', ENT_QUOTES, 'UTF-8');
$generatedPass = substr(bin2hex(random_bytes(8)), 0, 8);
if ($userActions->register($username, $generatedPass, $email)) {
    $receiver = $email;
    $subject = 'OPCMS - Your Login Credentials';
    $message = '
<html>
<head>
  <title>OPCMS - Your Login Credentials</title>
</head>
<body>
  <p>Hello ' . $safeUsername . ',</p>
  <p>you have successfully set up your website with OPCMS. Your login credentials:</p>
  <table>
    <tr>
    <th style="text-align:left">Login Page</th><td>' . $safeHost . '/opcms-login.php</td>
    </tr>
    <tr>
      <th style="text-align:left">Username</th><td>' . $safeUsername . '</td>
    </tr>
    <tr>
      <th style="text-align:left">Password</th><td>' . $generatedPass . '</td>
    </tr>
  </table>
  <p>The password was generated and nobody knows it except for you. Nonetheless, we recommend to change it.</p>
  <p>Have fun!</p>
</body>
</html>
';
    $header = [];
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';
    $header[] = 'From: noreply@' . $_SERVER['SERVER_NAME'];
    $servermessage = 'There is a new installation of OPCMS. Maybe you want to checkout ' . $_SERVER["SERVER_NAME"] . '.';

    mail($receiver, $subject, $message, implode("\r\n", $header));
    mail('newinstallation@onepagercms.de', 'OPCMS - a new installation', $servermessage, implode("\r\n", $header));
    header("Location: ../opcms-login.php");
    die();
} else {
    header('Location: ../misc/error.php?reason=dberror');
    die();
}
