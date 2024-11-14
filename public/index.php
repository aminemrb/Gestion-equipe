<?php
session_start();
require_once '../routes/routes.php';

$router = require '../routes/routes.php';

// Récupère l'URI de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch la requête vers le contrôleur et l'action appropriés
$router->dispatch($uri);
?>