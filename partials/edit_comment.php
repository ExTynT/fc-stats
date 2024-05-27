<?php
// Spustenie session 
session_start();

// Načítanie potrebných súborov
require_once '../_inc/functions.php';       
require_once '../_inc/NewsRepository.php';  
require_once 'Comment.php';                 

// Nastavenie hlavičky pre odpoveď vo formáte JSON
header('Content-Type: application/json');

// Spracovanie HTTP požiadavky
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Overenie a vyčistenie vstupných údajov z formulára (POST)
    $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmEdit = filter_input(INPUT_POST, 'confirm_edit', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE); // Voliteľný parameter pre potvrdenie úpravy

    // Kontrola, či sú ID komentára a nový obsah zadané
    if ($commentId && $content) {
        // Nadviazanie spojenia s databázou
        $conn = dbConnect();

        // Kontrola úspešnosti nadviazania spojenia
        if (!$conn) {
            // Ak nie je spojenie, odoslanie chybovej odpovede a ukončenie skriptu
            sendErrorResponse('Database connection failed.');
        }

        // Vytvorenie inštancie triedy NewsRepository
        $newsRepository = new NewsRepository($conn);

        // Získanie komentára podľa ID
        $comment = $newsRepository->getCommentById($commentId);

        // Kontrola, či komentár existuje
        if ($comment) {
            // Získanie používateľského mena z cookie
            $usernameFromCookie = $_COOKIE['username'];

            // Overenie, či má používateľ právo upraviť komentár (je autorom alebo adminom)
            $isAuthorized = $comment->username === $usernameFromCookie || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

            // Ak je používateľ autorizovaný
            if ($isAuthorized) {
                // Ak je nastavené potvrdenie úpravy, pokračujeme s úpravou
                if ($confirmEdit) {
                    // Aktualizácia komentára v databáze
                    $updateResult = $newsRepository->updateComment($commentId, $content);

                    // Kontrola úspešnosti aktualizácie
                    if ($updateResult) {
                        // Ak áno, získanie aktualizovaného komentára a odoslanie úspešnej odpovede vo formáte JSON
                        $updatedComment = $newsRepository->getCommentById($commentId);
                        echo json_encode([
                            'success' => true,
                            'comment' => [
                                'id' => $updatedComment->id,
                                'username' => $updatedComment->username,
                                'content' => $updatedComment->content,
                                'created_at' => $updatedComment->created_at,
                            ],
                        ]);
                    } else {
                        // Ak nie, odoslanie chybovej odpovede
                        sendErrorResponse('Error updating comment.');
                    }
                } else {
                    // Ak potvrdenie úpravy nie je nastavené, odoslanie odpovede vyžadujúcej potvrdenie
                    echo json_encode(['require_confirmation' => true]);
                    exit;
                }
            } else {
                // Ak používateľ nie je autorizovaný, odoslanie chybovej odpovede
                sendErrorResponse('You are not authorized to edit this comment.');
            }
        } else {
            // Ak komentár neexistuje, odoslanie chybovej odpovede
            sendErrorResponse('Comment not found.');
        }
        
        // Uzatvorenie spojenia s databázou
        $conn->close(); 
    } else {
        // Ak ID komentára alebo obsah nie sú platné, odoslanie chybovej odpovede
        sendErrorResponse('Invalid comment ID or content.');
    }
} else {
    // Spracovanie GET požiadavky (načítanie komentára na úpravu)
    if (isset($_GET['id'])) {
        // Získanie ID komentára z GET parametrov
        $commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
        // Ak je ID komentára platné
        if ($commentId) {
            // Nadviazanie spojenia s databázou
            $conn = dbConnect();
            if (!$conn) {
                // Ak nie je spojenie, odoslanie chybovej odpovede a ukončenie skriptu
                sendErrorResponse('Database connection failed.');
            }
    
            // Vytvorenie inštancie triedy NewsRepository
            $newsRepository = new NewsRepository($conn);
            $comment = $newsRepository->getCommentById($commentId);
    
            // Kontrola, či komentár existuje
            if ($comment) {
                // Kontrola prihlásenia používateľa a overenie, či je autorom komentára
                if (isset($_COOKIE['loggedIn'])) {
                    $usernameFromCookie = $_COOKIE['username'];
                    $userIdFromCookie = $newsRepository->getUserIdByUsername($usernameFromCookie);
    
                    // Overenie oprávnenia na úpravu (autor alebo admin)
                    if (($comment->user_id == $userIdFromCookie && $userIdFromCookie !== null) || 
                        (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')) {
                        // Odoslanie údajov o komentári vo formáte JSON
                        echo json_encode([
                            'id' => $comment->id,
                            'content' => $comment->content,
                        ]);
                    } else {
                        // Ak používateľ nie je autorizovaný, odoslanie chybovej odpovede
                        echo json_encode(['error' => 'You are not authorized to edit this comment.']);
                    }
                } else {
                    // Ak používateľ nie je prihlásený, odoslanie chybovej odpovede
                    echo json_encode(['error' => 'You must be logged in to edit this comment.']);
                }
            } else {
                // Ak komentár neexistuje, odoslanie chybovej odpovede
                echo json_encode(['error' => 'Comment not found.']);
            }
    
            // Uzatvorenie spojenia s databázou
            $conn->close(); 
        } else {
            // Ak ID komentára nie je platné, odoslanie chybovej odpovede
            echo json_encode(['error' => 'Invalid comment ID.']);
        }
    }
}