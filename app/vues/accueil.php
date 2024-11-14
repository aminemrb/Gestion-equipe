<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../vues/Authentification/login.php');
    exit;
}
?>

<?php include __DIR__ . '/../vues/Layouts/header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
    <p>Ceci est la page d'accueil.</p>
</body>
</html>
<?php include __DIR__ . '/../vues/Layouts/footer.php'; ?>