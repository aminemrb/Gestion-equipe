<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/config.php';

header('Location: ' . BASE_URL . '/vues/Accueil/accueil.php');
exit;