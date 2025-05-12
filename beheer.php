
<?php
session_start();
$servername = "mysql_db";
$username = "root";
$password = "rootpassword";

try {
    $conn = new PDO("mysql:host=$servername;dbname=Menukaart", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$sql = "SELECT * FROM menu";
$stmt = $conn->query($sql);
$menu = $stmt->fetchAll(pdo::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Darko's</title>
</head>
<body>
<header>
    <div class="container-header">
        <img src="images/logo.png" alt="Logo Darko's">
    </div>
    <div class="menu-content" id="menu-lijst">
        <?php if (!empty($menu)): ?>
            <ul>
                <?php foreach ($menu as $menu): ?>
                    <li><strong><?= htmlspecialchars($menu['naam']) ?> - </strong> <strong><?= htmlspecialchars($menu['omschrijving']) ?></strong> - €<?= number_format($menu['prijs'], 2, ',', '.') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Er staan momenteel geen producten op het menu.</p>
        <?php endif; ?>
    </div>

</header>
</body>
</html>
