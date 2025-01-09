<?php
require_once __DIR__ . '/vendor/autoload.php';

// Récupérer l'URI
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Si l'URI commence par le chemin du projet, le retirer
$projectBase = basename(__DIR__);
if (strpos($uri, $projectBase) === 0) {
    $uri = substr($uri, strlen($projectBase));
    $uri = trim($uri, '/');
}
// Routage des pages
switch ($uri) {
    case '':

    // gestion de l'accueil
    case 'accueil':
        require __DIR__ . '/app/vues/Accueil/accueil.php';
        break;

    // Gestion des rencontres
    case 'rencontres': //
        require __DIR__ . '/app/vues/Rencontres/liste_rencontres.php';
        break;
    case 'rencontres/ajouter': //
        require __DIR__ . '/app/vues/Rencontres/ajouter_rencontre.php';
        break;
    case 'rencontres/modifier': //
        require __DIR__ . '/app/vues/Rencontres/modifier_rencontre.php';
        break;
    case 'rencontres/supprimer': //
        require __DIR__ . '/app/vues/Rencontres/supprimer_rencontre.php';
        break;
    case 'rencontres/feuille_de_rencontre': //
        require __DIR__ . '/app/vues/Rencontres/feuille_rencontres.php';
        break;
    case 'rencontres/resultat': //
        require __DIR__ . '/app/vues/Rencontres/ajouter_resultat.php';
        break;

    // Gestions des joueurs
    case 'joueurs':
        require __DIR__ . '/app/vues/Joueurs/liste_joueurs.php';
        break;
    case 'joueurs/ajouter':
        require __DIR__ . '/app/vues/Joueurs/ajouter_joueur.php';
        break;
    case 'joueurs/modifier':
        require __DIR__ . '/app/vues/Joueurs/modifier_joueur.php';
        break;
    case 'joueurs/supprimer':
        require __DIR__ . '/app/vues/Joueurs/supprimer_joueur.php';
        break;

    // Gestion des statistiques
    case 'statistiques':
        require __DIR__ . '/app/vues/Statistiques/stats.php';
        break;

    // gestion de l'authentification
    case 'deconnexion':
        require __DIR__ . '/app/vues/Authentification/logout.php';
        break;
    case 'connexion':
        require __DIR__ . '/app/vues/Authentification/login.php';
        break;

    default:
        // Page 404 personnalisée
        http_response_code(404);
        echo "Oupss on dirait que vous vous êtes perdu en chemin...";
        break;
}
