<?php
namespace App\Controleurs;

use App\Modeles\Utilisateur;

class UtilisateurControleur {

    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new Utilisateur(); // Créer une instance du modèle Rencontre
    }

    public function getNomPrenom(){
        $userId = $_SESSION['id_utilisateur'];
        $user = $this->utilisateurModel->getNomPrenom($userId);
        $prenom = htmlspecialchars($user['prenom']);
        $nom = htmlspecialchars($user['nom']);
        $fullName = "$prenom $nom";
        return $fullName;
    }


}
