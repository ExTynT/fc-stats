<?php

// Spustenie relácie
session_start();

// Načítanie potrebných súborov
require_once '../_inc/functions.php';       // Súbor s pomocnými funkciami 
require_once '../_inc/NewsRepository.php';  // Súbor s triedou NewsRepository pre prácu s novinkami a komentármi
require_once 'Comment.php';                 // Súbor s triedou Comment pre prácu s komentármi

// Nastavenie hlavičky Content-Type na application/json
// Týmto hovoríme prehliadaču, že odpoveď bude vo formáte JSON 
header('Content-Type: application/json');

// Overenie prihlásenia
if (!isset($_COOKIE['loggedIn'])) {
    // Ak nie je používateľ prihlásený, vráti chybové hlásenie vo formáte JSON
    echo json_encode(['error' => 'Pre zmazanie komentára musíte byť prihlásený.']);
    exit; // Ukončenie skriptu, pretože ďalšie akcie nie sú potrebné
}

// Vytvorenie spojenia s databázou
$conn = dbConnect();

// Spracovanie POST požiadavky
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Overenie a filtrovanie ID komentára z POST parametrov
    $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);

    // Ak je ID komentára platné
    if ($commentId) {
        // Vytvorenie inštancie triedy NewsRepository
        $newsRepository = new NewsRepository($conn);

        // Pokus o zmazanie komentára
        $deleteResult = $newsRepository->deleteComment($commentId);

        // Kontrola výsledku zmazania
        if ($deleteResult === 'deleted') {
            // Ak bol komentár úspešne zmazaný, vráti správu o úspechu vo formáte JSON
            echo json_encode(['success' => 'Komentár bol úspešne zmazaný.']);
        } elseif ($deleteResult === 'unauthorized') {
            // Ak používateľ nemá oprávnenie na zmazanie komentára, vráti chybové hlásenie vo formáte JSON
            echo json_encode(['error' => 'Nemáte oprávnenie na zmazanie tohto komentára.']);
        } else {
            // Ak nastala iná chyba pri mazání, vráti všeobecné chybové hlásenie vo formáte JSON
            echo json_encode(['error' => 'Chyba pri mazání komentára.']);
        }
    } else {
        // Ak ID komentára nie je platné, vráti chybové hlásenie vo formáte JSON
        echo json_encode(['error' => 'Neplatné ID komentára.']);
    }
}

// Uzatvorenie spojenia s databázou
$conn->close();
