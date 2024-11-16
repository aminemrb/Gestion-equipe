<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include __DIR__ . '/../../config.php'; // Include config.php
include __DIR__ . '/../../auth/auth.php'; // Include auth.php

if (basename($_SERVER['PHP_SELF']) !== 'accueil.php') {
    verifierUtilisateurConnecte();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/public/assets/css/style.css"> <!-- Si le fichier CSS est dans assets -->
</head>
<body>
<header>
    <?php
    if (isset($_SESSION['utilisateur_id'])) {
        include __DIR__ . '/menu.php';
    } else {
        include __DIR__ . '/menu_deconnecter.php';
    }
    ?>
</header>