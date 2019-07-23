<!DOCTYPE html>
<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="css/login.css" rel="stylesheet">

</head>
<body>

<div class="container login-container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col login-form-1">
            <h3>Sign in</h3>
            <form method="post" action="../misc/login.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required />
                </div>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" name="login" value="Login" />
                </div>
            </form>
        </div>
        <div class="col-3"></div>
    </div>
</div>

<!--<section>
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
</section>-->

</body>
</html>