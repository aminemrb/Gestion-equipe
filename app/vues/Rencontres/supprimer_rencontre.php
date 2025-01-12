<?php
include __DIR__ . '/../Layouts/header.php';
use App\Controleurs\RencontreControleur;

$rencontreControleur = new RencontreControleur();

$id_rencontre = $_GET['id_rencontre'] ?? null;

if ($id_rencontre) {
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
