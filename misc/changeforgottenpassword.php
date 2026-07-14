<?php
// Public endpoint (no auth): a user who forgot their password requests a reset.
// Hardened: the new password is ALWAYS mailed to the address stored for the
// account, never to an attacker-supplied one, and no existence status is leaked.
$username = trim((string)($_POST['username'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));

if ($username === '' && $email === '') {
    header('Location: ../misc/error.php?reason=criticalinput');
    die();
}

include_once "../database/SQLUserActions.php";
include_once "../database/SQLSpamProtectionActions.php";
$userActions = new SQLUserActions();

// Rate-limit reset attempts per IP to curb spam/DoS and blind enumeration.
$spamprotection = new SQLSpamProtectionActions();
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if ($spamprotection->isRateLimited($ip)) {
    header('Location: ../misc/error.php?reason=contactratelimited');
    die();
}
$spamprotection->recordSubmission($ip);

// Resolve the account from whichever identifier was given. The recipient address
// is taken from the database only — the request's email field is never trusted.
$resolvedUsername = ($username !== '')
    ? $username
    : $userActions->getUsernameByEmail($email);

if ($resolvedUsername === null || $resolvedUsername === '') {
    // Unknown account: respond identically to the success case (no enumeration).
    header('Location: ../misc/success.php?reason=resettedpass');
    die();
}

$recipient = $userActions->getEmailByUsername($resolvedUsername);
if ($recipient === null || $recipient === '') {
    header('Location: ../misc/success.php?reason=resettedpass');
    die();
}

$username = $resolvedUsername;
$generatedPass = substr(bin2hex(random_bytes(8)), 0, 8);
if ($userActions->changePassword($username, $generatedPass)) {
    $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $safeHost = htmlspecialchars($_SERVER['SERVER_NAME'] ?? '', ENT_QUOTES, 'UTF-8');
    $receiver = $recipient;
    $subject = 'OPCMS - Your New Password';
    $message = '
<html>
<head>
  <title>OPCMS - New Login Credentials</title>
</head>
<body>
  <p>Hello ' . $safeUsername . ',</p>
  <p>you resetted you password. This mail gives you a temporary password. Your new login credentials:</p>
  <table>
    <tr>
    <th style="text-align:left">Host</th><td>' . $safeHost . '/opcms-login.php</td>
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
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';
    $header[] = 'From: noreply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost');

    mail($receiver, $subject, $message, implode("\r\n", $header));
    header("Location: ../misc/success.php?reason=resettedpass");
} else header('Location: ../misc/error.php?reason=dberror');
