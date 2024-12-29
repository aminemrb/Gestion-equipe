<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

// Initialisation
$joueurControleur = new JoueurControleur();
$joueurs = $joueurControleur->liste_joueurs();

// Vérification si des joueurs existent
if (!$joueurs || count($joueurs) === 0) {
    echo "<p>Aucun joueur trouvé.</p>";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/joueurs.css">
    <title>Liste des joueurs</title>
</head>
<body>
<div id="liste">
    <main>
        <h1>Liste des joueurs</h1>
        <a class="btn-ajouter" href="<?= BASE_URL ?>/vues/Joueurs/ajouter_joueur.php">Ajouter un joueur</a>

        <table border="1">
            <thead>
            <tr>
                <th>Numéro de Licence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de Naissance</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Statut</th>
                <th>Poste Préférée</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($joueurs as $joueur) : ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['numero_licence']) ?></td>
                    <td><?= htmlspecialchars($joueur['nom']) ?></td>
                    <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                    <td><?= htmlspecialchars($joueur['date_naissance']) ?></td>
                    <td><?= htmlspecialchars($joueur['taille']) ?> m</td>
                    <td><?= htmlspecialchars($joueur['poids']) ?> kg</td>
                    <td><?= htmlspecialchars($joueur['statut']) ?></td>
                    <td><?= htmlspecialchars($joueur['position_preferee']) ?></td>
                    <td><?= htmlspecialchars($joueur['commentaire']) ?></td>
                    <td class="actions">
                        <a class="btn-modifier" href="<?= BASE_URL ?>/vues/Joueurs/modifier_joueur.php?numero_licence=<?= htmlspecialchars($joueur['numero_licence']) ?>">Modifier</a>
                        <a class="btn-supprimer" href="<?= BASE_URL ?>/vues/Joueurs/supprimer_joueur.php?numero_licence=<?= htmlspecialchars($joueur['numero_licence']) ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
