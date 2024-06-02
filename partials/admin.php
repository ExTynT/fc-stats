<?php

session_start();


require_once '../_inc/functions.php';  
require_once '../_inc/Analytics.php';   


$conn = dbConnect();


$analytics = new Analytics($conn);

// Získanie štatistík
$totalUsers = $analytics->getTotalUsers();          // Celkový počet používateľov
$totalComments = $analytics->getTotalComments();    // Celkový počet komentárov
$totalArticles = $analytics->getTotalArticles();    // Celkový počet článkov

// Overenie prihlásenia a oprávnení administrátora
if (!isset($_SESSION['user_id'])) {
    // Ak nie je nastavené ID používateľa v session (používateľ nie je prihlásený)
    header('Location: ../index.php'); 
    exit; 
} else {

    // Ak je používateľ prihlásený, overíme, či má rolu administrátora

    $stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $user = $result->fetch_assoc(); 
    $conn->close(); 

    // Kontrola, či bol používateľ nájdený a či má rolu 'admin'
    if (!$user || $user['role'] !== 'admin') {
        header('Location: ../index.php'); 
        exit; 
    } else {
        // Ak je používateľ admin, uložíme jeho meno do premennej $username
        $username = $user['username']; 
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Admin rozhranie</title>
    <style>
/* Štýly pre celú stránku */
body {
    font-family: sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
}


.admin-panel {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 800px; 
    margin: 20px auto; 
}


h1 {
    color: #007bff;
    margin-bottom: 20px;
}

h2 {
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}


a {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #0056b3; 
}


ul {
    list-style: none; 
    padding: 0;
    margin-bottom: 20px;
}

li {
    text-align: left; 
    padding: 8px 15px;
    border-bottom: 1px solid #eee;
}

li:last-child { 
    border-bottom: none;
}
    </style>
</head>
<body>
    <div class="admin-panel">
        <?php if (isset($username)): ?>
            <h1>Vitajte v admin rozhraní, <?php echo htmlspecialchars($username); ?>!</h1>
        <?php else: ?>
            <p>Chyba: Používateľské meno sa nepodarilo načítať.</p> 
        <?php endif; ?>

        <h2>Štatistiky:</h2>
        <p>Celkový počet používateľov: <?php echo $totalUsers; ?></p>
        <p>Celkový počet komentárov: <?php echo $totalComments; ?></p>
        <p>Celkový počet článkov: <?php echo $totalArticles; ?></p>
        <p>Priemerný počet komentárov na článok: <?php echo number_format($analytics->getAverageCommentsPerArticle(), 1, '.', ''); ?></p> 
<p>Počet nových používateľov za posledný mesiac: <?php echo number_format($analytics->getNewUsersLastMonth(), 0, '', ''); ?></p>

<h3>Najaktívnejší používatelia:</h3>
<ul>
<?php
// Začína cyklus foreach, ktorý prechádza cez pole s najaktívnejšími používateľmi
foreach ($analytics->getMostActiveUsers() as $user) {

   
    echo "<li>";

    // bezpečnosť proti XSS útokom
    echo htmlspecialchars($user['username']); 

    
    echo " (" . number_format($user['comment_count'], 0, '', '') . " komentárov)";

    
    echo "</li>";
}
?>
</ul>

<h3>Najkomentovanejšie články:</h3>
<ul>
<?php
// Začína cyklus foreach, ktorý prechádza cez pole s najkomentovanejšími článkami
foreach ($analytics->getMostCommentedArticles() as $article) {

   
    echo "<li>";

    
    echo htmlspecialchars($article['title']);

    
    echo " (" . number_format($article['comment_count'], 0, '', '') . " komentárov)"; 

    
    echo "</li>";
}
?>



</ul>

        <a href="edit_articles.php">Upraviť články</a> 
        <a href="../index.php">Späť na hlavnú stránku</a>
        <a href ="delete_user_match.php">Vymazanie článku</a>
        <a href="logout.php">Odhlásiť sa</a>
    </div>
</body>
</html>

