<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;
use App\Controleurs\RencontreControleur;

// Contrôleur des rencontres
$rencontreControleur = new RencontreControleur();
$stats_rencontres = $rencontreControleur->statistiquesRencontres();

$stats_gagnes = $stats_rencontres['victoires_pourcentage'] / 100;
$stats_perdus = $stats_rencontres['defaites_pourcentage'] / 100 + $stats_gagnes;
$stats_nuls = $stats_rencontres['nuls_pourcentage'] / 100 + $stats_perdus;

// Vérification des statistiques des rencontres
if (empty($stats_rencontres)) {
    echo "<p>Aucune rencontre trouvée.</p>";
    exit;
}

// Contrôleur des joueurs
$joueurControleur = new JoueurControleur();
$joueurs = $joueurControleur->liste_joueurs();

// Vérification des joueurs
if (!$joueurs || count($joueurs) === 0) {
    echo "<p>Aucun joueur trouvé.</p>";
    exit;
}

// Récupérer les statistiques des joueurs
$statistiques_joueurs = [];
foreach ($joueurs as $joueur) {
    $statistiques_joueurs[$joueur['numero_licence']] = $joueurControleur->getStatistiquesJoueur($joueur['numero_licence']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/stats.css">
    <link rel="stylesheet" href="/football_manager/public/assets/css/charts.min.css">
    <title>Statistiques des Joueurs</title>
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
                        <span class="data"><?= htmlspecialchars($stats_rencontres['nuls_pourcentage']) ?>%</span>
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

        <h2>Statistiques des Joueurs</h2>
        <table class="stats_joueurs">
            <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Poste Préféré</th>
                <th>Titularisations</th>
                <th>Remplaçant</th>
                <th>Moyenne</th>
                <th>Victoires</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($joueurs as $joueur) :
                $stats = $statistiques_joueurs[$joueur['numero_licence']];
                ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                    <td><?= htmlspecialchars($joueur['nom']) ?></td>
                    <td><?= htmlspecialchars($joueur['statut']) ?></td>
                    <td><?= htmlspecialchars($joueur['position_preferee']) ?></td>
                    <td><?= htmlspecialchars($stats['titularisations'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($stats['remplacements'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($stats['moyenne_notes'] ?? 0) ?>/5</td>
                    <td><?= htmlspecialchars($stats['pourcentage_victoires'] ?? 0) ?>%</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
