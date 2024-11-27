<?php

namespace Config;

use PDO;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Informations de connexion
        $host = 'mysql-footballmanager.alwaysdata.net';
        $port = '21';
        $dbname = 'footballmanager_v1';
        $username = '387083';
        $password = '$iutinfo'; // Mot de passe MySQL (vide dans ce cas)

        // DSN (Data Source Name)
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        // Création de l'instance PDO
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
?>
