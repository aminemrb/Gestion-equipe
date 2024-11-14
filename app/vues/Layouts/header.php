<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/public/assets/css/style.css"> <!-- Si le fichier CSS est dans assets -->
</head>
<body>
    <header>
        <h1>Bienvenue sur Football Manager</h1>
        <?php
        session_start();
        if (isset($_SESSION['utilisateur_id'])) {
            include __DIR__ . '/menu.php';
        } else {
            include __DIR__ . '/menu_deconnecter.php';
        }
        ?>
    </header>