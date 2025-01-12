<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises
    $equipe_adverse = $_POST['equipe_adverse'];
    $date_rencontre = $_POST['date_rencontre'];
    $heure_rencontre = $_POST['heure_rencontre'];
    $lieu = $_POST['lieu'];

    // Obtenir la date et l'heure actuelles
    $currentDateTime = new DateTime(); // Maintenant
    $dateTimeRendezvous = new DateTime("$date_rencontre $heure_rencontre");

    // Validation combinée
    if ($dateTimeRendezvous <= $currentDateTime) {
        echo "<p style='color: red;'>Erreur : La date et l'heure de la rencontre doivent être supérieures à la date et l'heure actuelles.</p>";
    } else {
        $rencontreControleur->ajouter_rencontre();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/formulaire.css">
    <title>Ajouter une rencontre</title>
</head>
<body>
<main>
    <h1>Ajouter une rencontre</h1>
    <form method="POST" action="">
        <label for="equipe_adverse">Équipe Adverse :</label>
        <input type="text" id="equipe_adverse" name="equipe_adverse" pattern="[A-Za-zÀ-ÿ]+" required>

        <label for="date_rencontre">Date de la rencontre :</label>
        <?php
        $minDate = date("Y-m-d");
        ?>
        <input type="date" id="date_rencontre" name="date_rencontre" required min="<?= $minDate ?>">

        <label for="heure_rencontre">Heure de la rencontre :</label>
        <input type="time" id="heure_rencontre" name="heure_rencontre" required>

        <label for="lieu">Lieu :</label>
        <select id="lieu" name="lieu" required>
            <option value="Domicile">Domicile</option>
            <option value="Exterieur">Exterieur</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</main>
</body>
</html>


<?php include __DIR__ . '/../Layouts/footer.php'; ?>
