

<?php
include __DIR__ . '/../Layouts/header.php';
?>

<main>
    <h1>Suppression du joueur : Licence "<?=htmlspecialchars($_GET['numero_licence']) ?>"</h1>
</main>

<?php
use App\Controleurs\JoueurControleur;

$controleurJoueur = new JoueurControleur();

$numero_licence = $_GET['numero_licence'] ?? null;

if ($numero_licence) {
    $controleurJoueur->supprimer_joueur($numero_licence);
} else {
    echo "Numéro de licence non fourni.";
    exit;
}
