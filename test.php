<?php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();
    echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}