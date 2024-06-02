<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/Zapas.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/zapasy_created.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/MatchRepository.php'; 

// Získanie používateľských zápasov
$conn = dbConnect();
$matchRepository = new MatchRepository($conn);
$userCreatedMatches = $matchRepository->getAllMatches();
$conn->close(); 



// Pole zápasov (ako objekty triedy Zapas)
$zapasy = [
    new Zapas('20:30', 'Barcelona', 'PSG', 'FCB_LOGO.png', 'PSG_LOGO.png', 'partials/H2H_1_P.php', 'partials/preview_1.php', 'Liga Majstrov'),
    new Zapas('20:30', 'Dortmund', 'Atletico Madrid', 'bvb.png', 'atletico.png', 'partials/H2H_2_p.php', 'partials/preview_2.php', 'Liga Majstrov'),
    new Zapas('21:00', 'Atalanta', 'Leverkusen', 'Atalanta.jpg', 'Leverkusen.jpg', 'partials/H2H_3_p.php', 'partials/preview_3.php', 'Európska Liga'),
];

$zapasy = array_merge($zapasy, $userCreatedMatches); 

// Zoskupenie zápasov podľa súťaže
$groupedMatches = array_reduce($zapasy, function ($result, $zapas) {
    $result[$zapas->competition][] = $zapas;
    return $result;
}, []);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Zapasy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0; 
    color: #333;
    margin: 0;
}

.zapasy {
    width: 90%;
    max-width: 1200px; 
    margin: 20px auto;
    padding: 20px;
    background-color: #fff; 
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.zapasy_nazov_sutaze {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee; /
    padding-top: 15px;
}

.zapasy_nazov_sutaze img {
    width: 40px; 
    height: 40px;
    object-fit: contain; 
    margin-top: 15px;
}

.zapasy_nazov_sutaze h3 {
    margin: 0;
    color: #007bff; 
}

/* Štýly pre zápasy */
.matches-group {
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
    gap: 20px;
}

.zapas {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.zapas h3 {
    margin: 0 0 10px; 
    color: #333; 
}

.zapas_left,
.zapas_right {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.zapas img {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

.zapas_links {
    margin-top: 15px;
}

.zapas_links a {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px;
    text-decoration: none;
    background-color: #007bff;
    color: #fff;
    border-radius: 4px;
}


.zapas_links a:hover {
    background-color: #0056b3;
}

.simulation-button {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 10px 15px;
    background-color: #007bff; 
    color: white;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.simulation-button:hover {
    background-color: #0056b3; 
}


.zapasy_hry_1 {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    margin-bottom: 20px; 
}


.zapasy_hry_cas {
    margin-bottom: 10px;
    font-size: 16px; 
    font-weight: bold;
}


.zapasy_hry_games {
    display: flex;
    justify-content: space-around;
    margin-bottom: 15px;
}

.zapasy_hry_games p {
    margin: 0;
    font-size: 18px; 
    font-weight: bold; 
}


.zapasy_hry_data {
    display: flex;
    justify-content: space-around;
    gap: 20px;
    margin-bottom: 15px;
}


.zapasy_hry_H2H, .zapasy_hry_analyza {
    flex: 1;
    border: none;          
    padding: 10px;
    background-color: #f8f9fa; 
    border-radius: 5px;
}

.zapasy_hry_H2H h3, .zapasy_hry_analyza h3 {
    margin: 0 0 5px;
    font-size: 16px;
    color: #007bff; 
}

.zapasy_hry_H2H p, .zapasy_hry_analyza p {
    margin: 5px 0;
}


.zapasy_hry_live {
    text-align: center;
    margin-top: 15px; 
}

.zapasy_hry_live img {
    width: 30px;
    height: auto;
}



.toggle-button {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease; 
}

.toggle-button:hover {
    background-color: #0056b3; 
}
.matches-group {
    display: flex; 
    flex-direction: column; 
    gap: 20px; 
}


.match-container {
    background-color: #fff;
    padding: 25px;          
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;     
    text-align: center;
    overflow: hidden;       
}

.match-time p {
    font-size: 1.3rem; 
    font-weight: bold;
    color: #333;          
    margin-bottom: 20px;  
}


.match-teams {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-bottom: 25px;  
}


.team-with-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.team-logo {
    width: 60px;         
    height: 60px;
    object-fit: contain;
}

.match-teams p {
    font-size: 1.2rem; 
    font-weight: bold;   
    margin: 0;
}


.match-data {
    display: flex;
    justify-content: space-around;
   
}


.match-h2h, 
.match-preview {
    flex: 1;
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
    text-align: left; 
}


.user-created-heading { 
    font-weight: bold;
    margin: 0;           
    padding: 0 0 10px 0;   
    border-bottom: 1px solid #eee; 
    text-align: center;  
}



.user-created-data {
    font-weight: normal;  
}

.match-detail {
    font-weight: bold;
}


.match-h2h-1 .user-created-data, 
.match-preview-1 .user-created-data { 
    color: #333; 
}

.match-h2h-2 .user-created-data,
.match-preview-2 .user-created-data {
    color: #666; 
}

</style>

    <script src='main.js'></script>


</head>

<body>
    
<div class="zapasy">
    <a href="partials/add_matches.php" class="simulation-button">Pridať zápas</a>
    <?php foreach ($groupedMatches as $competition => $matchesInCompetition): ?>
        <div class="zapasy_nazov_sutaze">
            <?php
            // Načítanie loga súťaže s predvolenou hodnotou
            $competitionSlug = strtolower(str_replace(' ', '-', $competition));
            $logoPath = file_exists("img/$competitionSlug.png") ? "img/$competitionSlug.png" : 'img/default_competition.png'; 
            ?>

            <?php 
            $displayedMatches = 0; 
            foreach ($matchesInCompetition as $zapas) {
                if (!($zapas instanceof UserCreatedZapas)) {
                    $displayedMatches++;
                    if ($displayedMatches <= 3) {
                        echo '<img src="' . $logoPath . '" alt="' . $competition . '">';
                        break; // Ukončíme cyklus po zobrazení prvého loga
                    }
                }
            }
            ?>
            <h3><?= $competition ?></h3>
        </div>

        <div class="matches-group">
            <?php foreach ($matchesInCompetition as $zapas): ?>
                <?php
                if ($zapas instanceof UserCreatedZapas) {
                    $zapas->displayWithoutLogos(); 
                } else {
                    $zapas->display();
                }
                ?> 
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
