<?php
    session_start();

    if (isset($_POST['login'])) {
        if ($_POST['username'] == "admin" && $_POST['password'] == "goat") {
            $_SESSION['username'] = 'admin';
            header('Location: admin.php');
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inloggen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Login form</h1>
<form action="login.php" method="post">
    <div class="container">
        <div>
            <label for="uname">Username
                <input type="text" placeholder="Enter Username" name="username" required autocomplete="off">
            </label>
        </div>
        <div>
            <label for="uname">Password
                <input type="password" placeholder="Enter password" name="password" required autocomplete="off">
            </label>
        </div>
        <div>
            <input type="submit" name="login" value="Login">
        </div>
    </div>
</form>
</body>
</html>
