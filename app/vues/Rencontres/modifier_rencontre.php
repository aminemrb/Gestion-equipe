<?php
require_once 'config.php'; // Connexion à la base via PDO
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupération de l'ID de la rencontre
$rencontre_id = $_GET['id'] ?? null;

if (!$rencontre_id) {
    die("ID de la rencontre manquant.");
}

// Récupération des données actuelles de la rencontre
$query = $pdo->prepare("SELECT * FROM rencontres WHERE id = ?");
$query->execute([$rencontre_id]);
$rencontre = $query->fetch(PDO::FETCH_ASSOC);

if (!$rencontre) {
    die("Rencontre introuvable.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $equipe_adverse = trim($_POST['equipe_adverse'] ?? '');
    $lieu = $_POST['lieu'] ?? '';

    // Validation des champs côté serveur
    if (!$date || !$heure || !$equipe_adverse || !$lieu) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {
        // Mise à jour des informations dans la base de données
        $query = $pdo->prepare("UPDATE rencontres SET date = ?, heure = ?, equipe_adverse = ?, lieu = ? WHERE id = ?");
        $query->execute([$date, $heure, $equipe_adverse, $lieu, $rencontre_id]);

        header('Location: liste_rencontres.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier une rencontre</title>
</head>
<body>
    <h1>Modifier une rencontre</h1>
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
        <input type="date" name="date" value="<?php echo htmlspecialchars($rencontre['date']); ?>" required><br>

        <label>Heure :</label>
        <input type="time" name="heure" value="<?php echo htmlspecialchars($rencontre['heure']); ?>" required><br>

        <label>Équipe adverse :</label>
        <input type="text" name="equipe_adverse" maxlength="255" value="<?php echo htmlspecialchars($rencontre['equipe_adverse']); ?>" required><br>

        <label>Lieu :</label>
        <select name="lieu" required>
            <option value="domicile" <?php echo $rencontre['lieu'] === 'domicile' ? 'selected' : ''; ?>>Domicile</option>
            <option value="exterieur" <?php echo $rencontre['lieu'] === 'exterieur' ? 'selected' : ''; ?>>Extérieur</option>
        </select><br>

        <button type="submit">Enregistrer les modifications</button>
    </form>
</body>
</html>
