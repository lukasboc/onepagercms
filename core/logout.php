<?php
session_start();
session_destroy();

echo '
<!DOCTYPE html>
<html>';
require 'inc/head.php';
echo '
<body class="min-h-screen bg-base-200">
<div class="container">
    <div class="alert alert-success mt-3 items-start">
        <i class="fas fa-check-circle mt-1"></i>
        <div>
            <h1>You are now logged out</h1>
            You will be redirected to the index of your website.
            <div class="divider my-2"></div>
            <p>Redirecting..</p>
        </div>
    </div>
</div>
</body>
</html>
';
header('refresh:2;url=../index.php');
