<?php

namespace App\Controleurs;

use App\Modeles\Utilisateur;
use Config\Database;

class AuthControleur {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // Accès à la base de données
    }

    public function login($email, $password) {
        $utilisateurModel = new Utilisateur($this->db);
        $utilisateur = $utilisateurModel->trouverParEmail($email);

        if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
            session_start();
            $_SESSION['utilisateur_id'] = $utilisateur['id_utilisateur'];
            $_SESSION['email'] = $utilisateur['email'];
            header('Location: ' . BASE_URL . '/vues/Accueil/accueil.php');
            exit;
        } else {
            echo "Identifiants invalides.";
        }
    }



    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/vues/Accueil/accueil.php');
        exit;
    }
}
?>