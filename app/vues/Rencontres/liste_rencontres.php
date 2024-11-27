<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $query = $pdo->prepare("DELETE FROM rencontres WHERE id = ?");
    $query->execute([$delete_id]);
}

$query = $pdo->query("SELECT * FROM rencontres ORDER BY date, heure");
$rencontres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des rencontres</title>
</head>
<body>
    <h1>Liste des rencontres</h1>
    <a href="ajouter_rencontre.php">Ajouter une rencontre</a>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Équipe adverse</th>
            <th>Lieu</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rencontres as $rencontre): ?>
            <tr>
                <td><?php echo htmlspecialchars($rencontre['date']); ?></td>
                <td><?php echo htmlspecialchars($rencontre['heure']); ?></td>
                <td><?php echo htmlspecialchars($rencontre['equipe_adverse']); ?></td>
                <td><?php echo htmlspecialchars($rencontre['lieu']); ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">
                        <input type="hidden" name="delete_id" value="<?php echo $rencontre['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                    <a href="modifier_rencontre.php?id=<?php echo $rencontre['id']; ?>">Modifier</a>
                    <a href="formulaire_selection.php?rencontre_id=<?php echo $rencontre['id']; ?>">Préparer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
