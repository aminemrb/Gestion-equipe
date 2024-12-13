<?php
include __DIR__ . '/../../config.php'; // Include config.php
?>
<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>/vues/Accueil/accueil.php">Accueil</a></li>
        <li><a href="<?php echo BASE_URL; ?>/vues/Joueurs/liste_joueurs.php">Joueurs</a></li>
        <li><a href="<?php echo BASE_URL; ?>/vues/Rencontres/liste_rencontres.php">Rencontres</a></li>
        <li><a href="<?php echo BASE_URL; ?>/vues/Statistiques/stats.php">Statistiques</a></li>
        <li><a href="<?php echo BASE_URL; ?>/vues/Authentification/logout.php">Déconnexion</a></li>
    </ul>
</nav>