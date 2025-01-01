<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;

// Créer une instance du contrôleur
$joueurControleur = new JoueurControleur();

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Appeler la méthode du contrôleur pour ajouter un joueur
    $joueurControleur->ajouter_joueur();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/formulaire.css">
    <title>Ajouter un joueur</title>
</head>
<body>
<div id="ajouter-style">
    <main>
        <h1>Ajouter un joueur</h1>
        <form method="POST" action="<?= BASE_URL ?>/vues/Joueurs/ajouter_joueur.php">

            <div class="form-group">
                <label for="numero_licence">Numéro de licence :</label>
                <input type="text" id="numero_licence" name="numero_licence" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" required>
            </div>

            <div class="form-group">
                <label for="taille">Taille (en mètres) :</label>
                <input type="number" id="taille" name="taille" step="0.01" min="1.00" max="2.50" required>
            </div>

            <div class="form-group">
                <label for="poids">Poids (en kg) :</label>
                <input type="number" id="poids" name="poids" step="0.1" min="30" max="200" required>
            </div>

            <div class="form-group">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut" required>
                    <option value="Actif">Actif</option>
                    <option value="Blessé">Blessé</option>
                    <option value="Suspendu">Suspendu</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="position_preferee">Position préférée :</label>
                <select id="position_preferee" name="position_preferee" required>
                    <option value="Gardien de but">Gardien de but</option>
                    <option value="Défenseur central">Défenseur central</option>
                    <option value="Défenseur latéral droit">Défenseur latéral droit</option>
                    <option value="Défenseur latéral gauche">Défenseur latéral gauche</option>
                    <option value="Milieu défensif">Milieu défensif</option>
                    <option value="Milieu central">Milieu central</option>
                    <option value="Milieu offensif">Milieu offensif</option>
                    <option value="Ailier droit">Ailier droit</option>
                    <option value="Ailier gauche">Ailier gauche</option>
                    <option value="Attaquant">Attaquant</option>
                </select>
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire :</label>
                <textarea id="commentaire" name="commentaire"></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Ajouter</button>
            </div>

        </form>
    </main>
</div>
</body>
</html>

<?php
// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
