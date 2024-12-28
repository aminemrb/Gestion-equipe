<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\JoueurControleur;

$controleurRencontre = new RencontreControleur();
$controleurSelection = new SelectionControleur();
$controleurJoueur = new JoueurControleur();
$listeRencontres = $controleurRencontre->liste_rencontres();

// Fonction pour récupérer et filtrer les joueurs par poste
function getJoueursParPoste($joueursSelectionnes) {
    return [
        'gardiens' => array_filter($joueursSelectionnes, fn($j) => $j['poste'] === 'Gardien'),
        'defenseurs' => array_filter($joueursSelectionnes, fn($j) => $j['poste'] === 'Défenseur'),
        'milieux' => array_filter($joueursSelectionnes, fn($j) => $j['poste'] === 'Milieu'),
        'attaquants' => array_filter($joueursSelectionnes, fn($j) => $j['poste'] === 'Attaquant'),
    ];
}

// Fonction pour formater la date en français
function formaterDate($date) {
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $dateObj = new DateTime($date);
    return strftime('%A %d %B %Y', $dateObj->getTimestamp());
}

// Fonction pour déterminer la couleur du score
function couleurScore($scoreEquipe, $scoreAdverse) {
    if ($scoreEquipe > $scoreAdverse) {
        return 'green'; // Victoire
    } elseif ($scoreEquipe < $scoreAdverse) {
        return 'red'; // Défaite
    }
    return 'white'; // Match nul
}

