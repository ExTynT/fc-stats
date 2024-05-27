<?php
// Spustenie session
session_start();

// Načítanie pomocných funkcií 
require_once '../_inc/functions.php';

// Vytvorenie spojenia s databázou
$conn = dbConnect();

// Kontrola úspešnosti nadviazania spojenia
if (!$conn) {
    // Ak sa pripojenie nepodarilo, nastaví sa chybová správa do session premennej
    $_SESSION['error_message'] = "Connection failed."; 

    // Presmerovanie na hlavnú stránku
    header("Location: ../index.php");

    // Ukončenie skriptu, aby sa zabránilo ďalšiemu vykonávaniu kódu po presmerovaní
    exit;
}

// Spracovanie odoslaného registračného formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Základná validácia a čistenie vstupných údajov

    // Získanie a vyčistenie používateľského mena a hesla z formulára
    $newUsername = filter_input(INPUT_POST, 'newUsername', FILTER_SANITIZE_STRING); // Odstraní potenciálne nebezpečné znaky
    $newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_UNSAFE_RAW); // Bez filtrovania (neskôr bude heslo zahashované)
    $confirmNewPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_UNSAFE_RAW);

    // Kontrola, či je zaškrtnutá voľba "Som admin"
    $isAdmin = isset($_POST['isAdmin']) ? $_POST['isAdmin'] : 'off'; // Ak nie je zaškrtnuté, nastaví sa 'off'

    // Získanie a vyčistenie administrátorského kódu z formulára
    $adminCode = filter_input(INPUT_POST, 'adminCode', FILTER_SANITIZE_STRING);

    // Počiatočná validácia - kontrola, či sú všetky polia vyplnené a či sa zadané heslá zhodujú
    if (empty($newUsername) || empty($newPassword) || $newPassword !== $confirmNewPassword) {
        echo "<script>alert('Neplatný vstup. Prosím, skontrolujte všetky polia.');
                         window.location.href = '/partials/signup_page.php'; // Presmerovanie späť na registračnú stránku
                      </script>";
        exit; // Ukončenie skriptu po vykonaní presmerovania
    }

    // Kontrola duplicity používateľského mena

    // Príprava SQL dopytu na kontrolu, či už existuje používateľ s daným menom
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $newUsername);

    if ($checkStmt->execute()) { // Vykonanie dopytu na overenie duplicity
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) { // Ak je nájdený riadok, znamená to, že meno už existuje
            // Nastavenie chybovej správy do session a presmerovanie na index.php
            $_SESSION['error_message'] = "Username already taken.";
            header("Location: ../index.php");
            exit;
        } else {
            // Ak meno nie je obsadené, pokračujeme s registráciou

            // Zahešovanie hesla pre bezpečné uloženie do databázy
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Určenie roly používateľa podľa toho, či je admin a zadal správny kód
            $role = ($isAdmin == 'on' && $adminCode === 'YOUR_SECRET_CODE') ? 'admin' : 'user'; // Porovnanie so správnym kódom

            // Vloženie nového používateľa do databázy
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $newUsername, $hashedPassword, $role);
            
            if ($stmt->execute()) { // Vykonanie dopytu na vloženie nového používateľa
                // Ak bolo vloženie úspešné, nastaví sa správa o úspechu do session a presmeruje sa na prihlasovaciu stránku
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: signup_page.php"); 
                exit;
            } else {
                // Ak nastala chyba pri vkladaní, uloží sa chybová správa do session
                $_SESSION['error_message'] = "Error during signup: " . $stmt->error;
            }
        }

        $checkStmt->close(); 
    } else {
        // Ak nastala chyba pri overovaní duplicity mena, uloží sa chybová správa do session
        $_SESSION['error_message'] = "Error checking username: " . $checkStmt->error;
    }
    // Presmerovanie na hlavnú stránku po spracovaní formulára 
header("Location: ../index.php"); 
exit;
} 

// Presmerovanie na hlavnú stránku po spracovaní formulára 

$conn->close(); 
?>
