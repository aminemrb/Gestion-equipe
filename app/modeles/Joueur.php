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

    public function getNombreTitularisationParJoueur($numero_licence) {
        try {
            // Préparer et exécuter la requête
            $stmt = $this->db->prepare("SELECT COUNT(*) AS nombre_notes 
            FROM selection 
            WHERE numero_licence = :numero_licence 
              AND poste NOT IN ('R1', 'R2', 'R3', 'R4', 'R5') 
              AND note IS NOT NULL
            ");
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération du nombre de notes : " . $e->getMessage());
        }
    }

    public function getNombreRemplacementsJoueur($numero_licence) {
        try {
            $sql = "SELECT COUNT(*) AS nombre_remplacements 
                FROM selection 
                WHERE numero_licence = :numero_licence 
                  AND poste LIKE 'R%'";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':numero_licence', $numero_licence);
            $stmt->execute();
            $result = $stmt->fetch();

            return $result['nombre_remplacements'] ?? 0;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des remplacements : " . $e->getMessage());
            return 0; // Retourne 0 en cas d'erreur
        }
    }
    public function getMoyenneNotesJoueur($numero_licence) {
        try {
            $sql = "SELECT AVG(note) AS moyenne_notes 
                FROM selection 
                WHERE numero_licence = :numero_licence 
                  AND note IS NOT NULL";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':numero_licence', $numero_licence, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            // Retourner la moyenne ou 0 si aucune note n'existe
            return $result['moyenne_notes'] !== null ? round($result['moyenne_notes'], 2) : 0;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de la moyenne des notes : " . $e->getMessage());
            return 0;
        }
    }

    public function getPourcentageVictoiresJoueur($numero_licence) {
        try {
            $sql = "
            SELECT 
                COUNT(CASE WHEN r.resultat = 'Victoire' THEN 1 END) AS victoires,
                COUNT(*) AS total_matchs
            FROM selection s
            INNER JOIN rencontre r ON s.id_rencontre = r.id_rencontre
            WHERE s.numero_licence = :numero_licence
            AND r.resultat IS NOT NULL; -- Exclure les matchs sans résultat
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':numero_licence', $numero_licence, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            // Calculer le pourcentage de victoires
            if ($result['total_matchs'] > 0) {
                return round(($result['victoires'] / $result['total_matchs']) * 100, 2);
            } else {
                return 0; // Aucun match joué
            }
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du pourcentage de victoires : " . $e->getMessage());
            return 0;
        }
    }

    public function getJoueursParPoste($id_rencontre, $poste) {
        try {
            // Définir les postes spécifiques correspondant à chaque poste générique
            $postesMapping = [
                'gardiens'    => ['GB'],                     // Gardiens
                'defenseurs'  => ['DD', 'DG', 'DCG', 'DCD'], // Défenseurs
                'milieux'     => ['MD', 'MCG', 'MCD'],       // Milieux
                'attaquants'  => ['AD', 'AG', 'BU'],         // Attaquants
                'remplacants' => ['R1', 'R2', 'R3', 'R4', 'R5'] // Remplaçants
            ];

            // Vérifie si le poste passé est valide
            if (!array_key_exists($poste, $postesMapping)) {
                throw new Exception("Poste non valide : " . htmlspecialchars($poste));
            }

            // Récupérer les postes spécifiques correspondant au poste générique
            $postesSpecifiques = $postesMapping[$poste];
            $placeholders = implode(',', array_fill(0, count($postesSpecifiques), '?'));

            // Préparer et exécuter la requête
            $stmt = $this->db->prepare("
            SELECT j.*
            FROM selection s
            INNER JOIN joueur j ON s.numero_licence = j.numero_licence
            WHERE s.id_rencontre = ?
            AND j.poste IN ($placeholders)
        ");

            // Associer les paramètres dynamiquement
            $params = array_merge([$id_rencontre], $postesSpecifiques);
            $stmt->execute($params);

            // Retourner les résultats
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            error_log("Erreur lors de la récupération des joueurs personnalisés : " . $e->getMessage());
            return [];
        }
    }



}
