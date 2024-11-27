<?php
require_once 'config.php'; 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $equipe_adverse = trim($_POST['equipe_adverse'] ?? '');
    $lieu = $_POST['lieu'] ?? '';

    if (!$date || !$heure || !$equipe_adverse || !$lieu) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {
        $query = $pdo->prepare("INSERT INTO rencontres (date, heure, equipe_adverse, lieu) VALUES (?, ?, ?, ?)");
        $query->execute([$date, $heure, $equipe_adverse, $lieu]);

        header('Location: liste_rencontres.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une rencontre</title>
</head>
<body>
    <h1>Ajouter une rencontre</h1>
    <a href="liste_rencontres.php">Retour à la liste des rencontres</a>

    <?php if ($errors): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>Date :</label>
        <input type="date" name="date" required><br>
        <label>Heure :</label>
        <input type="time" name="heure" required><br>
        <label>Équipe adverse :</label>
        <input type="text" name="equipe_adverse" maxlength="255" required><br>
        <label>Lieu :</label>
        <select name="lieu" required>
            <option value="domicile">Domicile</option>
            <option value="exterieur">Extérieur</option>
        </select><br>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
