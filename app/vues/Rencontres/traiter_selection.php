<?php
include __DIR__ . '/../Layouts/header.php';

use App\Controleurs\SelectionControleur;

// Créer une instance du contrôleur
$selectionControleur = new SelectionControleur();

// Traiter la sélection des joueurs
$selectionControleur->traiterSelection();

include __DIR__ . '/../Layouts/footer.php';
?>