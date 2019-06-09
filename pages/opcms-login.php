<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>
<section>
    <div class="container">
        <form method="post" action="../misc/login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Username</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>

            <input type="submit" class="btn btn-primary" name="login">Sign in</input>
        </form>
    </div>
    <div class="align-center">
    </div>
</section>

</body>
</html>