<?php
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;

if (strlen($username) == 0 || $username == null) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if (strlen($email) == 0 || $email == null) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

include_once "../database/SQLUserActions.php";
$userActions = new SQLUserActions();

if ($userActions->checkForSpecificUsername($username)[0] != 0) {
    header('Location: ../core/error.php?reason=usernameinuse');
    die();
}

if ($userActions->checkForSpecificEmail($email)[0] != 0) {
    header('Location: ../core/error.php?reason=emailinuse');
    die();
}

$generatedPass = substr((md5(microtime())), rand(0, 26), 8);
if ($userActions->register($username, $generatedPass, $email)) {
// mehrere Empfänger
    $receiver = $email; // beachte das Komma

// Betreff
    $subject = 'OPCMS - New Login Credentials';

// Nachricht
    $message = '
<html>
<head>
  <title>OPCMS - New Login Credentials</title>
</head>
<body>
  <p>Hello ' . $username . ',</p>
  <p>this mail gives you access to a OPCMS-Website. Data:</p>
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

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';

// zusätzliche Header

// verschicke die E-Mail
    mail($receiver, $subject, $message, implode("\r\n", $header));
    header('Location: ../core/account.php');
} else header('Location: ../core/error.php?reason=dberror');
