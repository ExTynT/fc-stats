<?php


session_start();


require_once '../_inc/functions.php';        
require_once '../_inc/NewsRepository.php';  
require_once '../_inc/Comment.php';                 


// Týmto hovoríme prehliadaču, že odpoveď bude vo formáte JSON 
header('Content-Type: application/json');

// Overenie prihlásenia
if (!isset($_COOKIE['loggedIn'])) {
    
    echo json_encode(['error' => 'Pre zmazanie komentára musíte byť prihlásený.']);
    exit; 
}


$conn = dbConnect();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);


    if ($commentId) {
        // Vytvorenie inštancie triedy NewsRepository
        $newsRepository = new NewsRepository($conn);

        // Pokus o zmazanie komentára
        $deleteResult = $newsRepository->deleteComment($commentId);

        // Kontrola výsledku zmazania
        if ($deleteResult === 'deleted') {
            
            echo json_encode(['success' => 'Komentár bol úspešne zmazaný.']);
        } elseif ($deleteResult === 'unauthorized') {
            
            echo json_encode(['error' => 'Nemáte oprávnenie na zmazanie tohto komentára.']);
        } else {
            
            echo json_encode(['error' => 'Chyba pri mazání komentára.']);
        }
    } else {
        
        echo json_encode(['error' => 'Neplatné ID komentára.']);
    }
}


$conn->close();
