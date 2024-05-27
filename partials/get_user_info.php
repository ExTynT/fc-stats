<?php
// Spustenie session
session_start();

// Načítanie súboru functions.php z aktuálneho adresára (_inc)
require_once '_inc/functions.php';

// Overenie prihlásenia
// Kontrola, či je v session premennej nastavené ID používateľa
if (isset($_SESSION['user_id'])) {

    // Vytvorenie spojenia s databázou
    $conn = dbConnect();

    // Príprava SQL dopytu na získanie mena a roly používateľa podľa jeho ID
    $stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
    
    // Bezpečné naviazanie parametra (ID používateľa) do pripraveného dopytu
    $stmt->bind_param("i", $_SESSION['user_id']);

    // Vykonanie dopytu
    $stmt->execute();

    // Získanie výsledkov dopytu
    $result = $stmt->get_result();

    // Načítanie údajov používateľa z výsledku dopytu
    $user = $result->fetch_assoc();

    // Kontrola, či sa našiel používateľ s daným ID
    if ($user) {
        // Ak áno, vrátenie používateľského mena a roly vo formáte JSON
        echo json_encode(['username' => $user['username'], 'role' => $user['role']]);
    } else {
        // Ak nie, vrátenie HTTP stavového kódu 401 Unauthorized a chybového hlásenia vo formáte JSON
        http_response_code(401); 
        echo json_encode(['error' => 'User not found']);
    }

    // Uzatvorenie príkazu (prepared statement)
    $stmt->close();

    // Uzatvorenie spojenia s databázou
    $conn->close(); 
} else {
    // Ak používateľ nie je prihlásený, vrátenie HTTP stavového kódu 401 Unauthorized a chybového hlásenia vo formáte JSON
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
}
