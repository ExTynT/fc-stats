
<?php

require_once '../_inc/functions.php';       
require_once '../_inc/MatchRepository.php';  
require_once '../_inc/Zapas.php';  
require_once '../_inc/zapasy_created.php';

// Inicializácia premenných pre správy
$errorMessage = "";
$successMessage = "";

// Spracovanie formulára na pridanie zápasu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Získanie údajov z formulára
    $time = $_POST['time'];
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $competition = $_POST['competition'];

    // Spracovanie nahrávania loga
    $logo1Result = uploadFile('logo1');
    $logo2Result = uploadFile('logo2');
    

        // Získanie údajov H2H a preview
        $h2hData = [
            'date' => $_POST['h2h_date'],
            'league' => $_POST['h2h_league'],
            'match' => $_POST['h2h_match'],
            'result' => $_POST['h2h_result']
        ];

        $previewData = [
            'text' => $_POST['preview_text'],
            'odds_win_1' => $_POST['preview_odds_win_1'],
            'odds_draw' => $_POST['preview_odds_draw'],
            'odds_win_2' => $_POST['preview_odds_win_2']
        ];


        // Vytvorenie objektu UserCreatedZapas
        $zapas = new UserCreatedZapas($time, $team1, $team2, $logo1Result,  $logo2Result, $h2hData, $previewData, $competition); 

        // Uloženie zápasu, H2H a preview do databázy
        $matchRepository = new MatchRepository(dbConnect());
        try {
            $matchId = $matchRepository->createMatch($zapas);

            // Vloženie H2H a preview dát iba ak boli odoslané vo formulári
            if (!empty($h2hData['date']) && !empty($h2hData['league']) && !empty($h2hData['match']) && !empty($h2hData['result'])) {
                $matchRepository->createH2hData($matchId, $h2hData);
            }

            if (!empty($previewData['text']) && !empty($previewData['odds_win_1']) && !empty($previewData['odds_draw']) && !empty($previewData['odds_win_2'])) {
                $matchRepository->createPreviewData($matchId, $previewData);
            }

            $successMessage = "Zápas bol úspešne pridaný.";
        } catch (Exception $e) {
            $errorMessage = "Chyba pri pridávaní zápasu: " . $e->getMessage();
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <style>body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="text"],
input[type="time"],
input[type="date"],
textarea {
    width: calc(100% - 22px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

button[type="submit"] {
    background-color: #007bff; 
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease; 
}

button[type="submit"]:hover {
    background-color: #0056b3; 
}


h3 {
    margin-top: 25px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}


input[type="file"] {
    padding: 5px;
}

.back-button {
    position: absolute; 
    top: 15px; 
    right: 15px; 
    display: inline-block;
    background-color: #dc3545;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #c82333;
}

.team-name{padding-top: 15px;}

</style>
    <title>Pridať zápas</title>
</head>
<body>
   


    <form method="POST" enctype="multipart/form-data">
    <h2>Pridať nový zápas</h2>
        <label for="time">Čas:</label><br>
        <input type="time" id="time" name="time" ><br><br>

        <label for="team1">Tím 1:</label><br>
        <input type="text" id="team1" name="team1"  ><br><br>

        <label for="team2">Tím 2:</label><br>
        <input type="text" id="team2" name="team2" ><br><br>

        <label for="logo1">Logo tímu 1:</label><br>
        <input type="file" id="logo1" name="logo1" ><br><br>

        <label for="logo2">Logo tímu 2:</label><br>
        <input type="file" id="logo2" name="logo2" ><br><br>
       
       
        <h3>H2H</h3>
       
        <label for="h2h_date">Dátum:</label><br>
        <input type="date" id="h2h_date" name="h2h_date" ><br><br>

        <label for="h2h_league">Liga:</label><br>
        <input type="text" id="h2h_league" name="h2h_league"  ><br><br>

        <label for="h2h_match">Zápas:</label><br>
        <input type="text" id="h2h_match" name="h2h_match" ><br><br>

        <label for="h2h_result">Výsledok:</label><br>
        <input type="text" id="h2h_result" name="h2h_result" ><br><br>

        <h3>Preview</h3>

        <label for="preview_text">Text náhľadu:</label><br>
        <textarea id="preview_text" name="preview_text" rows="4" cols="50" ></textarea><br><br>

        <label for="preview_odds_win_1">Kurz na výhru tímu 1:</label><br>
        <input type="text" id="preview_odds_win_1" name="preview_odds_win_1"  ><br><br>

        <label for="preview_odds_draw">Kurz na remízu:</label><br>
        <input type="text" id="preview_odds_draw" name="preview_odds_draw" ><br><br>

        <label for="preview_odds_win_2">Kurz na výhru tímu 2:</label><br>
        <input type="text" id="preview_odds_win_2" name="preview_odds_win_2" ><br><br>

        <label for="competition">Súťaž:</label><br>
        <input type="text" id="competition" name="competition" ><br><br>

        <button type="submit">Pridať zápas</button>
        <a href="/index.php" class="back-button">Späť</a>
    </form>
    
</body>
</html>
