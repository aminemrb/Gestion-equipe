<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\JoueurControleur;

// Instantiate the controller
$joueurControleur = new JoueurControleur();

// Get the numero_licence from the request (e.g., URL parameter)
$numero_licence = $_GET['numero_licence'] ?? null;

if ($numero_licence) {
    // Call the method to delete the player
    $joueurControleur->supprimer_joueur($numero_licence);
} else {
    echo "Numéro de licence non fourni.";
    exit;
}
?>
    <main>
        <h1>Suppression du joueur</h1>
        <p>Le joueur a été supprimé avec succès.</p>
    </main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>