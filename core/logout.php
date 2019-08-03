<?php
session_start();
session_destroy();

echo "
<!DOCTYPE html>
<html>";
require "inc/head.php";
echo "
<body>
<div class=\"container mt-3\">
    <h1>You are now logged out</h1>
    <div class=\"alert alert-success\">
        You will be redirected to the index of your website.
    </div>
</div>
</body>
</html>
";
header("refresh:555;url=../index.php");