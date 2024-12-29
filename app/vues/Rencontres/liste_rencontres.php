<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\JoueurControleur;

$controleurRencontre = new RencontreControleur();
$controleurSelection = new SelectionControleur();
$controleurJoueur = new JoueurControleur();
$listeRencontres = $controleurRencontre->liste_rencontres();


// Organiser les joueurs par poste
function getJoueursParPoste($joueurs) {
    $postesMapping = [
        'gardiens'    => ['GB'],
        'defenseurs'  => ['DD', 'DG', 'DCG', 'DCD'], // Défenseurs spécifiques
        'milieux'     => ['MD', 'MCG', 'MCD'],       // Milieux spécifiques
        'attaquants'  => ['AD', 'AG', 'BU'],         // Attaquants spécifiques
        'remplacants' => []                          // Pour tous les autres
    ];

    $joueursParPoste = [
        'gardiens'    => [],
        'defenseurs'  => [],
        'milieux'     => [],
        'attaquants'  => [],
        'remplacants' => []
    ];

    foreach ($joueurs as $joueur) {
        $poste = $joueur['poste'];
        $ajoute = false;

        // Assigner le joueur à sa catégorie en fonction du poste
        foreach ($postesMapping as $categorie => $postes) {
            if (in_array($poste, $postes)) {
                $joueursParPoste[$categorie][$poste] = $joueur; // Clé = poste exact
                $ajoute = true;
                break;
            }
        }

        if (!$ajoute) {
            $joueursParPoste['remplacants'][] = $joueur;
        }
    }

    return $joueursParPoste;
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
                // Récupérer les joueurs sélectionnés pour la rencontre
                $joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);

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
                                    <div class="team-composition">
                                        <div class="formation">
                                            <h3>Formation</h3>
                                            <div class="field">
                                                <!-- Attaque -->
                                                <div class="line forward">
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AG']['nom'] ?? 'N/A') ?><br>AG</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['BU']['nom'] ?? 'N/A') ?><br>BU</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AD']['nom'] ?? 'N/A') ?><br>AD</div>
                                                </div>
                                                <!-- Milieu -->
                                                <div class="line midfield">
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCG']['nom'] ?? 'N/A') ?><br>MCG</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MD']['nom'] ?? 'N/A') ?><br>MD</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCD']['nom'] ?? 'N/A') ?><br>MCD</div>
                                                </div>
                                                <!-- Défense -->
                                                <div class="line defense">
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DG']['nom'] ?? 'N/A') ?><br>DG</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCG']['nom'] ?? 'N/A') ?><br>DCG</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCD']['nom'] ?? 'N/A') ?><br>DCD</div>
                                                    <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DD']['nom'] ?? 'N/A') ?><br>DD</div>
                                                </div>
                                                <!-- Gardien -->
                                                <div class="line goal">
                                                    <div class="player">
                                                        <?= htmlspecialchars($joueursParPoste['gardiens']['GB']['nom'] ?? 'N/A') ?><br>GB
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <?php if(!$joueursParPoste['remplacants'] == []): ?>
                                        <!-- Banc de touche -->
                                        <div class="substitute-bench">
                                            <h3>Remplaçant(s)</h3>
                                            <div class="bench">
                                                <?php foreach ($joueursParPoste['remplacants'] as $remplacant): ?>
                                                    <div class="player">
                                                        <?= htmlspecialchars($remplacant['nom'] ?? 'N/A') ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                <!-- Actions -->
                                    <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Evaluations</a>
                                    <?php if ($isJoueursNotes): ?>
                                        <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_resultat.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Scorer</a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
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
                $joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);

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
                            <?php if (!empty($joueursSelectionnes)): ?>
                            <div class="team-composition">
                                <div class="formation">
                                    <h3>Formation</h3>
                                    <div class="field">
                                        <!-- Attaque -->
                                        <div class="line forward">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AG']['nom'] ?? 'N/A') ?><br>AG</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['BU']['nom'] ?? 'N/A') ?><br>BU</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AD']['nom'] ?? 'N/A') ?><br>AD</div>
                                        </div>
                                        <!-- Milieu -->
                                        <div class="line midfield">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCG']['nom'] ?? 'N/A') ?><br>MCG</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MD']['nom'] ?? 'N/A') ?><br>MD</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCD']['nom'] ?? 'N/A') ?><br>MCD</div>
                                        </div>
                                        <!-- Défense -->
                                        <div class="line defense">
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DG']['nom'] ?? 'N/A') ?><br>DG</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCG']['nom'] ?? 'N/A') ?><br>DCG</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCD']['nom'] ?? 'N/A') ?><br>DCD</div>
                                            <div class="player"><?= htmlspecialchars($joueursParPoste['defenseurs']['DD']['nom'] ?? 'N/A') ?><br>DD</div>
                                        </div>
                                        <!-- Gardien -->
                                        <div class="line goal">
                                                <div class="player">
                                                    <?= htmlspecialchars($joueursParPoste['gardiens']['GB']['nom'] ?? 'N/A') ?><br>GB
                                                </div>
                                        </div>

                                    </div>
                                </div>
                                <?php if(!$joueursParPoste['remplacants'] == []): ?>
                                <!-- Banc de touche -->
                                <div class="substitute-bench">
                                    <h3>Remplaçant(s)</h3>
                                    <div class="bench">
                                        <?php foreach ($joueursParPoste['remplacants'] as $remplacant): ?>
                                            <div class="player">
                                                <?= htmlspecialchars($remplacant['nom'] ?? 'N/A') ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="actions">
                                <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Sélection</a>
                                <a href="<?= BASE_URL ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Modifier</a>
                                <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
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
