<?php
// Inclure le header
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

// Créez une instance du contrôleur Rencontre
$rencontreControleur = new RencontreControleur();

// Traitez la soumission du formulaire via le contrôleur
$rencontreControleur->ajouter_rencontre(); // Traite la requête POST
?>

<main>
    <h1>Ajouter une rencontre</h1>
    <form method="POST" action="">
        <label for="equipe_adverse">Équipe Adverse :</label>
        <input type="text" id="equipe_adverse" name="equipe_adverse" required>

        <label for="date_rencontre">Date de la rencontre :</label>
        <input type="date" id="date_rencontre" name="date_rencontre" required>

        <label for="heure_rencontre">Heure de la rencontre :</label>
        <input type="time" id="heure_rencontre" name="heure_rencontre" required>

        <label for="lieu">Lieu :</label>
        <select id="lieu" name="lieu" required>
            <option value="Domicile">Domicile</option>
            <option value="Exterieur">Exterieur</option>
        </select>

        <label for="resultat">Résultat :</label>
        <input type="text" id="resultat" name="resultat" placeholder="Laissez vide si pas encore joué">

        <label for="resultat">Résultat :</label>
        <select id="resultat" name="resultat" required>
            <option value="Victoire">Victoire</option>
            <option value="Défaite">Défaite</option>
            <option value="Nul">Nul</option>
            <option value="<Rien>">Rien</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</main>

<?php
// Inclure le footer
include __DIR__ . '/../Layouts/footer.php';
?>
