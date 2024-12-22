<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;

$rencontreControleur = new RencontreControleur();
$selectionControleur = new SelectionControleur();
$rencontres = $rencontreControleur->liste_rencontres();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/liste.css">
    <title>Liste des Rencontres</title>
</head>
<body>
<main id="liste">
    <h1>Liste des Rencontres</h1>
    <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_rencontre.php">Ajouter une rencontre</a>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Équipe Adverse</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Lieu</th>
            <th>Résultat</th>
            <th>Joueurs Sélectionnés</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rencontres as $rencontre): ?>
            <?php
            $joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($rencontre['id_rencontre']);
            $joueurs = empty($joueurs_selectionnes)
                ? "Aucun joueur sélectionné"
                : implode('<br>', array_map(fn($j) => htmlspecialchars($j['nom'] . ' ' . $j['prenom']), $joueurs_selectionnes));
            $resultat = $rencontre['resultat'] ?? 'N/A';
            $score = isset($rencontre['score_equipe'], $rencontre['score_adverse']) ? "{$rencontre['score_equipe']}-{$rencontre['score_adverse']}" : 'N/A';
            $currentDateTime = new DateTime();
            $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
            $ajouter_resultat_link = (empty($joueurs_selectionnes) || $currentDateTime < $matchDateTime)
                ? ''
                : "<a href=\"" . BASE_URL . "/vues/Rencontres/ajouter_resultat.php?id_rencontre={$rencontre['id_rencontre']}\">Ajouter Résultat</a>";
            ?>
            <tr>
                <td><?= $rencontre['id_rencontre'] ?></td>
                <td><?= htmlspecialchars($rencontre['equipe_adverse']) ?></td>
                <td><?= htmlspecialchars($rencontre['date_rencontre']) ?></td>
                <td><?= htmlspecialchars($rencontre['heure_rencontre']) ?></td>
                <td><?= htmlspecialchars($rencontre['lieu']) ?></td>
                <td><?= $resultat . ($score !== 'N/A' ? " ($score)" : '') ?></td>
                <td><?= $joueurs ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/vues/Feuille_rencontres/formulaire_selection.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>">Sélection</a>
                    <a href="<?= BASE_URL ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>">Modifier</a>
                    <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>"
                       class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                    <?= $ajouter_resultat_link ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
