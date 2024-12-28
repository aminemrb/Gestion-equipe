<?php
namespace App\Controleurs;

use App\Modeles\Selection;
use App\Modeles\Joueur;

class SelectionControleur {
    private $selectionModel;
    private $joueurModel;

    public function __construct() {
        $this->selectionModel = new Selection();
        $this->joueurModel = new Joueur();
    }

    public function getJoueursSelectionnes($id_rencontre) {
        try {
            return $this->selectionModel->getJoueursSelectionnes($id_rencontre);
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération des joueurs sélectionnés : " . $e->getMessage());
            return [];
        }
    }

    public function updatePostes($id_rencontre, $postes_postes) {
        try {
            foreach ($postes_postes as $numero_licence => $poste) {
                if (empty($poste)) {
                    $poste = null;  // Remplace la note par NULL
                }
                $this->selectionModel->updatePoste($id_rencontre, $numero_licence, $poste);
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de la mise à jour des postes : " . $e->getMessage());
        }
    }

    public function updateNotes($id_rencontre, $notes) {
        try {
            foreach ($notes as $id_joueur => $note) {
                    $this->selectionModel->updateNote($id_rencontre, $id_joueur, $note);
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'enregistrement des notes : " . $e->getMessage());
            throw new \Exception("Impossible d'enregistrer les notes.");
        }
    }

    public function getNotesByRencontre($id_rencontre) {
        return $this->selectionModel->getNotesByRencontre($id_rencontre);
    }

    public function getNbJoueursNotes($id_rencontre) {
        return $this->selectionModel->getNbJoueursNotes($id_rencontre);
    }

    public function validerSelection($id_rencontre, $postes_postes) {
        // Vérifier que tous les postes obligatoires sont remplis
        $postes_obligatoires = [
            "GB", "DG", "DCG", "DCD", "DD", "MD", "MCG", "MCD", "AD", "AG", "BU"
        ];
        foreach ($postes_obligatoires as $poste) {
            if (!in_array($poste, $postes_postes)) {
                throw new \Exception("Tous les postes obligatoires doivent être assignés.");
            }
        }

        // Mettre à jour la base de données
        $this->updatePostes($id_rencontre, $postes_postes);
    }


}
