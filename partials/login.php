<?php
// Spustenie session 
session_start();

// Načítanie pomocných funkcií 
require_once '../_inc/functions.php';

// Vytvorenie spojenia s databázou
$conn = dbConnect();

// Kontrola úspešnosti pripojenia k databáze
if (!$conn) {
    // Ak sa pripojenie nepodarilo, zobrazí sa upozornenie v JavaScripte a používateľ sa vráti na predchádzajúcu stránku.
    echo "<script>alert('Chyba pripojenia k databáze.'); history.back();</script>";
    exit; // Ukončí sa vykonávanie skriptu.
}

// Spracovanie prihlasovacieho formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Základná validácia vstupu
    // Používateľské meno sa očistí od nebezpečných znakov
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // Heslo sa prečíta v surovom stave, keďže ho budeme porovnávať s hashom v databáze
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    // Overenie, či sú polia vyplnené
    if (empty($username) || empty($password)) {
        // Ak niektoré pole nie je vyplnené, zobrazí sa upozornenie a používateľ sa vráti na predchádzajúcu stránku.
        echo "<script>alert('Prosím, vyplňte všetky polia.'); history.back();</script>";
        exit; // Ukončí sa vykonávanie skriptu.
    }

    // Príprava a vykonanie SQL dopytu
    // Dopyt vyberie id, password, role a username používateľa s daným menom
    $stmt = $conn->prepare("SELECT id, password, role, username FROM users WHERE username = ?"); 

    // Naviazanie parametra do pripraveného dopytu (zamedzenie SQL injection)
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Získanie výsledku dopytu
    $result = $stmt->get_result();

    // Kontrola, či sa našiel presne jeden používateľ s daným menom
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc(); // Načítanie riadku s údajmi používateľa

        // Overenie hesla pomocou funkcie password_verify (porovnáva zadané heslo s hashom v databáze)
        if (password_verify($password, $row['password'])) {
            // Úspešné prihlásenie
            $_SESSION['user_id'] = $row['id'];       // Uloženie ID používateľa do session
            $_SESSION['user_role'] = $row['role'];   // Uloženie roly používateľa do session
            $_SESSION['username'] = $row['username']; // Uloženie mena používateľa do session

            // Nastavenie cookies pre uchovanie informácií o prihlásení
            setcookie('loggedIn', 'true', time() + 3600, '/');      // Cookie platný na 1 hodinu
            setcookie('user_role', $row['role'], time() + 3600, '/'); // Uloženie role do cookie
            setcookie('username', $row['username'], time() + 3600, '/'); // Uloženie username do cookie

            // Presmerovanie podľa roly používateľa (admin.php pre admina, index.php pre ostatných)
            $redirectUrl = ($row['role'] === 'admin') ? 'admin.php' : '../index.php';
            header("Location: $redirectUrl"); 
            exit; // Ukončenie skriptu.
        } else {
            // Ak heslo nie je správne, zobrazí sa upozornenie a používateľ sa vráti na predchádzajúcu stránku
            echo "<script>alert('Nesprávne heslo.'); history.back();</script>";
            exit;
        }
    } else {
        // Ak používateľ s týmto menom neexistuje, zobrazí sa upozornenie a používateľ sa vráti na predchádzajúcu stránku
        echo "<script>alert('Používateľ s týmto menom neexistuje.'); history.back();</script>";
        exit; 
    }
}

// Uzatvorenie spojenia
$conn->close(); 
