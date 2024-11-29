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
        <label for="numero_licence">Numero de licence :</label>
        <input type="text" id="numero_licence" name="numero_licence" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" required>

        <label for="taille">Taille (en mètres) :</label>
        <input type="number" id="taille" name="taille" step="0.01" min="1.00" max="2.50" required>

        <label for="poids">Poids (en kg) :</label>
        <input type="number" id="poids" name="poids" step="0.1" min="30" max="200" required>


        <label for="statut">Statut :</label>
        <select id="statut" name="statut" required>
            <option value="Actif">Actif</option>
            <option value="Blessé">Blessé</option>
            <option value="Suspendu">Suspendu</option>
            <option value="Absent">Absent</option>
        </select>

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
            <option value="Attaquant de pointe">Attaquant de pointe</option>
            <option value="Attaquant gauche">Attaquant gauche</option>
            <option value="Attaquant droit">Attaquant droit</option>
        </select>


        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire"></textarea>

        <button type="submit">Ajouter</button>
    </form>
</main>


<?php
// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
