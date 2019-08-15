<?php
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;

if (strlen($username) == 0 || $username == null && strlen($email) == 0 || $email == null) {
    header('Location: ../misc/error.php?reason=criticalinput');
    die();
}

include_once "../database/SQLUserActions.php";
$userActions = new SQLUserActions();

if (!$userActions->checkForSpecificUsername($username) && !$userActions->checkForSpecificEmail($email)) {
    header('Location: ../misc/error.php?reason=usernotfound');
    die();
}

$email = ($email == null) ? $userActions->getEmailByUsername($username) : $email;
$username = ($username == null) ? $userActions->getUsernameByEmail($email) : $username;

$generatedPass = substr((md5(microtime())), rand(0, 26), 8);
if ($userActions->changePassword($username, $generatedPass, $email)) {
    $receiver = $email;
    $subject = 'OPCMS - Your New Password';
    $message = '
<html>
<head>
  <title>OPCMS - New Login Credentials</title>
</head>
<body>
  <p>Hello ' . $username . ',</p>
  <p>you resetted you password. This mail gives you a temporary password. Your new login credentials:</p>
  <table>
    <tr>
    <th>Host</th><td>' . $_SERVER["SERVER_NAME"] . '</td>
    </tr>
    <tr>
      <th>Username</th><td>' . $username . '</td>
    </tr>
    <tr>
      <th>Password</th><td>' . $generatedPass . '</td>
    </tr>
  </table>
  <p>The password was generated and nobody knows it except for you. Nonetheless, we recommend to change it.</p>
  <p>Have fun!</p>
</body>
</html>
';
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';

    mail($receiver, $subject, $message, implode("\r\n", $header));
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else header('Location: ../misc/error.php?reason=dberror');
