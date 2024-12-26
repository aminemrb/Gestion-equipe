<?php
include __DIR__ . '/../../config.php'; // Include config.php
include __DIR__ . '/../Layouts/header.php'; // Include header.php

?>

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
            echo "sur Football Manager";
        }
        ?>!
    </h1>
    <p>Ceci est la page d'accueil.</p>
    </body>
    </html>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>