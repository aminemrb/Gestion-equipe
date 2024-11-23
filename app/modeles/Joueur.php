<?php
namespace App\Modeles;

use PDO;
use Config\Database;

class Joueur {
    private $db;

    public function __construct() {
        // Connexion à la base de données via une instance de PDO
        $this->db = Database::getInstance();
    }

    // Récupérer tous les joueurs
    public function getAllJoueurs() {
        $stmt = $this->db->prepare("SELECT * FROM joueur ORDER BY nom, prenom");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner tous les résultats sous forme de tableau associatif
    }

    // Ajouter un joueur
    public function ajouterJoueur($nom, $prenom, $date_naissance, $taille = null, $poids = null, $statut = 'Actif', $position_preferee = null, $commentaire = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO joueur (nom, prenom, date_naissance, taille, poids, statut, position_preferee, commentaire) 
                                        VALUES (:nom, :prenom, :date_naissance, :taille, :poids, :statut, :position_preferee, :commentaire)");

            // Lier les paramètres aux valeurs
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':taille', $taille);
            $stmt->bindParam(':poids', $poids);
            $stmt->bindParam(':statut', $statut);
            $stmt->bindParam(':position_preferee', $position_preferee);
            $stmt->bindParam(':commentaire', $commentaire);

            // Exécuter la requête
            $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'insertion du joueur : " . $e->getMessage());
        }
    }

    // Modifier un joueur
    public function modifierJoueur($numero_licence, $nom, $prenom, $date_naissance, $taille = null, $poids = null, $statut = 'Actif', $position_preferee = null, $commentaire = null) {
        try {
            $stmt = $this->db->prepare("UPDATE joueur SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, 
                                        taille = :taille, poids = :poids, statut = :statut, position_preferee = :position_preferee, 
                                        commentaire = :commentaire WHERE numero_licence = :numero_licence");

            // Lier les paramètres aux valeurs
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':taille', $taille);
            $stmt->bindParam(':poids', $poids);
            $stmt->bindParam(':statut', $statut);
            $stmt->bindParam(':position_preferee', $position_preferee);
            $stmt->bindParam(':commentaire', $commentaire);

            // Exécuter la requête
            $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la mise à jour du joueur : " . $e->getMessage());
        }
    }

    // Supprimer un joueur
    public function supprimerJoueur($numero_licence) {
        try {
            $stmt = $this->db->prepare("DELETE FROM joueur WHERE numero_licence = :numero_licence");
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression du joueur : " . $e->getMessage());
        }
    }

    // Récupérer un joueur par son numéro de licence
    public function getJoueurByNumeroLicence($numero_licence) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM joueur WHERE numero_licence = :numero_licence");
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourner le joueur trouvé sous forme de tableau associatif
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération du joueur : " . $e->getMessage());
        }
    }
}
