<?php
session_start();

// DATABASECONNECTIE
$host = "db";
$dbname = "Menukaart";
$username = "root";
$password = "rootpassword";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
}

// LOGIN AFHANDELING
if (isset($_POST['login'])) {
    $gebruikersnaam = $_POST['username'];
    $wachtwoord = $_POST['password'];

    // Haal de gebruiker op uit de database
    $stmt = $conn->prepare("SELECT * FROM users WHERE gebruikersnaam = :gebruikersnaam");
    $stmt->bindParam(':gebruikersnaam', $gebruikersnaam);
    $stmt->execute();
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of de gebruiker bestaat en het wachtwoord klopt
    if ($gebruiker) {
        // Als het wachtwoord niet gehashed is, eerst updaten
        if (!password_get_info($gebruiker['wachtwoord'])['algo']) {
            $hashedPassword = password_hash($gebruiker['wachtwoord'], PASSWORD_DEFAULT);

            // Update wachtwoord in de database naar gehashte versie
            $updateStmt = $conn->prepare("UPDATE users SET wachtwoord = :wachtwoord WHERE gebruikersnaam = :gebruikersnaam");
            $updateStmt->bindParam(':wachtwoord', $hashedPassword);
            $updateStmt->bindParam(':gebruikersnaam', $gebruikersnaam);
            $updateStmt->execute();

            // Verifieer met de gehashte versie
            $gebruiker['wachtwoord'] = $hashedPassword;
        }

        // Verifieer het wachtwoord
        if (password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $_SESSION['username'] = $gebruikersnaam;
            header("Location: admin.php");  // Redirect naar admin.php
            exit();
        } else {
            echo "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        echo "Gebruiker niet gevonden.";
    }
}
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container-header">
        <img src="images/logo.png" alt="Logo Darko's">
        <a href="login.php">Login</a>
        <a href="index.php">Home</a>
    </div>
</header>
<h1>Login Formulier</h1>
<form action="login.php" method="post">
    <div class="container">
        <label for="uname">Gebruikersnaam</label>
        <input type="text" name="username" placeholder="Voer gebruikersnaam in" required autocomplete="off">

        <label for="psw">Wachtwoord</label>
        <input type="password" name="password" placeholder="Voer wachtwoord in" required autocomplete="off">

        <input type="submit" name="login" value="Inloggen">
    </div>
</form>
</body>
</html>
