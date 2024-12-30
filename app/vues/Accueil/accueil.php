<?php
include __DIR__ . '/../../config.php';
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\UtilisateurControleur;

// Créer une instance du contrôleur
$utilisateurControleur = new UtilisateurControleur();
$message = "";

// Récupérer les informations de l'utilisateur
$infosUtilisateur = $utilisateurControleur->getInfosUtilisateur();
$fullName = htmlspecialchars($infosUtilisateur['prenom']) . " " . htmlspecialchars($infosUtilisateur['nom']);

// Traiter la mise à jour si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $utilisateurControleur->modifierInfos();
    header("refresh:1;url=accueil.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="/football_manager/public/assets/css/accueil.css">
</head>
<body>
<main>
    <div class="welcome-container">
        <h1>
            Bienvenue <?= isset($_SESSION['email']) ? htmlspecialchars($fullName) : "Sur Football Manager" ?>
        </h1>
        <?php if (isset($_SESSION['email'])): ?>
        <p>Nom de votre équipe : <?= htmlspecialchars($infosUtilisateur['nom_equipe']) ?></p>
        <?php endif; ?>
        <p>Vous pouvez ici gérer vos informations personnelles et votre équipe pour améliorer votre expérience de jeu.</p>
    </div>

    <?php if (isset($_SESSION['email'])): ?>
    <!-- Formulaire pour modifier les informations -->
    <div class="update-form">
        <h2>Modifier vos informations</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($infosUtilisateur['prenom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($infosUtilisateur['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nom_equipe">Nom de l'équipe :</label>
                <input type="text" id="nom_equipe" name="nom_equipe" value="<?= htmlspecialchars($infosUtilisateur['nom_equipe']) ?>" required>
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <?php endif; ?>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
