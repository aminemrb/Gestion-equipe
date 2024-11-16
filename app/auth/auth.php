<?php
// /app/auth/auth.php

session_start();

function verifierUtilisateurConnecte() {
    if (!isset($_SESSION['utilisateur_id'])) {
        header('Location: ' . BASE_URL . '/vues/Accueil/accueil.php');
        exit;
    }
}
?>