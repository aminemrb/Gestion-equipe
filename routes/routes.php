<?php

use App\Router;
use App\Controleurs\AuthControleur;
use App\Controleurs\JoueurControleur;
use App\Controleurs\RencontreControleur;
use App\Controleurs\SelectionControleur;
use App\Controleurs\StatsControleur;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialisation du routeur
$router = new Router();

// Routes pour Joueurs
$router->addRoute('/Joueurs/liste_joueurs', JoueurControleur::class, 'liste_joueurs');
$router->addRoute('/Joueurs/ajouter_joueur', JoueurControleur::class, 'ajouter_joueur');
$router->addRoute('/Joueurs/supprimer_joueur/{numero_licence}', JoueurControleur::class, 'supprimer_joueur');

// Routes pour Rencontres
$router->addRoute('/Rencontres/liste_rencontres', RencontreControleur::class, 'liste_rencontres');
$router->addRoute('/Rencontres/ajouter_rencontre', RencontreControleur::class, 'ajouter_rencontre');
$router->addRoute('/Rencontres/modifier_rencontre/([^/]+)', RencontreControleur::class, 'modifier_rencontre');
$router->addRoute('/Rencontres/supprimer_rencontre/([^/]+)', RencontreControleur::class, 'supprimer_rencontre');
$router->addRoute('/Rencontres/ajouter_resultat', RencontreControleur::class, 'ajouter_rencontre');

//Routes pour la sélection des joueurs
$router->addRoute('/Feuille_rencontres/feuille_rencontres', RencontreControleur::class, 'feuille_rencontres');
$router->addRoute('/Feuille_rencontres/formulaire_selection', SelectionControleur::class, 'formulaire_selection');
$router->addRoute('/Feuille_rencontres/traiter_selection', SelectionControleur::class, 'traiter_selection');

// Routes pour l'Authentification
$router->addRoute('/Accueil/accueil', AuthControleur::class, 'accueil');
$router->addRoute('/Authentification/login', AuthControleur::class, 'login');
$router->addRoute('/Authentification/logout', AuthControleur::class, 'logout');

// Routes pour Statistiques
$router->addRoute('/Statistiques', StatsControleur::class, 'stats');

// Retourne l'instance du routeur pour usage dans index.php
return $router;