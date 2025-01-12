<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\UtilisateurControleur;

$utilisateurControleur = new UtilisateurControleur();
$message = "";

// Récupérer les informations de l'utilisateur
$infosUtilisateur = $utilisateurControleur->getInfosUtilisateur();
$fullName = htmlspecialchars($infosUtilisateur['prenom']) . " " . htmlspecialchars($infosUtilisateur['nom']);

// Traiter la mise à jour si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $utilisateurControleur->modifierInfos();
    header("refresh:1;url=/football_manager/accueil");
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
            Bienvenue <?= isset($_SESSION['email']) ? htmlspecialchars($fullName) : "sur Football Management" ?>
        </h1>
        <?php if (isset($_SESSION['email'])): ?>
        <p>Vous entrainez actuellement <strong style="color: #4CAF50"><?= htmlspecialchars($infosUtilisateur['nom_equipe'])?></strong></p>
        <?php endif; ?>
        <p>Vous pouvez ici gérer vos informations personnelles et votre équipe pour améliorer votre expérience de jeu.</p>
    </div>

    <?php if (isset($_SESSION['email'])): ?>
    <!-- Formulaire pour modifier les informations -->
    <div class="update-form">
        <h2>Qui êtes-vous ?</h2>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($infosUtilisateur['prenom']) ?>" pattern="[A-Za-zÀ-ÿ]+" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($infosUtilisateur['nom']) ?>" pattern="[A-Za-zÀ-ÿ]+" required>
            </div>

            <div class="form-group">
                <label for="nom_equipe">Changer d'équipe</label>
                <input type="text" id="nom_equipe" name="nom_equipe" value="<?= htmlspecialchars($infosUtilisateur['nom_equipe']) ?>" pattern="[A-Za-zÀ-ÿ]+" required>
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <?php endif; ?>
</main>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
