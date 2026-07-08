<?php
include '../database/SQLSettingActions.php';
$settingactions = new SQLSettingActions();

include '../database/SQLUserActions.php';
$useractions = new SQLUserActions();
if (count($useractions->getAllUsernames()) !== 0) {
    header('Location: ../pages/index.php');
    die();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Installation - OP-CMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <script>(function(){var t=localStorage.getItem("opcms-admin-theme")||(window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light");document.documentElement.setAttribute("data-theme",t);})();</script>
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-base-200">

<div class="card card-border bg-base-100 shadow-lg w-full max-w-sm m-4">
    <div class="card-body">
        <div class="text-center mb-6">
            <img class="opcms-logo inline-block" src="../img/logo/logo_black.png"
                 style="max-width:300px; max-height: 200px; <?php echo $settingactions->getSettingValue('logo_css') ?>">
        </div>
        <h3 class="text-xl font-semibold mb-2">First Installation</h3>
        <p class="text-sm text-base-content/60 text-center mb-4">Please enter your prefered username and your email adress. A
            generated password will be sent to the entered email adress.
        </p>
        <form method="post" action="../misc/firstuserdata.php">
            <div class="mb-4">
                <input type="text" class="input w-full" name="username" placeholder="Username"/>
            </div>
            <div class="mb-4">
                <input type="email" class="input w-full" name="email" placeholder="Email"/>
            </div>
            <div class="mb-4">
                <input type="submit" class="btn btn-primary btn-block" name="installation" value="Let's go!"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
