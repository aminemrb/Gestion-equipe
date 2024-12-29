<?php
include __DIR__ . '/../../config.php'; // Include config.php
include __DIR__ . '/../Layouts/header.php'; // Include header.php

use App\Controleurs\UtilisateurControleur;

// Vérifier si l'utilisateur est connecté et récupérer ses informations
if (isset($_SESSION['utilisateur_id'])) {
    $utilisateurControleur = new UtilisateurControleur();
    $fullName = $utilisateurControleur->getNomPrenom();
} else {
    $fullName = "sur Football Manager";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/football_manager/public/assets/css/style.css">
</head>
<body>
<div class="welcome-container">
    <h1>Bienvenue <?= $fullName ?> !</h1>
    <p>Ceci est la page d'accueil de Football Manager. Profitez de la gestion de votre équipe !</p>
</div>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
