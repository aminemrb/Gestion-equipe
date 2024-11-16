<?php
include __DIR__ . '/../../config.php'; // Include config.php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Controleurs\AuthControleur;

echo "Starting logout process..."; // Debugging statement

$auth = new AuthControleur();
$auth->logout();

echo "Logout process completed."; // Debugging statement
?>