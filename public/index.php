<?php

// Charger les configurations de base
require_once '../config/config.php';
require_once '../app/Router.php';

// Initialiser le routeur et charger les routes
$router = require_once '../routes/routes.php';

// Dispatcher la route actuelle
$router->dispatch();


