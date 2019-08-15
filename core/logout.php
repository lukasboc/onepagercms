<?php
session_start();
session_destroy();

echo "
<!DOCTYPE html>
<html>";
require "inc/head.php";
echo "
<body>
<div class=\"container\">
    <div class=\"alert alert-success mt-3\">
        <h1 class=\"alert-heading\">You are now logged out</h1>
        You will be redirected to the index of your website.
        <hr>
        <p>Redirecting..</p>
    </div>
</div>
</body>
</html>
";
header("refresh:3;url=../index.php");