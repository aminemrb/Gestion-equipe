<?php
require_once '../app/Router.php';
// Initialisation du routeur
$router = new Router(); // Assurez-vous que la classe Router est correctement incluse ou autoloadée

//Routes pour Joueurs
$router->addRoute('/Joueurs/liste_joueurs', 'JoueurControleur', 'liste_joueurs');
$router->addRoute('/Joueurs/ajouter_joueur', 'JoueurControleur', 'ajouter_joueur');
$router->addRoute('/Joueurs/modifier_joueur/{id}', 'JoueurControleur', 'modifier_joueur');

//Routes pour Rencontres
$router->addRoute('/Rencontres/liste_rencontres', 'RencontreControleur', 'liste_rencontres');
$router->addRoute('/Rencontres/ajouter_rencontre/{id}', 'RencontreControleur', 'ajouter_rencontre');
$router->addRoute('/Rencontres/modifier_rencontre/{id}', 'RencontreControleur', 'modifier_rencontre');

//Routes pour l'Authentification
$router->addRoute('/Authentification/login', 'AuthControleur', 'login');

// Routes pour Feuille de Match
$router->addRoute('/Feuille_rencontres/liste_rencontres', 'FeuilleDeRencontreControleur', 'liste_rencontres');
$router->addRoute('/Feuille_rencontres/formulaire_selection', 'FeuilleDeRencontreControleur', 'formulaire_selection');

// Routes pour Statistiques
$router->addRoute('/Stats', 'StatsControleur', 'stats');

// Retourne l'instance du routeur pour usage dans index.php
return $router;