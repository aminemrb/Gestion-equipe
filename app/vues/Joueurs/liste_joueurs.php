<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

$joueurControleur = new JoueurControleur();
$joueurs = $joueurControleur->liste_joueurs();

// Vérification si des joueurs existent
if (!$joueurs || count($joueurs) === 0) {
    echo "<p style='color: red;text-align: center' >Aucun joueur trouvé.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/joueurs.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Liste des joueurs</title>
</head>
<body>
<div id="liste">
    <main>
        <h1>Gestion des joueurs</h1>
        <div style="text-align: center; margin-top: 10px">
            <a class="btn-ajouter" href="/football_manager/joueurs/ajouter">Ajouter un joueur</a>
        </div>

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
                        <a class="btn-modifier" href="/football_manager/joueurs/modifier?numero_licence=<?= htmlspecialchars($joueur['numero_licence']) ?>">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a class="btn-supprimer" href="/football_manager/joueurs/supprimer?numero_licence=<?= htmlspecialchars($joueur['numero_licence']) ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
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
