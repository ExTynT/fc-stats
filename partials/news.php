<!DOCTYPE html>
<html lang = "sk">
<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>News</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        <?php include '../style/style.css'; ?>
            body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    </style>

    <script src='main.js'></script>


    <?php include ("header.php"); ?> 


</head>
<!DOCTYPE html>
<html lang="sk">
<head>
    </head>
<body>
<div class="news-filters">
        <a href="?category=Champions League">Champions League</a>
        <a href="?category=Europa League">Europa League</a>
        <a href="?category=Transfers">Transfers</a>
        <a href="news.php">All News</a>
    </div>

    <div class="news-container">
    <?php


require_once '../_inc/functions.php';       
require_once '../_inc/NewsRepository.php';  
require_once '../_inc/template_functions.php'; 
include_once '../_inc/Comment.php';                


$conn = dbConnect();


$newsRepository = new NewsRepository($conn);

// Získanie vybranej kategórie z GET parametrov, ak nie je nastavená, predvolená hodnota je 'Champions League'
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'Champions League';

// Získanie 3 najnovších článkov z vybranej kategórie
$newsItems = $newsRepository->getNewsByCategory($selectedCategory, 3);

// Cyklus prechádzajúci cez získané články
foreach ($newsItems as $newsItem) {
    echo "<div class='news-item-with-comments'>"; 

    // Vykreslenie článku
    renderNewsItem($newsItem);

    
    $sql = "SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.news_id = ? 
            ORDER BY c.created_at DESC
            LIMIT 3";

    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $newsItem->id); 
    $stmt->execute();
    $commentsResult = $stmt->get_result();

    // Získanie celkového počtu komentárov pre článok
    $totalComments = $commentsResult->num_rows;
    $displayedComments = 0; 

  
    echo "<div class='comments-container'>";

    // Cyklus prechádzajúci cez výsledky komentárov
    while ($commentRow = $commentsResult->fetch_assoc()) { // Pre každý komentár

        // Vytvorenie objektu Comment z údajov komentára
        $comment = new Comment($commentRow);

        // Vykreslenie komentára pomocou šablóny 
        include '../partials/comment_template.php';

        
        $displayedComments++;
    }

    // Ak nie sú žiadne komentáre, zobrazí sa správa "No comments yet."
    if ($totalComments == 0) {
        echo "<p>No comments yet.</p>";
    // Ak je viac ako 3 komentárov, zobrazí sa odkaz na zobrazenie všetkých komentárov
    } else if ($totalComments > 3) {
        echo "<p><a href='view_all_comments.php?news_id={$newsItem->id}'>View all $totalComments comments</a></p>"; 
    }

    echo "</div>";

    // Zobrazenie formulára na pridanie komentára, ak je používateľ prihlásený a počet zobrazených komentárov je menej ako 3
    if (isset($_COOKIE['loggedIn']) && $displayedComments < 3) {
        include '../partials/comment_form.php'; 
    } elseif (isset($_COOKIE['loggedIn'])) {
        echo "<p>Dosiahnutý limit komentárov!</p>"; 
    } else {
        echo "<p>Please <a href='/partials/signup_page.php'>log in or sign up</a> to comment.</p>"; 
    }

    echo "</div>"; 
}


$conn->close(); 
?>


    </div>

    <?php include("footer.php"); ?>
</body>
</html>