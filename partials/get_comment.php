<?php

session_start();


require_once '../_inc/functions.php';       
require_once '../_inc/NewsRepository.php';  
include_once '../_inc/Comment.php';                 

// Nastavenie hlavičky Content-Type na application/json
header('Content-Type: application/json');


 //Pomocná funkcia na odoslanie odpovede s chybovým hlásením vo formáte JSON

function sendErrorResponse($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// Kontrola existencie parametra id v GET požiadavke
if (!isset($_GET['id'])) {
    sendErrorResponse('Chýba ID komentára.'); 
}

// Overenie a filtrovanie ID komentára z GET parametrov
$commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$commentId) {
    sendErrorResponse('Neplatné ID komentára.'); 
}


$conn = dbConnect();


if (!$conn) {
    sendErrorResponse('Zlyhalo pripojenie k databáze.'); 
}

// Vytvorenie inštancie triedy NewsRepository
$newsRepository = new NewsRepository($conn);

// Získanie komentára podľa ID
$comment = $newsRepository->getCommentById($commentId);

if (!$comment) {
    sendErrorResponse('Komentár nebol nájdený.'); 
}

// Overenie, či je používateľ prihlásený a či má rolu admin
if (!isset($_COOKIE['loggedIn']) || ($_COOKIE['user_role'] !== 'admin')) { 
    sendErrorResponse('Nemáte oprávnenie na úpravu tohto komentára.'); 
}

// Ak všetky kontroly prešli úspešne, odoslanie údajov komentára vo formáte JSON
echo json_encode([
    'id' => $comment->id,       
    'content' => $comment->content, 
]);


$conn->close();
