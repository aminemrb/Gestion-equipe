<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$router = require __DIR__ . '/../routes/routes.php';

// Récupère l'URI de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch la requête vers le contrôleur et l'action appropriés
$router->dispatch($uri);
?>