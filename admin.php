<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "mysql_db";
$username = "root";
$password = "rootpassword";
$feedback = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=Menukaart", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Verbinding mislukt: " . $e->getMessage());
}

// Dingen toevoegen
if (isset($_POST['add'])) {
    $naam = htmlspecialchars(trim($_POST['naam']));
    $omschrijving = htmlspecialchars(trim($_POST['omschrijving']));
    $prijs = filter_var($_POST['prijs'], FILTER_VALIDATE_FLOAT);

    if (!empty($naam) && !empty($omschrijving) && $prijs !== false) {
        $sql = "INSERT INTO menu (naam, omschrijving, prijs) VALUES (:naam, :omschrijving, :prijs)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([':naam' => $naam, ':omschrijving' => $omschrijving, ':prijs' => $prijs])) {
            $feedback = "Succesvol toegevoegd!";
        } else {
            $feedback = "Toevoegen mislukt!";
        }
    } else {
        $feedback = "Vul een geldige naam, omschrijving en prijs in.";
    }
}

// Bewerken
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $naam = htmlspecialchars(trim($_POST['naam']));
    $omschrijving = htmlspecialchars(trim($_POST['omschrijving']));
    $prijs = filter_var($_POST['prijs'], FILTER_VALIDATE_FLOAT);

    if (!empty($naam) && !empty($omschrijving) && $prijs !== false) {
        $sql = "UPDATE menu SET naam = :naam, omschrijving = :omschrijving, prijs = :prijs WHERE id = :id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([':naam' => $naam, ':omschrijving' => $omschrijving, ':prijs' => $prijs, ':id' => $id])) {
            $feedback = "Item bijgewerkt!";
        } else {
            $feedback = "Bijwerken mislukt!";
        }
    } else {
        $feedback = "Vul een geldige naam, omschrijving en prijs in.";
    }
}

// Verwijderen
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM menu WHERE id = :id";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([':id' => $id])) {
        $feedback = "Item verwijderd!";
    } else {
        $feedback = "Verwijderen mislukt!";
    }
}

// Menu ophalen
try {
    $sql = "SELECT * FROM menu";
    $stmt = $conn->query($sql);
    if ($stmt !== false) {
        $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $menu = [];
        $feedback = "Er zijn geen menu-items gevonden.";
    }
} catch (PDOException $e) {
    $menu = [];
    $feedback = "Fout bij het ophalen van het menu: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Menu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Menu Beheer</h1>
</header>

<?php if (!empty($feedback)): ?>
    <p><?= $feedback ?></p>
<?php endif; ?>

<h2>Nieuw menu-item toevoegen</h2>
<form method="POST">
    <label>Naam:</label>
    <input type="text" name="naam" required>
    <label>Omschrijving:</label>
    <input type="text" name="omschrijving" required>
    <label>Prijs (€):</label>
    <input type="number" name="prijs" step="0.01" required>
    <input type="submit" name="add" value="Toevoegen">
</form>

<h2>Huidige Menu-items</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Omschrijving</th>
        <th>Prijs</th>
        <th>Acties</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($menu)): ?>
        <?php foreach ($menu as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['ID']) ?></td>
                <td><?= htmlspecialchars($item['naam']) ?></td>
                <td><?= htmlspecialchars($item['omschrijving']) ?></td>
                <td>€ <?= number_format($item['prijs'], 2, ',', '.') ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $item['ID'] ?>">
                        <input type="text" name="naam" value="<?= htmlspecialchars($item['naam']) ?>" required>
                        <input type="text" name="omschrijving" value="<?= htmlspecialchars($item['omschrijving']) ?>" required>
                        <input type="number" name="prijs" value="<?= htmlspecialchars($item['prijs']) ?>" step="0.01" required>
                        <input type="submit" name="edit" value="Wijzig">
                    </form>
                    <a href="?delete=<?= $item['ID'] ?>" onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?');">Verwijderen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Geen menu-items gevonden.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<a href="login.php">Uitloggen</a>
</body>
</html>
