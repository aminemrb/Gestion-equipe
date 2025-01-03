<?php
namespace App\Modeles;

use PDO;
use App\Config\Database;
use DateTime;

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

    // Récupérer les joueurs actifs
    public function getJoueursActifs() {
        $stmt = $this->db->prepare("SELECT * FROM joueur WHERE statut = 'Actif' ORDER BY nom, prenom");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un joueur
    public function ajouterJoueur($numero_licence, $nom, $prenom, $date_naissance, $taille = null, $poids = null, $statut = 'Actif', $position_preferee = null, $commentaire = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO joueur (numero_licence, nom, prenom, date_naissance, taille, poids, statut, position_preferee, commentaire) 
                                        VALUES (:numero_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut, :position_preferee, :commentaire)");

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

    public function getStatistiquesJoueur($numero_licence) {
        try {
            // Requête SQL pour récupérer les statistiques de base
            $sql = "
        SELECT 
            -- Nombre de titularisations
            COUNT(CASE WHEN s.poste NOT IN ('R1', 'R2', 'R3', 'R4', 'R5') AND s.note IS NOT NULL THEN 1 END) AS titularisations,

            -- Nombre de remplacements
            COUNT(CASE WHEN s.poste LIKE 'R%' THEN 1 END) AS remplacements,

            -- Moyenne des notes
            AVG(CASE WHEN s.note IS NOT NULL THEN s.note END) AS moyenne_notes,

            -- Nombre de victoires
            COUNT(CASE WHEN r.resultat = 'Victoire' THEN 1 END) AS victoires,

            -- Nombre total de matchs
            COUNT(r.id_rencontre) AS total_matchs
        FROM selection s
        LEFT JOIN rencontre r ON s.id_rencontre = r.id_rencontre
        WHERE s.numero_licence = :numero_licence
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':numero_licence', $numero_licence, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculer le pourcentage de victoires
            $result['pourcentage_victoires'] = $result['total_matchs'] > 0
                ? round(($result['victoires'] / $result['total_matchs']) * 100, 2)
                : 0;

            // Requête pour les sélections consécutives
            $sql_consecutive = "
    SELECT CONCAT(r.date_rencontre, ' ', r.heure_rencontre) AS datetime_rencontre
    FROM selection s
    INNER JOIN rencontre r ON s.id_rencontre = r.id_rencontre
    WHERE s.numero_licence = :numero_licence
    AND r.resultat IS NOT NULL
    ORDER BY r.date_rencontre ASC, r.heure_rencontre ASC
";
            $stmt_consecutive = $this->db->prepare($sql_consecutive);
            $stmt_consecutive->bindParam(':numero_licence', $numero_licence, \PDO::PARAM_STR);
            $stmt_consecutive->execute();
            $dates = $stmt_consecutive->fetchAll(PDO::FETCH_COLUMN);

            // Calculer les sélections consécutives
            $consecutive_count = $this->calculerSelectionsConsecutives($dates);

            // Retourner les résultats avec la nouvelle statistique
            return [
                'titularisations' => $result['titularisations'] ?? 0,
                'remplacements' => $result['remplacements'] ?? 0,
                'moyenne_notes' => $result['moyenne_notes'] !== null ? round($result['moyenne_notes'], 2) : 0,
                'pourcentage_victoires' => $result['pourcentage_victoires'],
                'selections_consecutives' => $consecutive_count
            ];

        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques : " . $e->getMessage());
            return [
                'titularisations' => 0,
                'remplacements' => 0,
                'moyenne_notes' => 0,
                'pourcentage_victoires' => 0,
                'selections_consecutives' => 0
            ];
        }
    }
    private function calculerSelectionsConsecutives(array $dates_heures) {
        if (empty($dates_heures)) {
            return 0;
        }

        // Trier les dates et heures combinées
        sort($dates_heures);

        $max_consecutive = 1;
        $current_consecutive = 1;

        for ($i = 1; $i < count($dates_heures); $i++) {
            // Créer des objets DateTime pour comparer
            $prev_datetime = new DateTime($dates_heures[$i - 1]);
            $current_datetime = new DateTime($dates_heures[$i]);

            // Vérifie si les dates sont consécutives (intervalle de 7 jours ou moins)
            $interval = $prev_datetime->diff($current_datetime);

            // On compare ici l'intervalle en jours. Tu peux ajuster selon tes besoins.
            if ($interval->days <= 7) {
                $current_consecutive++;
            } else {
                $current_consecutive = 1;
            }

            $max_consecutive = max($max_consecutive, $current_consecutive);
        }

        return $max_consecutive;
    }



    public function estJoueurSelectionneEnCours($numero_licence) {
        $stmt = $this->db->prepare("
       SELECT COUNT(*)
        FROM selection s
        JOIN rencontre r ON s.id_rencontre = r.id_rencontre
        WHERE s.numero_licence = :numero_licence
        AND CONCAT(r.date_rencontre, ' ', r.heure_rencontre) < NOW()
        AND r.resultat IS NULL
    ");
        $stmt->bindParam(':numero_licence', $numero_licence, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

}
