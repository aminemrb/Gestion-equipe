<?php
namespace App\Controleurs;

use App\Modeles\Selection;
use App\Modeles\Joueur;

class SelectionControleur {

    private $selectionModel;
    private $joueurModel;

    public function __construct() {
        $this->selectionModel = new Selection(); // Créer une instance du modèle Selection
        $this->joueurModel = new Joueur(); // Créer une instance du modèle Joueur
    }

    // Récupérer les joueurs sélectionnés pour une rencontre donnée
    public function getJoueursSelectionnes($id_rencontre) {
        try {
            return $this->selectionModel->getJoueursSelectionnes($id_rencontre);
        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération des joueurs sélectionnés : " . $e->getMessage());
            return [];
        }
    }

    // Traiter la sélection des joueurs
    public function traiterSelection() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rencontre = $_POST['id_rencontre'];
            $joueurs = $_POST['joueurs'] ?? [];

            // Récupérer les joueurs actuellement sélectionnés
            $joueurs_selectionnes = $this->getJoueursSelectionnes($id_rencontre);
            $selectionnes_ids = array_column($joueurs_selectionnes, 'numero_licence');

            // Supprimer les sélections existantes
            $this->selectionModel->supprimerSelection($id_rencontre);

            // Ajouter les nouvelles sélections et décrémenter les joueurs désélectionnés
            foreach ($joueurs as $numero_licence) {
                $this->selectionModel->ajouterSelection($id_rencontre, $numero_licence);
                if (!in_array($numero_licence, $selectionnes_ids)) {
                    $this->joueurModel->incrementerRencontresJouees($numero_licence);
                }
            }

            foreach ($selectionnes_ids as $numero_licence) {
                if (!in_array($numero_licence, $joueurs)) {
                    $this->joueurModel->decrementerRencontresJouees($numero_licence);
                }
            }

            echo "<p>La sélection des joueurs a été mise à jour avec succès.</p>";
        }
    }
}
?>