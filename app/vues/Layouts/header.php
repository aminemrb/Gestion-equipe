<?php
// Chargement des dépendances
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../auth/auth.php';

if (basename($_SERVER['REQUEST_URI']) !== 'accueil') {
    verifierUtilisateurConnecte();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/football_manager/public/assets/css/header.css">
    <title>Football Manager</title>
</head>
<body>
<header class="header-menu">
    <img src="/football_manager/public/assets/images/logo.png" alt="Logo" class="logo">

    <?php
    // Inclusion du menu en fonction de l'état de connexion
    if (isset($_SESSION['utilisateur_id'])) {
        include __DIR__ . '/menu.php';
    } else {
        include __DIR__ . '/menu_deconnecter.php';
    }
    ?>
</header>
</body>
</html>


