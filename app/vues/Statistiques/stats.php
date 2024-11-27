<?php include __DIR__ . '/../Layouts/header.php'; ?>
<?php
require_once 'config.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$query = $pdo->query("
    SELECT
        COUNT(*) AS total_matchs,
        SUM(CASE WHEN resultat = 'victoire' THEN 1 ELSE 0 END) AS victoires,
        SUM(CASE WHEN resultat = 'defaite' THEN 1 ELSE 0 END) AS defaites,
        SUM(CASE WHEN resultat = 'nul' THEN 1 ELSE 0 END) AS nuls
    FROM rencontres
");
$stats_equipe = $query->fetch(PDO::FETCH_ASSOC);

$total_matchs = $stats_equipe['total_matchs'] ?: 1; // Évite la division par zéro
$pourcentage_victoires = round(($stats_equipe['victoires'] / $total_matchs) * 100, 2);
$pourcentage_defaites = round(($stats_equipe['defaites'] / $total_matchs) * 100, 2);
$pourcentage_nuls = round(($stats_equipe['nuls'] / $total_matchs) * 100, 2);

$query = $pdo->query("
    SELECT
        joueurs.nom,
        joueurs.prenom,
        joueurs.statut,
        joueurs.poste_prefere,
        COUNT(CASE WHEN participations.role = 'titulaire' THEN 1 ELSE NULL END) AS titularisations,
        COUNT(CASE WHEN participations.role = 'remplaçant' THEN 1 ELSE NULL END) AS remplacements,
        AVG(evaluations.note) AS moyenne_notes,
        COUNT(participations.match_id) AS total_matchs_participes,
        SUM(CASE WHEN rencontres.resultat = 'victoire' THEN 1 ELSE 0 END) AS victoires_participation,
        MAX(COALESCE((
            SELECT COUNT(*)
            FROM participations p2
            WHERE p2.joueur_id = participations.joueur_id
              AND p2.match_id IN (SELECT id FROM rencontres WHERE rencontres.date >= participations.date)
        ), 0)) AS matchs_consecutifs
    FROM joueurs
    LEFT JOIN participations ON joueurs.id = participations.joueur_id
    LEFT JOIN rencontres ON participations.match_id = rencontres.id
    LEFT JOIN evaluations ON participations.id = evaluations.participation_id
    GROUP BY joueurs.id
");
$stats_joueurs = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistiques de l'équipe</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Statistiques de l'équipe</h1>
    <a href="index.php">Retour au menu principal</a>

    <h2>Statistiques globales</h2>
    <p>Total de matchs : <?php echo $stats_equipe['total_matchs']; ?></p>
    <p>Victoires : <?php echo $stats_equipe['victoires']; ?> (<?php echo $pourcentage_victoires; ?>%)</p>
    <p>Défaites : <?php echo $stats_equipe['defaites']; ?> (<?php echo $pourcentage_defaites; ?>%)</p>
    <p>Matchs nuls : <?php echo $stats_equipe['nuls']; ?> (<?php echo $pourcentage_nuls; ?>%)</p>

    <h2>Statistiques des joueurs</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Statut</th>
                <th>Poste préféré</th>
                <th>Titularisations</th>
                <th>Remplacements</th>
                <th>Moyenne des évaluations</th>
                <th>Total matchs joués</th>
                <th>% Victoires</th>
                <th>Matchs consécutifs</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats_joueurs as $joueur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($joueur['nom'] . ' ' . $joueur['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['statut']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['poste_prefere']); ?></td>
                    <td><?php echo $joueur['titularisations']; ?></td>
                    <td><?php echo $joueur['remplacements']; ?></td>
                    <td><?php echo round($joueur['moyenne_notes'], 2); ?></td>
                    <td><?php echo $joueur['total_matchs_participes']; ?></td>
                    <td><?php echo round(($joueur['victoires_participation'] / max($joueur['total_matchs_participes'], 1)) * 100, 2); ?>%</td>
                    <td><?php echo $joueur['matchs_consecutifs']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>


<?php include __DIR__ . '/../Layouts/footer.php'; ?>