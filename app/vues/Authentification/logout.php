<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Controleurs\AuthControleur;


$auth = new AuthControleur();
$auth->logout();

