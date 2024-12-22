<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rencontreControleur->ajouter_rencontre();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/ajouter.css">
    <title>Ajouter une rencontre</title>
</head>
<body>
<main id="ajouter-style">
    <h1>Ajouter une rencontre</h1>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
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

        <button type="submit">Ajouter</button>
    </form>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
