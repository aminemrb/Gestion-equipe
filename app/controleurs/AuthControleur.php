<?php

require_once __DIR__ . '/../modeles/Utilisateur.php';

class AuthControleur {
    private $db;

    public function __construct() {
        $this->db = require_once __DIR__ . '/../../config/database.php'; // Accès à la base de données
    }

    public function login($email, $password) {
        $utilisateurModel = new Utilisateur($this->db);
        $utilisateur = $utilisateurModel->trouverParEmail($email);

        // Utiliser sha1 pour vérifier le mot de passe
        if ($utilisateur && sha1($password) === $utilisateur['mot_de_passe']) {
            session_start();
            $_SESSION['utilisateur_id'] = $utilisateur['id_utilisateur'];
            $_SESSION['email'] = $utilisateur['email'];
            header('Location: ../../vues/accueil.php');
            exit;
        } else {
            echo "Identifiants invalides.";
        }
    }
}
?>