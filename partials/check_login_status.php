<?php

session_start();


require_once __DIR__ . 'functions.php';

// Overenie prihlásenia

$isLoggedIn = isset($_SESSION['user_id']);

// Nastavenie hlavičky Content-Type pre odpoveď
header('Content-Type: application/json');

// Vrátenie výsledku vo formáte JSON
// Funkcia json_encode() prevádza pole do reťazca vo formáte JSON

echo json_encode(['isLoggedIn' => $isLoggedIn]);
