<?php
// Chargement des dépendances
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php'; // Chargement de la configuration
require_once __DIR__ . '/../../auth/auth.php'; // Chargement des fonctions d'authentification

if (basename($_SERVER['REQUEST_URI']) !== 'accueil') {
    verifierUtilisateurConnecte();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <link rel="stylesheet" href="/football_manager/public/assets/css/header.css"> <!-- Chemin absolu basé sur BASE_URL -->
    <title>Football Manager</title>
</head>
<body>
<header class="header-menu">
    <img src="/football_manager/public/assets/images/logo.png" alt="Logo" class="logo">

    <?php
    // Inclusion du menu en fonction de l'état de connexion
    if (isset($_SESSION['utilisateur_id'])) {
        include __DIR__ . '/menu.php'; // Menu pour les utilisateurs connectés
    } else {
        include __DIR__ . '/menu_deconnecter.php'; // Menu pour les utilisateurs déconnectés
    }
    ?>
</header>
</body>
</html>


