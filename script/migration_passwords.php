<?php
require_once __DIR__ . '/../app/config/database.php'; // Inclure votre fichier de configuration

try {
    $pdo = App\Config\Database::getInstance();

    // Récupérer tous les utilisateurs
    $query = $pdo->query("SELECT id_utilisateur, mot_de_passe FROM utilisateur");
    $utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utilisateurs as $utilisateur) {
        $id = $utilisateur['id_utilisateur'];
        $passwordOld = $utilisateur['mot_de_passe'];

        // Générer un nouveau mot de passe hashé
        $passwordHash = password_hash($passwordOld, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe dans la base
        $updateQuery = $pdo->prepare("UPDATE utilisateur SET mot_de_passe = :password WHERE id_utilisateur = :id");
        $updateQuery->execute([
            ':password' => $passwordHash,
            ':id' => $id,
        ]);
    }

    echo "Migration des mots de passe réussie.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
