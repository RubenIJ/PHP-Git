<?php
session_start();
$servername = "mysql_db";
$username = "root";
$password = "rootpassword";

try {
    $conn = new PDO("mysql:host=$servername;dbname=Menukaart", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Zoeken
$search = '';
if (isset($_POST['search'])) {
    $search = htmlspecialchars($_POST['query']);
    $sql = "SELECT * FROM menu WHERE naam LIKE :search OR omschrijving LIKE :search";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->execute();
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT * FROM menu";
    $stmt = $conn->query($sql);
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Darko's</title>
</head>
<body>
<header>
    <div class="container-header">
        <img src="images/logo.png" alt="Logo Darko's">
        <a href="login.php">Login</a>
        <a href="index.php">Home</a>
    </div>
</header>

<main>
    <!-- Zoekformulier -->
    <form class="search-form" action="" method="post">
        <input type="text" name="query" placeholder="Zoek..." value="<?php echo $search; ?>">
        <button type="submit" name="search">Zoeken</button>
    </form>

    <div class="menu-content" id="menu-lijst">
        <?php if (!empty($menu)): ?>
            <ul>
                <?php foreach ($menu as $item): ?>
                    <li><strong><?= htmlspecialchars($item['naam']) ?> - </strong>
                        <strong><?= htmlspecialchars($item['omschrijving']) ?></strong> - €<?= number_format($item['prijs'], 2, ',', '.') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Geen resultaten gevonden.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
