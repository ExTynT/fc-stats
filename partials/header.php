<?php

$loggedIn = isset($_COOKIE['loggedIn']); 
$userId = null; 
$userRole = null;

// Ak je používateľ prihlásený, načítame ID a rolu z session
if ($loggedIn) {
    // Ak existuje session premenná 'user_id', priradíme jej hodnotu do $userId, inak null
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; 

    // Ak existuje session premenná 'user_role', priradíme jej hodnotu do $userRole, inak null
    $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null; 
}
?>

<!DOCTYPE html>
<html lang = "sk">
<head>
    <style>
        .hlavicka a{text-decoration: none;color:black;}
    </style>
  
<body>
<input type="hidden" id="errorMessage" name="errorMessage" value="<?php echo isset($_SESSION['error_message']) ? $_SESSION['error_message'] : ''; ?>">

<div class="hlavicka">
        <div class="hlavicka-prvok-1">
            <img src="/img/logo.png" alt="logo" id="logo">
        </div>
        <div class="hlavicka-prvok-2">
            <a href="/index.php"><p>FIXTURES</p></a>
        </div>
        <div class="hlavicka-prvok-3">
            <a href="/partials/news.php"><p>NEWS</p></a>
        </div>

        <div class="hlavicka-prvok-4">
            <?php if ($loggedIn): ?>
                <a href="/partials/user_profile.php?id=<?php echo $userId; ?>">
                    <img src="/img/prihlasovanie-logo.png" alt="prihlasovanie-logo" id="prihlasovanie"> 
                </a>
            <?php else: ?>
                <a href="/partials/signup_page.php">
                    <img src="/img/prihlasovanie-logo.png" alt="prihlasovanie-logo" id="prihlasovanie">
                </a>
            <?php endif; ?>
        </div>
    </div>

    </div>
    <script src="partials/main.js" defer ></script>
</body>
</html>
