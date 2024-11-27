<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$rencontre_id = $_GET['rencontre_id'] ?? null;

$query = $pdo->prepare("SELECT id FROM rencontres WHERE id = ?");
$query->execute([$rencontre_id]);
if (!$query->fetch()) {
    die("Rencontre invalide.");
}

$query = $pdo->prepare("SELECT id, nom, prenom, taille, poids, commentaire FROM joueurs WHERE statut = 'Actif'");
$query->execute();
$joueurs = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $joueurs_selectionnes = $_POST['joueurs'] ?? [];
    foreach ($joueurs_selectionnes as $joueur_id => $details) {
        $role = $details['role'];
        $poste = trim($details['poste']);
        if ($role && $poste) {
            $query = $pdo->prepare("INSERT INTO feuille_match (rencontre_id, joueur_id, role, poste) VALUES (?, ?, ?, ?)");
            $query->execute([$rencontre_id, $joueur_id, $role, $poste]);
        }
    }

    header("Location: liste_rencontres.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de sélection</title>
</head>
<body>
    <h1>Formulaire de sélection</h1>
    <a href="liste_rencontres.php">Retour à la liste des rencontres</a>

    <form method="POST">
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Commentaire</th>
                <th>Rôle</th>
                <th>Poste</th>
            </tr>
            <?php foreach ($joueurs as $joueur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($joueur['nom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['taille']); ?> cm</td>
                    <td><?php echo htmlspecialchars($joueur['poids']); ?> kg</td>
                    <td><?php echo htmlspecialchars($joueur['commentaire']); ?></td>
                    <td>
                        <select name="joueurs[<?php echo $joueur['id']; ?>][role]" required>
                            <option value="titulaire">Titulaire</option>
                            <option value="remplaçant">Remplaçant</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="joueurs[<?php echo $joueur['id']; ?>][poste]" maxlength="50" required>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit">Valider la sélection</button>
    </form>
</body>
</html>
