<?php

class BaseControleur {
    public function verifierAuthentification() {
        session_start();
        if (!isset($_SESSION['utilisateur_id'])) {
            header('Location: /Authentification/login');
            exit;
        }
    }
}