// Fonction pour afficher les joueurs sous forme de texte
function afficherJoueurs($joueurs) {
    return empty($joueurs) ? 'Aucun joueur' : implode('<br>', array_map(fn($j) => htmlspecialchars($j['nom'] . ' ' . $j['prenom']), $joueurs));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/rencontres.css">
    <title>Liste des Rencontres</title>
</head>
<body>
<main id="liste">
    <h1>Liste des Rencontres</h1>
    <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_rencontre.php" class="btn-ajouter">Ajouter une rencontre</a>

    <div class="rencontres-container">
        <!-- Colonne des matchs passés -->
        <div class="column">
            <h2>Matchs Passés</h2>
            <?php foreach ($listeRencontres as $rencontre): ?>
                <?php
                $joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);
                $remplacants = array_diff($joueursSelectionnes, array_merge($joueursParPoste['gardiens'], $joueursParPoste['defenseurs'], $joueursParPoste['milieux'], $joueursParPoste['attaquants']));

                $resultat = $rencontre['resultat'] ?? 'N/A';
                $scoreEquipe = $rencontre['score_equipe'] ?? null;
                $scoreAdverse = $rencontre['score_adverse'] ?? null;
                $couleurScore = couleurScore($scoreEquipe, $scoreAdverse);

                $score = ($scoreEquipe !== null && $scoreAdverse !== null)
                    ? "{$scoreEquipe}-{$scoreAdverse}"
                    : 'N/A';

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFutur = $matchDateTime > $currentDateTime;
                $nbJoueursNotes = $controleurSelection->getNbJoueursNotes($rencontre['id_rencontre']);
                $isJoueursNotes = ($nbJoueursNotes == count($joueursSelectionnes));
                ?>

                <?php if (!$isMatchFutur): ?>
                    <div class="match-card">
                        <div class="match-header">
                            <div class="match-date-time">
                                <span class="match-date"><strong><?= formaterDate($rencontre['date_rencontre']) ?></strong> à </span>
                                <span class="match-time"><strong><?= htmlspecialchars($rencontre['heure_rencontre']) ?></strong> - </span>
                                <span class="match-lieu"><?= htmlspecialchars($rencontre['lieu']) ?></span>
                            </div>
                            <div class="match-result">
                                <span><?= $resultat ?></span>
                            </div>
                        </div>

                        <div class="match-body">
                            <div class="team">
                                <span class="team-name">Mon équipe</span>
                                <span class="score" style="color: <?= $couleurScore ?>;"><?= $score ?? 'N/A' ?></span>
                                <span class="team-name"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></span>
                            </div>
                        </div>

                        <div class="match-footer">
                            <div class="actions">
                                <?php if (!empty($joueursSelectionnes)): ?>
                                    <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Evaluations</a>
                                    <?php if ($isJoueursNotes): ?>
                                        <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_resultat.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Scorer</a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                                    <div class="players-selected">
                                        <strong>Joueurs Sélectionnés:</strong>
                                        <div id="joueurs-selectionnes-<?= $rencontre['id_rencontre'] ?>"><?= afficherJoueurs($joueursSelectionnes) ?></div>
                                    </div>
                                <?php else: ?>
                                    <span>MATCH ANNULÉ (aucun joueur sélectionné)</span>
                                    <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Colonne des matchs à venir -->
        <div class="column">
            <h2>Matchs à Venir</h2>
            <?php foreach ($listeRencontres as $rencontre): ?>
                <?php
                $nombreJoueurs = $controleurJoueur->liste_joueurs_actifs();
                $joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);
                $remplacants = array_diff($joueursSelectionnes, array_merge($joueursParPoste['gardiens'], $joueursParPoste['defenseurs'], $joueursParPoste['milieux'], $joueursParPoste['attaquants']));

                $resultat = $rencontre['resultat'] ?? 'N/A';
                $scoreEquipe = $rencontre['score_equipe'] ?? null;
                $scoreAdverse = $rencontre['score_adverse'] ?? null;
                $couleurScore = couleurScore($scoreEquipe, $scoreAdverse);

                $score = ($scoreEquipe !== null && $scoreAdverse !== null)
                    ? "{$scoreEquipe}-{$scoreAdverse}"
                    : 'N/A';

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFutur = $matchDateTime > $currentDateTime;
                ?>
                <?php if ($isMatchFutur): ?>
                    <div class="match-card">
                        <div class="match-header">
                            <div class="match-date-time">
                                <span class="match-date"><strong><?= formaterDate($rencontre['date_rencontre']) ?></strong> à </span>
                                <span class="match-time"><strong><?= htmlspecialchars($rencontre['heure_rencontre']) ?></strong> - </span>
                                <span class="match-lieu"><?= htmlspecialchars($rencontre['lieu']) ?></span>
                            </div>
                            <div class="match-result">
                                <span><?= $resultat ?></span>
                            </div>
                        </div>

                        <div class="match-body">
                            <div class="team">
                                <span class="team-name">Mon équipe</span>
                                <span class="score" style="color: <?= $couleurScore ?>;"><?= $score ?? 'N/A' ?></span>
                                <span class="team-name"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></span>
                            </div>
                        </div>

                        <div class="match-footer">
                            <div class="actions">
                                <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action <?= $nombreJoueurs < 11 ? 'disabled' : '' ?>"
                                   onclick="return <?= $nombreJoueurs < 11 ? 'alert(\'Vous devez avoir au moins 11 joueurs dans la base de données pour accéder à la feuille de match.\'); return false;' : 'true'; ?>">Sélection</a>
                                <a href="<?= BASE_URL ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Modifier</a>
                                <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                            </div>

                            <div class="team-composition">
                                <div class="formation">
                                    <h3>Formation 4-3-3</h3>
                                    <div class="field">
                                        <div class="line defense">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs'][0]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs'][1]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs'][2]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs'][3]['nom'] ?? 'N/A') ?></div>
                                        </div>
                                        <div class="line midfield">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux'][0]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux'][1]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux'][2]['nom'] ?? 'N/A') ?></div>
                                        </div>
                                        <div class="line forward">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants'][0]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants'][1]['nom'] ?? 'N/A') ?></div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants'][2]['nom'] ?? 'N/A') ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="substitute-bench">
                                    <h3>Banc de touche</h3>
                                    <div class="bench">
                                        <?= afficherJoueurs($remplacants); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
