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

        // Utiliser sha1 pour vérifier le mot de passe
        if ($utilisateur && sha1($password) === $utilisateur['mot_de_passe']) {
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