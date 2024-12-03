<?php
namespace App\Modeles;

use PDO;
use Config\Database;

class Selection {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Récupérer les joueurs sélectionnés pour une rencontre donnée
    public function getJoueursSelectionnes($id_rencontre) {
        $stmt = $this->db->prepare("
            SELECT j.*
            FROM joueur j
            JOIN selection s ON j.numero_licence = s.numero_licence
            WHERE s.id_rencontre = :id_rencontre
        ");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer les sélections existantes pour une rencontre donnée
    public function supprimerSelection($id_rencontre) {
        $stmt = $this->db->prepare("DELETE FROM selection WHERE id_rencontre = :id_rencontre");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->execute();
    }

    // Ajouter une nouvelle sélection
    public function ajouterSelection($id_rencontre, $numero_licence) {
        $stmt = $this->db->prepare("INSERT INTO selection (id_rencontre, numero_licence) VALUES (:id_rencontre, :numero_licence)");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->bindParam(':numero_licence', $numero_licence);
        $stmt->execute();
    }
}
?>