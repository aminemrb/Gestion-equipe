<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\JoueurControleur;
use App\Controleurs\UtilisateurControleur;

$controleurRencontre = new RencontreControleur();
$controleurSelection = new SelectionControleur();
$controleurJoueur = new JoueurControleur();
$utilisateurControleur = new UtilisateurControleur();
$listeRencontres = $controleurRencontre->liste_rencontres();


$infosUtilisateur = $utilisateurControleur->getInfosUtilisateur();
$nomEquipe = htmlspecialchars($infosUtilisateur['nom_equipe']);

// Organiser les joueurs par poste
function getJoueursParPoste($joueurs) {
    $postesMapping = [
        'gardiens'    => ['GB'],
        'defenseurs'  => ['DD', 'DG', 'DCG', 'DCD'],
        'milieux'     => ['MD', 'MCG', 'MCD'],
        'attaquants'  => ['AD', 'AG', 'BU'],
        'remplacants' => []
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
                $joueursParPoste[$categorie][$poste] = $joueur;
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
    setlocale(LC_ALL, 'fr_FR.utf8', 'fr_FR','fr','fr','fra','fr_FR@euro');
    $dateObj = new DateTime($date);
    return strftime('%A %d %B %Y', $dateObj->getTimestamp());
}

// Fonction pour déterminer la couleur du score
function couleurScore($scoreEquipe, $scoreAdverse) {
    if ($scoreEquipe > $scoreAdverse) {
        return '#2dbc2d'; // Victoire
    } elseif ($scoreEquipe < $scoreAdverse) {
        return 'red'; // Défaite
    }elseif ($scoreEquipe == $scoreAdverse && $scoreEquipe !== null && $scoreAdverse !== null) {
        return 'white'; // Match nul
    }
    return '#1E1E1E';
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/rencontres.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Liste des Rencontres</title>
</head>
<body>
<main id="liste">
    <h1>Gestion des rencontres</h1>
    <div style="text-align: center;"><a href="/football_manager/rencontres/ajouter" class="btn-ajouter">Ajouter une rencontre</a></div>

    <div class="rencontres-container">

                                           <!-- Colonne des matchs passés -->
        <div class="column">
            <h2>Matchs Passés</h2>
            <?php foreach ($listeRencontres as $rencontre): ?>
                <?php
                // Récupérer les joueurs sélectionnés pour la rencontre
                $joueursSelectionnes = $controleurSelection->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);

                $resultat = $rencontre['resultat'] ?? '';
                $scoreEquipe = $rencontre['score_equipe'] ?? null;
                $scoreAdverse = $rencontre['score_adverse'] ?? null;
                $couleurScore = couleurScore($scoreEquipe, $scoreAdverse);

                if($rencontre['lieu'] == 'Domicile') :
                    $score = ($scoreEquipe !== null && $scoreAdverse !== null)
                    ? "{$scoreEquipe} - {$scoreAdverse}" : '-';
                else :
                    $score = ($scoreEquipe !== null && $scoreAdverse !== null)
                    ? "{$scoreAdverse} - {$scoreEquipe}" : '-';
                endif;

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFutur = $matchDateTime > $currentDateTime;

                $nbJoueursNotes = $controleurSelection->getNbJoueursNotes($rencontre['id_rencontre']);
                $isJoueursNotes = ($nbJoueursNotes == count($joueursSelectionnes));
                ?>

                <?php if (!$isMatchFutur): ?>
                    <div class="match-card" style="box-shadow: 0px 0px 0px 2px  <?= $couleurScore ?>">
                        <div class="match-header">
                            <div class="match-date-time">
                                <span class="match-date"><strong><?= formaterDate($rencontre['date_rencontre']) ?></strong> à </span>
                                <span class="match-time"><strong><?= htmlspecialchars($rencontre['heure_rencontre']) ?></strong> - </span>
                                <span class="match-lieu"><?= htmlspecialchars($rencontre['lieu']) ?></span>
                            </div>
                            <div class="match-result">
                                <strong><span style="color: <?= $couleurScore ?>;"><?= $resultat ?></span></strong>
                            </div>
                        </div>

                        <div class="match-body">
                            <?php if($rencontre['lieu'] == 'Domicile') :?>
                            <div class="team">
                                <div class="team-name team-end"><?=$nomEquipe?></div>
                                <span class="score" style="background-color: <?= $couleurScore ?>;"><?=$score?></span>
                                <div class="team-name team-left"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></div>
                            </div>
                            <?php else : ?>
                                <div class="team">
                                    <div class="team-name team-end"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></div>
                                    <span class="score" style="background-color: <?= $couleurScore ?>;"><?=$score?></span>
                                    <div class="team-name team-left"><?=$nomEquipe?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="match-footer">
                            <div class="actions">
                                <?php if (!empty($joueursSelectionnes)): ?>
                                    <div class="team-composition">
                                        <div class="formation">
                                            <h3 style="margin: -1px 0 -1px 0;">Formation</h3>
                                            <div class="field">
                                                <!-- Attaque -->
                                                <div class="line forward">
                                                    <div class="poste-container">
                                                        <div class="note">
                                                            <div><?= htmlspecialchars(str_repeat("★",$joueursParPoste['attaquants']['AG']['note'] ?? 0)) ?></div>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['attaquants']['AG']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AG']['nom'] ?? null) ?></div>
                                                        <div class="poste att">AG</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note">
                                                            <?= htmlspecialchars(str_repeat("★",$joueursParPoste['attaquants']['BU']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['attaquants']['BU']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['BU']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste att">BU</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['attaquants']['AD']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['attaquants']['AD']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['attaquants']['AD']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste att">AD</div>
                                                    </div>
                                                </div>

                                                <!-- Milieu -->
                                                <div class="line midfield">
                                                    <div class="poste-container">
                                                        <div class="note">
                                                            <?= htmlspecialchars(str_repeat("★",$joueursParPoste['milieux']['MCG']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['milieux']['MCG']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCG']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste mil">MCG</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['milieux']['MD']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['milieux']['MD']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MD']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste mil">MCD</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['milieux']['MCD']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['milieux']['MCD']['note'])) ?></div>
                                                        </div>
                                                        <div class="player"><?= htmlspecialchars($joueursParPoste['milieux']['MCD']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste mil">MCD</div>
                                                    </div>
                                                </div>

                                                <!-- Défense -->
                                                <div class="line defense">
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['defenseurs']['DG']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['defenseurs']['DG']['note'])) ?></div></div>
                                                        <div class="player DG"><?= htmlspecialchars($joueursParPoste['defenseurs']['DG']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste def">DG</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['defenseurs']['DCG']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['defenseurs']['DCG']['note'])) ?></div></div>
                                                        <div class="player DCG"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCG']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste def">DCG</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['defenseurs']['DCD']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['defenseurs']['DCD']['note'])) ?></div></div>
                                                        <div class="player DCD"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCD']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste def">DCD</div>
                                                    </div>
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars( str_repeat("★", $joueursParPoste['defenseurs']['DD']['note'] ?? 0)); ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['defenseurs']['DD']['note'])) ?></div></div>
                                                        <div class="player DD"><?= htmlspecialchars($joueursParPoste['defenseurs']['DD']['nom'] ?? 'N/A') ?></div>
                                                        <div class="poste def">DD</div>
                                                    </div>
                                                </div>

                                                <!-- Gardien -->
                                                <div class="line goal">
                                                    <div class="poste-container">
                                                        <div class="note"><?= htmlspecialchars(str_repeat("★",$joueursParPoste['gardiens']['GB']['note'] ?? 0)) ?>
                                                            <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$joueursParPoste['gardiens']['GB']['note'])) ?></div></div>
                                                        <div class="player">
                                                            <?= htmlspecialchars($joueursParPoste['gardiens']['GB']['nom'] ?? 'N/A') ?>
                                                        </div>
                                                        <div class="poste goal">GB</div>
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
                                                <div>
                                                    <div class="note"><?= htmlspecialchars(str_repeat("★",$remplacant['note'] ?? 0)) ?>
                                                        <div class="note-reste"><?= htmlspecialchars(str_repeat("★",5-$remplacant['note'])) ?></div>
                                                    </div>
                                                    <div class="player">
                                                        <?= htmlspecialchars($remplacant['nom'] ?? 'N/A') ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                <!-- Actions -->
                                    <a href="/football_manager/rencontres/feuille_de_rencontre?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Evaluations</a>
                                        <a href="/football_manager/rencontres/resultat?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Score</a>
                                    <a href="/football_manager/rencontres/supprimer?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">
                                        <i class="fas fa-trash-alt"></i> <!-- Icône de suppression -->
                                    </a>
                                    <?php else: ?>
                                    <span>MATCH ANNULÉ (aucun joueur sélectionné)</span>
                                    <a href="football_manager/rencontres/supprimer?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">
                                        <i class="fas fa-trash-alt" style="margin-top: 20px"></i> <!-- Icône de suppression -->
                                    </a>
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
                $joueursTitulaires = array_filter($joueursSelectionnes, function($joueur) {
                    return strpos($joueur['poste'], 'R') !== 0;
                });

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFutur = $matchDateTime > $currentDateTime;

                if ($isMatchFutur && count($joueursTitulaires) < 11) {
                    $controleurSelection->verifierEtSupprimerSelection($rencontre['id_rencontre']);
                }
                $joueursParPoste = getJoueursParPoste($joueursSelectionnes);
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
                            <?php if($rencontre['lieu'] == 'Domicile') :?>
                                <div class="team">
                                    <div class="team-name team-end"><?=$nomEquipe?></div>
                                    <span class="score" style="background-color: <?= $couleurScore ?>;"><?=$score?></span>
                                    <div class="team-name team-left"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></div>
                                </div>
                            <?php else : ?>
                                <div class="team">
                                    <div class="team-name team-end"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></div>
                                    <span class="score" style="background-color: <?= $couleurScore ?>;"><?=$score?></span>
                                    <div class="team-name team-left"><?=$nomEquipe?></div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="match-footer">
                            <?php if (!empty($joueursSelectionnes)): ?>

                                <div class="team-composition">
                                <div class="formation">
                                    <h3 style="margin: -1px 0 -1px 0;">Formation</h3>
                                    <div class="field">
                                        <!-- Attaque -->
                                        <div class="line forward">
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['attaquants']['AG']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste att">AG</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['attaquants']['BU']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste att">BU</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['attaquants']['AD']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste att">AD</div>
                                            </div>
                                        </div>

                                        <!-- Milieu -->
                                        <div class="line midfield">
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['milieux']['MCG']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste mil">MCG</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['milieux']['MD']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste mil">MD</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player player-a-venir"><?= htmlspecialchars($joueursParPoste['milieux']['MCD']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste mil">MCD</div>
                                            </div>
                                        </div>

                                        <!-- Défense -->
                                        <div class="line defense">
                                            <div class="poste-container">
                                                <div class="player DG player-a-venir"><?= htmlspecialchars($joueursParPoste['defenseurs']['DG']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste def">DG</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player DCG player-a-venir"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCG']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste def">DCG</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player DCD player-a-venir"><?= htmlspecialchars($joueursParPoste['defenseurs']['DCD']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste def">DCD</div>
                                            </div>
                                            <div class="poste-container">
                                                <div class="player DD player-a-venir"><?= htmlspecialchars($joueursParPoste['defenseurs']['DD']['nom'] ?? 'N/A') ?></div>
                                                <div class="poste def">DD</div>
                                            </div>
                                        </div>

                                        <!-- Gardien -->
                                        <div class="line goal">
                                            <div class="poste-container">
                                                <div class="player player-a-venir">
                                                        <?= htmlspecialchars($joueursParPoste['gardiens']['GB']['nom'] ?? 'N/A') ?>
                                                </div>
                                                <div class="poste goal">GB</div>
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
                                <a href="/football_manager/rencontres/feuille_de_rencontre?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Sélection</a>
                                <a href="/football_manager/rencontres/modifier?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">
                                    <i class="fas fa-edit"></i> <!-- Icône de modification -->
                                </a>
                                <a href="/football_manager/rencontres/supprimer?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">
                                    <i class="fas fa-trash-alt"></i> <!-- Icône de suppression -->
                                </a>
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
