<?php
// config/config.php

// Afficher ou masquer les erreurs selon le mode de débogage
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// URL de base de l'application, utile pour générer des liens dynamiques
define('BASE_URL', 'http://localhost/football_manager/public');

// Définir le fuseau horaire par défaut
date_default_timezone_set('Europe/Paris');

// Dossier pour les fichiers de logs
define('LOG_PATH', __DIR__ . '/../logs/app.log');

// Autres constantes pour l'application (ex. : nom de l'application)
define('APP_NAME', 'Football Manager');
define('APP_VERSION', '1.0.0');

