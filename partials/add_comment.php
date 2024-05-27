<?php
// Spustenie session
session_start();

// Načítanie potrebných súborov s funkciami a triedou NewsRepository
require_once '../_inc/functions.php';  // Obsahuje dbConnect()
require_once '../_inc/NewsRepository.php'; // Obsahuje triedu pre prácu s novinkami 

// Overenie prihlásenia používateľa
if (!isset($_COOKIE['loggedIn'])) { // Kontrola, či je nastavený cookie 'loggedIn'
    // Ak nie je prihlásený, nastaví sa chybová správa
    $_SESSION['error_message'] = "Pre pridanie komentára musíte byť prihlásený."; 

    // Presmerovanie na stránku s novinkami
    header("Location: ../news.php"); 

    // Ukončenie skriptu, aby sa zabránilo ďalšiemu vykonávaniu kódu po presmerovaní
    exit; 
}

// Vytvorenie spojenia s databázou
$conn = dbConnect();

// Spracovanie odoslaného formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kontrola, či bol formulár odoslaný metódou POST

    // 1. Validácia a čistenie vstupných údajov
    // Overenie, či je ID novinky celé číslo a či bol zadaný obsah komentára
    // FILTER_VALIDATE_INT: Filtruje hodnotu ako celé číslo
    // FILTER_SANITIZE_SPECIAL_CHARS: Odstráni špeciálne znaky 
    $newsId = filter_input(INPUT_POST, 'news_id', FILTER_VALIDATE_INT);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

    // Ak sú vstupné údaje platné
    if ($newsId && $content) { 
        // Získanie ID používateľa zo session premennej, kam bolo uložené pri prihlásení
        $userId = $_SESSION['user_id'];

        // 2. Vloženie komentára do databázy

        // Príprava SQL dopytu na vloženie komentára
        $sql = "INSERT INTO comments (news_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";

        // Príprava pripraveného vyhlásenia pre SQL dopyt
        $stmt = $conn->prepare($sql);

        // Naviazanie parametrov do pripraveného vyhlásenia
        $stmt->bind_param("iis", $newsId, $userId, $content); // "iis" = int, int, string - typy údajov pre jednotlivé parametre

        // 3. Vykonanie dopytu a spracovanie výsledku
        if ($stmt->execute()) {
            // Ak bol komentár úspešne vložený, uloží sa správa o úspechu do session premennej
            $_SESSION['success_message'] = "Komentár bol úspešne pridaný!"; 
        } else {
            // Ak sa pri vkladaní komentára vyskytla chyba, uloží sa chybové hlásenie do session premennej
            $_SESSION['error_message'] = "Chyba pri pridávaní komentára: " . $stmt->error; 
        }
    } else {
        // Ak nie sú zadané všetky potrebné údaje, nastaví sa chybová správa
        $_SESSION['error_message'] = "Prosím, vyplňte všetky polia.";
    }

    // 4. Presmerovanie späť na článok s novinkou
    $redirectUrl = "news.php?id=$newsId";  // URL pre presmerovanie
    header("Location: $redirectUrl"); // Presmerovanie na danú URL
    exit; // Ukončenie skriptu po presmerovaní
}

// 5. Uzatvorenie spojenia s databázou
$conn->close(); 
?>
