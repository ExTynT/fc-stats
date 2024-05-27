<?php
// Spustenie session
session_start();

// Načítanie súboru functions.php z rovnakého adresára ako tento skript
require_once __DIR__ . 'functions.php';

// Overenie prihlásenia
// Kontroluje, či je v session nastavená premenná 'user_id', čo indikuje, že používateľ je prihlásený
$isLoggedIn = isset($_SESSION['user_id']);

// Nastavenie hlavičky Content-Type pre odpoveď
// Informuje klienta (prehliadač), že odpoveď bude vo formáte JSON 
header('Content-Type: application/json');

// Vrátenie výsledku vo formáte JSON
// Funkcia json_encode() prevádza pole do reťazca vo formáte JSON
// Pole obsahuje jediný prvok: 'isLoggedIn' s hodnotou true/false podľa toho, či je používateľ prihlásený alebo nie
echo json_encode(['isLoggedIn' => $isLoggedIn]);
