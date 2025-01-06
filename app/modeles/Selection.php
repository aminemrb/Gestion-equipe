<?php
namespace App\Modeles;

use PDO;
use App\Config\Database;

class Selection {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getJoueursSelectionnes($id_rencontre) {
        $stmt = $this->db->prepare("
            SELECT j.*, s.poste, s.note
            FROM joueur j
            JOIN selection s ON j.numero_licence = s.numero_licence
            WHERE s.id_rencontre = :id_rencontre
        ");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePoste($id_rencontre, $numero_licence, $poste) {
        // Vérifier si la sélection existe déjà dans la base
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM selection
        WHERE id_rencontre = :id_rencontre AND numero_licence = :numero_licence
    ");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->bindParam(':numero_licence', $numero_licence);
        $stmt->execute();

        $existe = $stmt->fetchColumn(); // Retourne 0 ou 1 (existence)

        if (!empty($poste)) {
            // Si un poste est spécifié
            if ($existe) {
                // Mise à jour du poste si l'entrée existe
                $stmt = $this->db->prepare("
                UPDATE selection
                SET poste = :poste
                WHERE id_rencontre = :id_rencontre AND numero_licence = :numero_licence
            ");
            } else {
                // Insérer un nouveau poste si l'entrée n'existe pas
                $stmt = $this->db->prepare("
                INSERT INTO selection (id_rencontre, numero_licence, poste)
                VALUES (:id_rencontre, :numero_licence, :poste)
            ");
            }

            $stmt->bindParam(':id_rencontre', $id_rencontre);
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->bindParam(':poste', $poste);
            $stmt->execute();

        } elseif ($existe) {
            // Supprimer l'entrée si elle existe mais qu'aucun poste n'est assigné
            $stmt = $this->db->prepare("
            DELETE FROM selection
            WHERE id_rencontre = :id_rencontre AND numero_licence = :numero_licence
        ");
            $stmt->bindParam(':id_rencontre', $id_rencontre);
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->execute();
        }
    }

    public function updateNote($id_rencontre, $id_joueur, $note) {
        if (empty($note)) {
            $note = null;  // Remplace la note par NULL
        }
        $stmt = $this->db->prepare("
        UPDATE selection
        SET note = :note
        WHERE id_rencontre = :id_rencontre AND numero_licence = :id_joueur
    ");
        $stmt->bindParam(':note', $note, PDO::PARAM_INT);
        $stmt->bindParam(':id_rencontre', $id_rencontre, PDO::PARAM_INT);
        $stmt->bindParam(':id_joueur', $id_joueur, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getNotesByRencontre($id_rencontre) {
        $stmt = $this->db->prepare("
        SELECT numero_licence, note
        FROM selection
        WHERE id_rencontre = :id_rencontre
    ");
        $stmt->bindParam(':id_rencontre', $id_rencontre);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formater sous forme de tableau clé-valeur [id_joueur => note]
        $notes = [];
        foreach ($result as $row) {
            $notes[$row['numero_licence']] = $row['note'];
        }
        return $notes;
    }

    public function getNbJoueursNotes($id_rencontre) {
        // Récupérer les notes pour la rencontre donnée
        $notes = $this->getNotesByRencontre($id_rencontre);

        // Filtrer et compter les notes non nulles
        $notesNonNulles = array_filter($notes, function($note) {
            return $note !== null; // Exclure les notes nulles
        });

        // Retourner le nombre de notes non nulles
        return count($notesNonNulles);
    }

    public function supprimerSelection($id_rencontre) {
        try {
            $stmt = $this->db->prepare("DELETE FROM selection WHERE id_rencontre = :id_rencontre");
            $stmt->bindParam(':id_rencontre', $id_rencontre, PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Exception $e) {
            error_log("Erreur lors de la suppression de la sélection : " . $e->getMessage());
        }
    }

}
