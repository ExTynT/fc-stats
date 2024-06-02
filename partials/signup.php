<?php

session_start();


require_once '../_inc/functions.php';


$conn = dbConnect();

// Kontrola úspešnosti nadviazania spojenia
if (!$conn) {
    
    $_SESSION['error_message'] = "Connection failed."; 

    
    header("Location: ../index.php");

    
    exit;
}

// Spracovanie odoslaného registračného formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newUsername = filter_input(INPUT_POST, 'newUsername', FILTER_SANITIZE_STRING); 
    $newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_UNSAFE_RAW); 
    $confirmNewPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_UNSAFE_RAW);

    // Kontrola, či je zaškrtnutá voľba "Som admin"
    $isAdmin = isset($_POST['isAdmin']) ? $_POST['isAdmin'] : 'off'; // momentálne nevyužité

    // Získanie a vyčistenie administrátorského kódu z formulára
    $adminCode = filter_input(INPUT_POST, 'adminCode', FILTER_SANITIZE_STRING);

    // Počiatočná validácia - kontrola, či sú všetky polia vyplnené a či sa zadané heslá zhodujú
    if (empty($newUsername) || empty($newPassword) || $newPassword !== $confirmNewPassword) {
        echo "<script>alert('Neplatný vstup. Prosím, skontrolujte všetky polia.');
                         window.location.href = '/partials/signup_page.php'; // Presmerovanie späť na registračnú stránku
                      </script>";
        exit; 
    }

    // Kontrola duplicity používateľského mena

    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $newUsername);

    if ($checkStmt->execute()) { 
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) { // Ak je nájdený riadok, znamená to, že meno už existuje
           
            $_SESSION['error_message'] = "Username already taken.";
            header("Location: ../index.php");
            exit;
        } else {
            // Ak meno nie je obsadené, pokračujeme s registráciou

            // Zahešovanie hesla pre bezpečné uloženie do databázy
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Určenie roly používateľa podľa toho, či je admin a zadal správny kód
            $role = ($isAdmin == 'on' && $adminCode === 'YOUR_SECRET_CODE') ? 'admin' : 'user'; // nevyužité

            // Vloženie nového používateľa do databázy
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $newUsername, $hashedPassword, $role);
            
            if ($stmt->execute()) { 
                
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: signup_page.php"); 
                exit;
            } else {
                
                $_SESSION['error_message'] = "Error during signup: " . $stmt->error;
            }
        }

        $checkStmt->close(); 
    } else {
       
        $_SESSION['error_message'] = "Error checking username: " . $checkStmt->error;
    }
   
header("Location: ../index.php"); 
exit;
} 


$conn->close(); 
?>
