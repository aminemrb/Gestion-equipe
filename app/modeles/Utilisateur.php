<?php
require_once __DIR__ . '/../../config/database.php';

class Utilisateur {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function trouverParEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>