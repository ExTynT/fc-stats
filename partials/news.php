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
        include '../_inc/functions.php';
        include '../_inc/NewsRepository.php';
        include '../_inc/template_functions.php';

        $conn = dbConnect();
        $newsRepository = new NewsRepository($conn);
        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'Champions League';

        $newsItems = $newsRepository->getNewsByCategory($selectedCategory, 3);

        foreach ($newsItems as $newsItem) {
            echo "<div class='news-item-with-comments'>"; 
            
            renderNewsItem($newsItem); 
            
            echo "<div class='comments-container'>";
            echo "    <div class='comment-section'>";
            echo "        <p>Comment 1: This is a great article!</p>"; 
            echo "        <p>Comment 2: I disagree with the author's point of view.</p>";
            echo "    </div>";
            echo "    <div class='comment-actions'>";
            echo "        <button class='action-button'>Add Comment</button>";
            echo "        <button class='action-button'>Edit</button>";
            echo "        <button class='action-button'>Delete</button>";
            echo "    </div>";
            echo "</div>"; 

            echo "</div>";  
        }

        if (count($newsItems) === 0) {
            echo "<p>No news available for this category.</p>";
        }

        $conn->close();
        ?>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>


