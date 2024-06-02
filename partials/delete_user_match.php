<?php
session_start();
require_once '../_inc/functions.php';
require_once '../_inc/MatchRepository.php';
require_once '../_inc/Zapas.php'; 

// Kontrola či je užívateľ admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php'); 
    exit;
}

$conn = dbConnect();
$matchRepository = new MatchRepository($conn);

// Spracovanie odstránenia zápasu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_match'])) {
    $matchIdToDelete = $_POST['delete_match'];
    if ($matchRepository->deleteMatch($matchIdToDelete)) { 
        $_SESSION['success_message'] = "Zápas bol úspešne zmazaný.";
    } else {
        $_SESSION['error_message'] = "Chyba pri mazání zápasu.";
    }
    header('Location: delete_user_match.php'); // Refreshnutie zápasu
    exit;
}

// Získanie všetkých user - vytvorených zápasov
$matches = $matchRepository->getAllUserCreatedMatches(); 

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Zmazať používateľom vytvorený zápas</title>
    <style>
     
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4; 
    color: #333;            
    margin: 0;
}

h2 {
    text-align: center;
    color: #333;
}


form {
    width: 400px;      
    margin: 20px auto; 
    padding: 20px;
    background-color: #fff; 
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
}


label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

select {
    width: 100%; 
    padding: 10px; 
    margin-bottom: 15px; 
    border: 1px solid #ddd; 
    border-radius: 4px;
    box-sizing: border-box; 
}


button[type="submit"] {
    background-color: #dc3545; 
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease; 
}

button[type="submit"]:hover {
    background-color: #c82333; 
}

a{
    background-color: #007bff; 
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease; 
    margin-top: 20px;
    display: block;
    text-align: center;
    text-decoration: none;
}

a:hover{
    background-color: #0056b3; 
}


.success {
    background-color: #28a745; 
    color: white;
    padding: 10px;
    margin-bottom: 15px;
    text-align: center;
    border-radius: 4px;
}

.error {
    background-color: #dc3545; 
    color: white;
    padding: 10px;
    margin-bottom: 15px;
    text-align: center;
    border-radius: 4px;
}

    </style>
</head>
<body>
    <h2>Zmazať používateľom vytvorený zápas</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form method="POST">
        <select name="delete_match">
            <?php foreach ($matches as $match): ?> 
                <option value="<?= $match['id'] ?>">
                    <?= $match['id'] ?> - <?= $match['team1'] ?> vs. <?= $match['team2'] ?> (<?= $match['competition'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Zmazať zápas</button>
        <a href ="../index.php">Hlavná stránka</a>
    </form>
</body>
</html>
