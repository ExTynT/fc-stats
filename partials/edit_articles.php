<?php
session_start(); 
require_once '../_inc/functions.php'; 
require_once '../_inc/NewsRepository.php'; 

// Overenie prihlásenia a roly admin 
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); 
    exit;
} else {
    
}

// Vytvorenie inštancie triedy NewsRepository
$newsRepository = new NewsRepository(dbConnect()); 

// Inicializácia premenných pre chybové a úspešné správy a pre údaje o článku
$errorMessage = null;
$successMessage = null;
$article = null;
$selectedArticleId = null;

// Spracovanie formulára na výber článku na úpravu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    
    $selectedArticleId = filter_input(INPUT_POST, 'article_id', FILTER_VALIDATE_INT);

    // Ak je ID článku platné
    if ($selectedArticleId) {
        // Získanie údajov o článku z databázy
        $article = $newsRepository->getArticleById($selectedArticleId);

        // Spracovanie formulára na úpravu článku
        if (isset($_POST['title']) && isset($_POST['content'])) { 
           
            $newTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING); 
            $newContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING); 

            // Ak sú nový nadpis a obsah zadané
            if ($newTitle && $newContent) {
                // Aktualizácia článku v databáze
                $updateResult = $newsRepository->updateArticle($selectedArticleId, $newTitle, $newContent); 

                // Kontrola, či bola aktualizácia úspešná
                if ($updateResult) {
                    $successMessage = "Článok bol úspešne upravený.";
                    $article = $newsRepository->getArticleById($selectedArticleId); 
                } else {
                  
                    $errorMessage = "Chyba pri úprave článku.";
                }
            } else {
                
                $errorMessage = "Prosím, vyplňte všetky polia.";
            }
        } 
    } else {
     
        $errorMessage = "Neplatné ID článku.";
    }
}

// Získanie zoznamu všetkých článkov pre výber v rozbaľovacom zozname
$articles = $newsRepository->getAllArticles();

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Upraviť články</title>
    <style>
        /* Všeobecné štýly */
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
    max-width: 800px;
    margin: 20px auto;
}

h1, h2 {
    text-align: center;
}

h1 {
    color: #007bff;
    margin-bottom: 20px;
}

h2 {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

/* Štýly pre odkazy */
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

/* Štýly pre formuláre */
form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Štýly pre chybové a úspešné správy */
.error-message,
.success-message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.error-message {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.success-message {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Upraviť články</h1>

       

        <h2>Vyberte článok na úpravu:</h2>
        <form method="POST">
            <label for="article_id">Vyberte článok:</label>
            <select name="article_id" id="article_id">
                
                <?php foreach ($articles as $articleItem): ?>
                    <option value="<?php echo $articleItem['id']; ?>" <?php echo ($articleItem['id'] == $selectedArticleId) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($articleItem['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Upraviť článok</button>
        </form>

        <?php if ($article): ?>
            <h2>Upraviť článok</h2>
            <form method="POST">
                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                <label for="title">Nadpis:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                <label for="content">Obsah:</label>
                <textarea name="content" id="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                <button type="submit">Uložiť zmeny</button>
            </form>
        <?php endif; ?>

        <a href="admin.php">Späť na admin rozhranie</a>
    </div>
</body>
</html>
