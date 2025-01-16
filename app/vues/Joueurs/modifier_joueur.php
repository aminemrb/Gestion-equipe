<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;

$joueurControleur = new JoueurControleur();

// Vérifier si un numéro de licence est passé dans l'URL
$numero_licence = $_GET['numero_licence'] ?? null;
if (!$numero_licence) {
    echo "Numéro de licence manquant.";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}

// Vérifier si le joueur est sélectionné pour un match à venir
$estSelectionnePourMatchAVenir = $joueurControleur->estJoueurSelectionnePourMatchAVenir($numero_licence);

// Récupérer les informations du joueur
$joueur = $joueurControleur->modifier_joueur($numero_licence);

if (!$joueur) {
    echo "Aucun joueur trouvé pour ce numéro de licence.";
    include __DIR__ . '/../Layouts/footer.php';
    exit;
}
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/football_manager/public/assets/css/formulaire.css">
        <title>Modifier un joueur</title>
    </head>
    <body>
    <div>
        <main>
            <h1>Modifier un joueur</h1>
            <form method="POST" action="/football_manager/app/vues/Joueurs/modifier_joueur.php?numero_licence=<?= htmlspecialchars($numero_licence) ?>">

                <div class="form-group">
                    <label for="numero_licence">Numéro de licence :</label>
                    <input type="text" id="numero_licence" name="numero_licence" value="<?= htmlspecialchars($joueur['numero_licence']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($joueur['nom']) ?>" pattern="[A-Za-zÀ-ÿ]+" required>
                </div>

                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($joueur['prenom']) ?>" pattern="[A-Za-zÀ-ÿ]+" required>
                </div>

                <div class="form-group">
                    <label for="date_naissance">Date de naissance :</label>
                    <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($joueur['date_naissance']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="taille">Taille (en mètres) :</label>
                    <input type="number" id="taille" name="taille" step="0.01" min="1.00" max="2.50" value="<?= htmlspecialchars($joueur['taille']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="poids">Poids (en kg) :</label>
                    <input type="number" id="poids" name="poids" step="0.1" min="15" max="300" value="<?= htmlspecialchars($joueur['poids']) ?>" required>
                </div>

                <?php if (!$estSelectionnePourMatchAVenir): ?>
                    <div class="form-group">
                        <label for="statut">Statut :</label>
                        <select id="statut" name="statut" required>
                            <option value="Actif" <?= ($joueur['statut'] == 'Actif') ? 'selected' : '' ?>>Actif</option>
                            <option value="Blessé" <?= ($joueur['statut'] == 'Blessé') ? 'selected' : '' ?>>Blessé</option>
                            <option value="Suspendu" <?= ($joueur['statut'] == 'Suspendu') ? 'selected' : '' ?>>Suspendu</option>
                            <option value="Absent" <?= ($joueur['statut'] == 'Absent') ? 'selected' : '' ?>>Absent</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="position_preferee">Position préférée :</label>
                    <input type="text" id="position_preferee" name="position_preferee" value="<?= htmlspecialchars($joueur['position_preferee']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="commentaire">Commentaire :</label>
                    <textarea id="commentaire" name="commentaire"><?= htmlspecialchars($joueur['commentaire']) ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit">Modifier</button>
                </div>

            </form>
        </main>
    </div>
    </body>
    </html>

<?php
include __DIR__ . '/../Layouts/footer.php';
?>