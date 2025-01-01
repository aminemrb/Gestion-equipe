<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;
use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();
$stats_rencontres = $rencontreControleur->statistiquesRencontres();

$stats_gagnes = $stats_rencontres['victoires_pourcentage']/100;
$stats_perdus = $stats_rencontres['defaites_pourcentage']/100 + $stats_gagnes;
$stats_nuls = $stats_rencontres['nuls_pourcentage']/100 + $stats_perdus;

// Vérification si des statistiques existent
if (empty($stats_rencontres)) {
    echo "<p>Aucune rencontre trouvée.</p>";
    exit;
}

$joueurControleur = new JoueurControleur();
$joueurs = $joueurControleur->liste_joueurs();

// Vérification si des joueurs existent
if (!$joueurs || count($joueurs) === 0) {
    echo "<p>Aucun joueur trouvé.</p>";
    exit;
}

// Récupérer le nombre de notes pour chaque joueur
$nb_notes = [];
$nb_remplacements = [];
$moyenne_notes = [];
$pourcentage_victoires = [];
foreach ($joueurs as $joueur) {
    $nb_notes[$joueur['numero_licence']] = $joueurControleur->nombreTitularisation($joueur['numero_licence']);
    $nb_remplacements[$joueur['numero_licence']] = $joueurControleur->nombreRemplacementsJoueur($joueur['numero_licence']);
    $moyenne_notes[$joueur['numero_licence']] = $joueurControleur->moyenneNotesJoueur($joueur['numero_licence']);
    $pourcentage_victoires[$joueur['numero_licence']] = $joueurControleur->pourcentageVictoiresJoueur($joueur['numero_licence']);

}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/stats.css">
    <link rel="stylesheet" href="/football_manager/public/assets/css/charts.min.css">
    <title>Liste des joueurs</title>
</head>
<body>
<div id="stats">
    <main>
        <h2>Statistiques des Rencontres</h2>

        <div id="my-chart">
            <table class="charts-css pie hide-data">
                <caption>Statistiques des matchs</caption>
                <thead>
                <tr>
                    <th scope="col">Matchs</th>
                    <th scope="col">Pourcentage</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> Matchs gagnés </th>
                    <td style="--start: 0; --end: <?=$stats_gagnes?>; --color: #35b135;">
                        <span class="data"><?= htmlspecialchars($stats_rencontres['victoires_pourcentage']) ?>%</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"> Matchs perdus </th>
                    <td style="--start: <?=$stats_gagnes?>; --end:<?=$stats_perdus?> ; --color: #da3939;">
                        <span class="data"><?= htmlspecialchars($stats_rencontres['defaites_pourcentage']) ?>%</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"> Matchs nuls </th>
                    <td style="--start: <?=$stats_perdus?>; --end: <?=$stats_nuls?>; --color: #d8c9c9;">
                        <span class="data"> <?= htmlspecialchars($stats_rencontres['nuls_pourcentage']) ?>%</span>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="stats_matchs">
                <p><span style="color: #35b135;">&#9608;&#9608;&#9608;&#9608;</span> Matchs gagnés : <?= htmlspecialchars($stats_rencontres['victoires']) ?></p>
                <p><span style="color: #da3939;">&#9608;&#9608;&#9608;&#9608;</span> Matchs perdus : <?= htmlspecialchars($stats_rencontres['defaites']) ?></p>
                <p><span style="color: #d8c9c9;">&#9608;&#9608;&#9608;&#9608;</span> Matchs nuls : <?= htmlspecialchars($stats_rencontres['nuls']) ?></p></br>
                <p class="total">Nombre total de matchs : <?= htmlspecialchars($stats_rencontres['total_matchs']) ?></p>
            </div>
        </div>

        <h2>Statistiques des joueurs</h2>

        <table class="stats_joueurs">
            <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Poste Préférée</th>
                <th>Titularisation</th>
                <th>Remplaçant</th>
                <th>Moyenne</th>
                <th>Victoires</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($joueurs as $joueur) : ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                    <td><?= htmlspecialchars($joueur['nom']) ?></td>
                    <td><?= htmlspecialchars($joueur['statut']) ?></td>
                    <td><?= htmlspecialchars($joueur['position_preferee']) ?></td>
                    <td>
                        <?= htmlspecialchars($nb_notes[$joueur['numero_licence']]['nombre_notes']) ?>
                    </td>
                    <td><?= htmlspecialchars($nb_remplacements[$joueur['numero_licence']] ?? 0) ?></td>
                    <td><?= htmlspecialchars($moyenne_notes[$joueur['numero_licence']] ?? 0) ?>/5</td>
                    <td><?= htmlspecialchars($pourcentage_victoires[$joueur['numero_licence']] ?? 0) ?>%</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
