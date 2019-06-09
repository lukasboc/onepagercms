<?php
session_start();
session_destroy();

echo "<h1>Logout successful</h1> <p>Redirecting to frontend..</p>";
header("refresh:2;url=../pages/index.php");