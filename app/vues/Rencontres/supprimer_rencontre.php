<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

// Instantiate the controller
$rencontreControleur = new RencontreControleur();

// Get the rencontre ID from the request (e.g., URL parameter)
$id_rencontre = $_GET['id_rencontre'] ?? null;

if ($id_rencontre) {
    // Call the method to delete the rencontre
    $rencontreControleur->supprimer_rencontre($id_rencontre);
} else {
    echo "ID de la rencontre non fourni.";
    exit;
}


?>

<main>
    <h1>Suppression de la rencontre</h1>
    <p>La rencontre a été supprimée avec succès.</p>
</main>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
