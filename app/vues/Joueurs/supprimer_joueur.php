<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

$controleurJoueur = new JoueurControleur();

$numero_licence = $_GET['numero_licence'] ?? null;

if ($numero_licence) {
    $controleurJoueur->supprimer_joueur($numero_licence);
} else {
    echo "Numéro de licence non fourni.";
    exit;
}
?>
    <main>
        <h1>Suppression du joueur</h1>
    </main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>