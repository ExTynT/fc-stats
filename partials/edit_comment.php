<?php

session_start();

require_once '../_inc/functions.php';       
require_once '../_inc/NewsRepository.php';  
require_once '../_inc/Comment.php';                 

// Nastavenie hlavičky Content-Type na application/json
header('Content-Type: application/json');

// Funkcia na odoslanie chybovej odpovede vo formáte JSON
function sendErrorResponse($message) {
    echo json_encode(['error' => $message]);
    exit;
}

// Spracovanie HTTP požiadavky
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmEdit = filter_input(INPUT_POST, 'confirm_edit', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    // Kontrola, či sú ID komentára a nový obsah zadané
    if ($commentId && $content) {
        
        $conn = dbConnect();

        if (!$conn) {
            sendErrorResponse('Database connection failed.');
        }

        $newsRepository = new NewsRepository($conn);
        $comment = $newsRepository->getCommentById($commentId);

        // Kontrola existencie komentára
        if ($comment) {
            // Overenie, či je používateľ prihlásený ako administrátor
            $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

            // Ak je používateľ administrátor
            if ($isAdmin) {
                
                if ($confirmEdit) {
                    // Aktualizácia komentára v databáze
                    $updateResult = $newsRepository->updateComment($commentId, $content);

                    // Kontrola úspešnosti aktualizácie
                    if ($updateResult) {
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
                        sendErrorResponse('Error updating comment.');
                    }
                } else {
                    // Ak potvrdenie úpravy nie je nastavené, odoslanie odpovede vyžadujúcej potvrdenie
                    echo json_encode(['require_confirmation' => true]);
                    exit;
                }
            } else {
                // Ak používateľ nie je administrátor, odoslanie chybovej odpovede
                sendErrorResponse('You are not authorized to edit this comment.'); 
            }
        } else {
            sendErrorResponse('Comment not found.');
        }

        $conn->close();
    } else {
        sendErrorResponse('Invalid comment ID or content.');
    }
} else {
    // Spracovanie GET požiadavky (načítanie komentára na úpravu)
    if (isset($_GET['id'])) {
        // Získanie ID komentára z GET parametrov
        $commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Ak je ID komentára platné
        if ($commentId) {
            
            $conn = dbConnect();
            if (!$conn) {
               
                sendErrorResponse('Database connection failed.');
            }

            // Vytvorenie inštancie triedy NewsRepository
            $newsRepository = new NewsRepository($conn);
            $comment = $newsRepository->getCommentById($commentId);

            // Kontrola, či komentár existuje
            if ($comment) {
                // Kontrola prihlásenia používateľa a overenie, či je administrátorom
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                   
                    echo json_encode([
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user_id' => $comment->user_id
                    ]);
                } else {
                    
                    echo json_encode(['error' => 'Na úpravu tohto komentára nemáte oprávnenie. Iba administrátor môže upravovať komentáre.']);
                }
            } else {
                
                echo json_encode(['error' => 'Comment not found.']);
            }

            $conn->close();
        } else {
            
            echo json_encode(['error' => 'Invalid comment ID.']);
        }
    }
}
