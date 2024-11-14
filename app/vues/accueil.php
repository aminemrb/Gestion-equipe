<?php include __DIR__ . '/../vues/Layouts/header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'accueil</title>
</head>
<body>
<h1>Bienvenue 
        <?php 
        if (isset($_SESSION['utilisateur_id'])) {
            echo htmlspecialchars($_SESSION['email']);
        } else {
            echo "sur le site";
        }
        ?>!
    </h1>
    <p>Ceci est la page d'accueil.</p>
</body>
</html>
<?php include __DIR__ . '/../vues/Layouts/footer.php'; ?>