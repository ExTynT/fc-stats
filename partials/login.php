<?php

session_start();


require_once '../_inc/functions.php';


$conn = dbConnect();


if (!$conn) {
  
    echo "<script>alert('Chyba pripojenia k databáze.'); history.back();</script>";
    exit; 
}

// Spracovanie prihlasovacieho formulára
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    // Overenie, či sú polia vyplnené
    if (empty($username) || empty($password)) {
        
        echo "<script>alert('Prosím, vyplňte všetky polia.'); history.back();</script>";
        exit; 
    }


    $stmt = $conn->prepare("SELECT id, password, role, username FROM users WHERE username = ?"); 

    
    $stmt->bind_param("s", $username);
    $stmt->execute();


    $result = $stmt->get_result();

    // Kontrola, či sa našiel presne jeden používateľ s daným menom
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc(); 

        // Overenie hesla pomocou funkcie password_verify (porovnáva zadané heslo s hashom v databáze)
        if (password_verify($password, $row['password'])) {
            // Úspešné prihlásenie
            $_SESSION['user_id'] = $row['id'];       
            $_SESSION['user_role'] = $row['role'];   
            $_SESSION['username'] = $row['username']; 

            // Nastavenie cookies pre uchovanie informácií o prihlásení
            setcookie('loggedIn', 'true', time() + 3600, '/');      
            setcookie('user_role', $row['role'], time() + 3600, '/'); 
            setcookie('username', $row['username'], time() + 3600, '/'); 

            // Presmerovanie podľa roly používateľa (admin.php pre admina, index.php pre ostatných)
            $redirectUrl = ($row['role'] === 'admin') ? 'admin.php' : '../index.php';
            header("Location: $redirectUrl"); 
            exit; 
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


$conn->close(); 
