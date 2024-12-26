<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;

$rencontreControleur = new RencontreControleur();
$selectionControleur = new SelectionControleur();
$rencontres = $rencontreControleur->liste_rencontres();

// Fonction pour formater la date en français
function formatDate($date) {
    setlocale(LC_TIME, 'fr_FR.UTF-8'); // S'assurer que la locale est en français
    $dateObj = new DateTime($date);
    return strftime('%A %d %B %Y', $dateObj->getTimestamp()); // Format "Lundi 18 Novembre 2024"
}


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
    <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_rencontre.php" class="btn-ajouter">Ajouter une rencontre</a>

    <div class="rencontres-container">
        <!-- Colonne des matchs passés -->
        <div class="column">
            <h2>Matchs Passés</h2>
            <?php foreach ($rencontres as $rencontre): ?>
                <?php
                $joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueurs = empty($joueurs_selectionnes)
                    ? "Aucun joueur sélectionné"
                    : implode('<br>', array_map(fn($j) => htmlspecialchars($j['nom'] . ' ' . $j['prenom']), $joueurs_selectionnes));
                $resultat = $rencontre['resultat'] ?? 'N/A';

                // Vérification si le score est null ou non défini
                $score = (isset($rencontre['score_equipe'], $rencontre['score_adverse']) && $rencontre['score_equipe'] !== null && $rencontre['score_adverse'] !== null)
                    ? "{$rencontre['score_equipe']}-{$rencontre['score_adverse']}"
                    : null;

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFuture = $matchDateTime > $currentDateTime; // Vérifie si le match est à venir
                ?>
                <?php if (!$isMatchFuture): ?>
                    <div class="match-card">
                        <div class="match-header">
                            <div class="match-date-time">
                                <span class="match-date"><strong><?= formatDate($rencontre['date_rencontre']) ?></strong> à </span>
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
                                <span class="score"><?= $score ?? 'N/A' ?></span>
                                <span class="team-name"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></span>
                            </div>
                        </div>

                        <div class="match-footer">
                            <!-- Boutons d'actions -->
                            <div class="actions">
                                <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>"
                                   class="btn-action">
                                    Feuille de match
                                </a>

                                <!-- Si la sélection est faite et que le score n'est pas encore ajouté -->
                                <?php if (!empty($joueurs_selectionnes)): ?>
                                        <a href="<?= BASE_URL ?>/vues/Rencontres/ajouter_resultat.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>"
                                           class="btn-action">
                                            Scorer
                                        </a>
                                <?php else: ?>
                                    <span>Faire la sélection</span>
                                <?php endif; ?>

                              <!--  <a href="<?= BASE_URL ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Modifier</a>-->
                                <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?> "
                                   class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                            </div>

                            <div class="players-selected">
                                <strong>Joueurs Sélectionnés:</strong>
                                <div id="joueurs-selectionnes-<?= $rencontre['id_rencontre'] ?>"><?= $joueurs ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Colonne des matchs à venir -->
        <div class="column">
            <h2>Matchs à Venir</h2>
            <?php foreach ($rencontres as $rencontre): ?>
                <?php
                $joueurs_selectionnes = $selectionControleur->getJoueursSelectionnes($rencontre['id_rencontre']);
                $joueurs = empty($joueurs_selectionnes)
                    ? "Aucun joueur sélectionné"
                    : implode('<br>', array_map(fn($j) => htmlspecialchars($j['nom'] . ' ' . $j['prenom']), $joueurs_selectionnes));
                $resultat = $rencontre['resultat'] ?? 'N/A';

                // Vérification si le score est null ou non défini
                $score = (isset($rencontre['score_equipe'], $rencontre['score_adverse']) && $rencontre['score_equipe'] !== null && $rencontre['score_adverse'] !== null)
                    ? "{$rencontre['score_equipe']}-{$rencontre['score_adverse']}"
                    : null;

                $currentDateTime = new DateTime();
                $matchDateTime = new DateTime("{$rencontre['date_rencontre']} {$rencontre['heure_rencontre']}");
                $isMatchFuture = $matchDateTime > $currentDateTime; // Vérifie si le match est à venir
                ?>
                <?php if ($isMatchFuture): ?>
                    <div class="match-card">
                        <div class="match-header">
                            <div class="match-date-time">
                                <span class="match-date"><strong><?= formatDate($rencontre['date_rencontre']) ?></strong> à </span>
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
                                <span class="score"><?= $score ?? 'N/A' ?></span>
                                <span class="team-name"><?= htmlspecialchars($rencontre['equipe_adverse']) ?></span>
                            </div>
                        </div>

                        <div class="match-footer">
                            <!-- Boutons d'actions -->
                            <div class="actions">
                                <a href="<?= BASE_URL ?>/vues/Rencontres/feuille_rencontres.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>"
                                   class="btn-action">
                                    Feuille de match
                                </a>

                                <a href="<?= BASE_URL ?>/vues/Rencontres/modifier_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?>" class="btn-action">Modifier</a>
                                <a href="<?= BASE_URL ?>/vues/Rencontres/supprimer_rencontre.php?id_rencontre=<?= $rencontre['id_rencontre'] ?> "
                                   class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?');">Supprimer</a>
                            </div>

                            <div class="players-selected">
                                <strong>Joueurs Sélectionnés:</strong>
                                <div id="joueurs-selectionnes-<?= $rencontre['id_rencontre'] ?>"><?= $joueurs ?></div>
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
