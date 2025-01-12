
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php  if (isset($_SESSION['utilisateur_id'])) : ?>
<footer class="footer-menu">
        <?php
            include __DIR__ . '/menu.php'; // Menu pour les utilisateurs connectés
        ?>
        <p>&copy; <?= date('Y'); ?> Football Management. Gestion de votre équipe.</p>

</footer>
<?php endif; ?>
</body>
</html>
