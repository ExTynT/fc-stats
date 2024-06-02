<?php
session_start();
require_once '../_inc/functions.php'; 
$conn = dbConnect();

$user = new User($conn); // Vytvorenie objektu triedy User

// Overenie prihlásenia a získanie informácií o používateľovi
if (isset($_SESSION['user_id'])) {
    $userInfo = $user->getInfo($_SESSION['user_id']);

    if (!$userInfo) {
        // Ak sa nepodarilo získať informácie o používateľovi, odhláste ho a presmerujte na index.php
        session_destroy(); 
        header('Location: ../index.php');
        exit;
    }
} else {
    // Ak používateľ nie je prihlásený, presmerujte ho na index.php
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Profil používateľa</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../style/style.css">

    <style>.container {
    
    text-align: center;
}

.container a {
    display: inline-block;
    margin: 10px;
    padding: 12px 24px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.container a:hover {
    background-color: #0056b3;
}</style>
</head>
<body>
    <div class="container">
        <h1>Vitajte, <?php echo htmlspecialchars($userInfo['username']); ?>!</h1>
        <p>Vaša rola: <?php echo htmlspecialchars($userInfo['role']); ?></p>

        <?php if ($userInfo['role'] === 'admin'): ?>
    <a href="admin.php">Admin Panel</a> 
    <a href="../index.php">Späť na hlavnú stránku</a>
    <a href="logout.php">Odhlásiť sa</a>
<?php endif; ?>

<?php if ($userInfo['role'] === 'user'): ?>  
    <a href="../index.php">Späť na hlavnú stránku</a>
    <a href="logout.php">Odhlásiť sa</a>
<?php endif; ?> 

    </div>
</body>
</html>
