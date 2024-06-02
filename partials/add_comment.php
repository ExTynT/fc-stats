<?php

session_start();


require_once '../_inc/functions.php';  
require_once '../_inc/NewsRepository.php'; 


// Overenie prihlásenia používateľa
if (!isset($_COOKIE['loggedIn'])) { // Kontrola, či je nastavený cookie 'loggedIn'

    // Ak nie je prihlásený, nastaví sa chybová správa
    $_SESSION['error_message'] = "Pre pridanie komentára musíte byť prihlásený."; 

    // Presmerovanie na stránku s novinkami
    header("Location: ../news.php"); 

    
    exit; 
}


$conn = dbConnect();

// Spracovanie odoslaného formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    // Validácia a čistenie vstupných údajov
    $newsId = filter_input(INPUT_POST, 'news_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Získanie kategórie z POST alebo GET premennej
    $category = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : null);

    // Ak sú vstupné údaje platné
    if ($newsId && $content) { 

        // Získanie ID používateľa zo session premennej, kam bolo uložené pri prihlásení
        $userId = $_SESSION['user_id'];

        // Vloženie komentára do databázy
        $sql = "INSERT INTO comments (news_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $newsId, $userId, $content);

        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Komentár bol úspešne pridaný!"; 
        } else {
            $_SESSION['error_message'] = "Chyba pri pridávaní komentára: " . $stmt->error; 
        }
    } else {
        $_SESSION['error_message'] = "Prosím, vyplňte všetky polia.";
    }


$newsRepository = new NewsRepository($conn); 

// Získanie URL pre presmerovanie pomocou metódy getNewsRedirectUrl
$redirectUrl = $newsRepository->getNewsRedirectUrl($newsId, $category); 

header("Location: $redirectUrl"); 
exit;

}


$conn->close(); 
?>
