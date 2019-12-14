<?php
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;

if ($username == null || $email == null) {
    header('Location: ../misc/error.php?reason=criticalinput');
    die();
}

include_once "../database/SQLUserActions.php";
$userActions = new SQLUserActions();

$generatedPass = substr((md5(microtime())), rand(0, 26), 8);
if ($userActions->register($username, $generatedPass, $email)) {
    $receiver = $email;
    $subject = 'OPCMS - Your Login Credentials';
    $message = '
<html>
<head>
  <title>OPCMS - Your Login Credentials</title>
</head>
<body>
  <p>Hello ' . $username . ',</p>
  <p>you have successfully set up your website with OPCMS. Your login credentials:</p>
  <table>
    <tr>
    <th style="text-align:left">Login Page</th><td>' . $_SERVER["SERVER_NAME"] . '/opcms-login.php</td>
    </tr>
    <tr>
      <th style="text-align:left">Username</th><td>' . $username . '</td>
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
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';
    $servermessage = 'There is a new installation of OPCMS. Maybe you want to checkout ' . $_SERVER["SERVER_NAME"] . '.';

    mail($receiver, $subject, $message, implode("\r\n", $header));
    mail('newinstallation@onepagercms.de', 'OPCMS - a new instalaltion', $servermessage, $header);
    header("Location: ../opcms-login.php?");
} else header('Location: ../misc/error.php?reason=dberror');
