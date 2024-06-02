<?php

session_start();


require_once '_inc/functions.php';

// Kontrola, či je v session premennej nastavené ID používateľa
if (isset($_SESSION['user_id'])) {

   
    $conn = dbConnect();

    
    $stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
    
    
    $stmt->bind_param("i", $_SESSION['user_id']);

    
    $stmt->execute();

    
    $result = $stmt->get_result();

    
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

    
    $stmt->close();

    $conn->close(); 
} else {
    // Ak používateľ nie je prihlásený, vrátenie HTTP stavového kódu 401 Unauthorized a chybového hlásenia vo formáte JSON
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
}
