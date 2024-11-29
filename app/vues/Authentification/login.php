<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
include __DIR__ . '/../../config.php'; // Inclure le fichier config

use App\Controleurs\AuthControleur;

$auth = new AuthControleur();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Tentative de connexion
    if (!$auth->login($email, $password)) {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>
<h2>Connexion</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" action="">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
</body>
</html>
