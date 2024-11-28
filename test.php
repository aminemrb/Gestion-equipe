<?php

// Informations de connexion
$host = 'mysql-footballmanager.alwaysdata.net'; // Remplacez par votre hôte MySQL
$port = '3306'; // Port par défaut pour MySQL (vérifiez si AlwaysData utilise un autre port)
$dbname = 'footballmanager_v1'; // Nom de votre base de données
$username = '387083'; // Votre nom d'utilisateur MySQL
$password = '$iutinfo'; // Votre mot de passe MySQL

try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    // Création d'une instance PDO
    $pdo = new PDO($dsn, $username, $password);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie à la base de données !";

    // Test d'une requête simple
    $query = "SHOW TABLES";
    $stmt = $pdo->query($query);

    echo "<h2>Tables dans la base de données :</h2><ul>";
    while ($table = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $table['Tables_in_' . $dbname] . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    // Gestion des erreurs
    echo "Erreur lors de la connexion : " . $e->getMessage();
}
?>
