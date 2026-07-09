<?php
include '../database/SQLSettingActions.php';
$settingactions = new SQLSettingActions();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign in - OP-CMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <script>(function(){var t=localStorage.getItem("opcms-admin-theme")||(window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light");document.documentElement.setAttribute("data-theme",t);})();</script>
    <link href="../css/admin.css" rel="stylesheet">
    <?php if (file_exists('../img/favicon/opcms48x48.ico')) echo "<link rel=\"shortcut icon\" href=\"../img/favicon/opcms48x48.ico\">"; ?>
</head>
<body class="min-h-screen flex items-center justify-center bg-base-200">

<div class="card card-border bg-base-100 shadow-lg w-full max-w-sm m-4">
    <div class="card-body">
        <div class="text-center mb-6">
            <img class="opcms-logo inline-block" src="../img/logo/logo_black.png"
                 style="max-width:300px; max-height: 200px; <?php echo $settingactions->getSettingValue('logo_css') ?>">
        </div>
        <h3 class="text-xl font-semibold mb-4">Sign in</h3>
        <form method="post" action="../misc/login.php">
            <div class="mb-4">
                <input type="text" class="input w-full" name="username" placeholder="Username" required />
            </div>
            <div class="mb-4">
                <input type="password" class="input w-full" name="password" placeholder="Password" required />
            </div>
            <div class="mb-4">
                <input type="submit" class="btn btn-primary btn-block" name="login" value="Sign in"/>
            </div>
            <div class="text-center">
                <a class="link text-sm" href="../misc/resetpassword.php">Forgot your password?</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
