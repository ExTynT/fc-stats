<?php
// Spustenie session 
session_start();

// Načítanie potrebných súborov
require_once '../_inc/functions.php';       // Súbor s pomocnými funkciami 
require_once '../_inc/NewsRepository.php';  // Súbor s triedou NewsRepository pre prácu s novinkami a komentármi
include_once 'Comment.php';                 // Súbor s triedou Comment pre prácu s komentármi

// Nastavenie hlavičky Content-Type na application/json
header('Content-Type: application/json');


 //Pomocná funkcia na odoslanie odpovede s chybovým hlásením vo formáte JSON

function sendErrorResponse($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// Kontrola existencie parametra id v GET požiadavke
if (!isset($_GET['id'])) {
    sendErrorResponse('Chýba ID komentára.'); // Ak chýba, odoslanie chybovej odpovede
}

// Overenie a filtrovanie ID komentára z GET parametrov
$commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$commentId) {
    sendErrorResponse('Neplatné ID komentára.'); // Ak je neplatné, odoslanie chybovej odpovede
}

// Nadviazanie spojenia s databázou
$conn = dbConnect();

// Kontrola úspešnosti nadviazania spojenia
if (!$conn) {
    sendErrorResponse('Zlyhalo pripojenie k databáze.'); // Ak nie je spojenie, odoslanie chybovej odpovede
}

// Vytvorenie inštancie triedy NewsRepository
$newsRepository = new NewsRepository($conn);

// Získanie komentára podľa ID
$comment = $newsRepository->getCommentById($commentId);

// Kontrola, či komentár existuje
if (!$comment) {
    sendErrorResponse('Komentár nebol nájdený.'); // Ak neexistuje, odoslanie chybovej odpovede
}

// Overenie, či je používateľ prihlásený a či má rolu admin
if (!isset($_COOKIE['loggedIn']) || ($_COOKIE['user_role'] !== 'admin')) { 
    sendErrorResponse('Nemáte oprávnenie na úpravu tohto komentára.'); // Ak nie je autorizovaný, odoslanie chybovej odpovede
}

// Ak všetky kontroly prešli úspešne, odoslanie údajov komentára vo formáte JSON
echo json_encode([
    'id' => $comment->id,       // ID komentára
    'content' => $comment->content, // Obsah
]);

// Uzatvorenie spojenia 
$conn->close();
