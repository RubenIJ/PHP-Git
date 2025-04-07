<?php
session_start();
if(isset($_SESSION["username"])) {
    //ingelogd
} else {
    header("Location: login.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
</head>
<body>

<h1>Welkom op de Admin pagina!</h1>

<ul>
    <li><a>Gerecht toevoegen</a></li>
    <li><a>Gerecht aanpassen</a></li>
    <li><a>Gerecht verwijderen</a></li>
    <li><a href="uitloggen.php">Uitloggen</a></li>
</ul>

</body>
</html>