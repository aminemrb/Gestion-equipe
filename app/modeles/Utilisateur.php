<?php

namespace App\Modeles;

use Config\Database;

class Utilisateur {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function trouverParEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getNomPrenom($id) {
        $stmt = $this->pdo->prepare("SELECT prenom, nom FROM utilisateur WHERE id_utilisateur = :id");
        $stmt->execute(['id_utilisateur' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>