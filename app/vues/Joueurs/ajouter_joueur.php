<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\JoueurControleur;

// Créez une instance du contrôleur Joueur
$joueurControleur = new JoueurControleur();

// Traitez la soumission du formulaire via le contrôleur
$joueurControleur->ajouter_joueur(); // Traite la requête POST

?>

<main>
    <h1>Ajouter un joueur</h1>
    <form method="POST" action="">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" required>

        <label for="taille">Taille (en mètres) :</label>
        <input type="number" id="taille" name="taille" min="1.00" max="2.50" step="0.01" required>


        <label for="poids">Poids (en kg) :</label>
        <input type="number" id="poids" name="poids" min="30" max="200" step="0.1" required>

        <label for="statut">Statut :</label>
        <select id="statut" name="statut" required>
            <option value="Actif">Actif</option>
            <option value="Blessé">Blessé</option>
            <option value="Suspendu">Suspendu</option>
            <option value="Absent">Absent</option>
        </select>

        <label for="position_preferee">Position préférée :</label>
        <input type="text" id="position_preferee" name="position_preferee" required>

        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire"></textarea>

        <button type="submit">Ajouter</button>
    </form>
</main>

<?php
// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
