<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/Zapas.php'; 

// Pole zápasov (ako objekty triedy Zapas)
$zapasy = [
    new Zapas('20:30', 'Barcelona', 'PSG', 'FCB_LOGO.png', 'PSG_LOGO.png', 'partials/H2H_1_P.php', 'partials/preview_1.php', 'Liga Majstrov'),
    new Zapas('20:30', 'Dortmund', 'Atletico Madrid', 'bvb.png', 'atletico.png', 'partials/H2H_2_p.php', 'partials/preview_2.php', 'Liga Majstrov'),
    new Zapas('21:00', 'Atalanta', 'Leverkusen', 'Atalanta.jpg', 'Leverkusen.jpg', 'partials/H2H_3_p.php', 'partials/preview_3.php', 'Európska Liga'),
];

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


    </style>
    <script src='main.js'></script>
</head>
<body>
    <div class="zapasy">
    <a href="partials/simulation.php" class="simulation-button">Simulácia</a> 
        <?php foreach ($groupedMatches as $competition => $matchesInCompetition): ?>
            <div class="zapasy_nazov_sutaze">
                <?php
                // Načítanie loga súťaže s predvolenou hodnotou
                $competitionSlug = strtolower(str_replace(' ', '-', $competition));
                $logoPath = file_exists("img/$competitionSlug.png") ? "img/$competitionSlug.png" : 'img/default_competition.png'; 
                ?>
                <img src="<?= $logoPath ?>" alt="<?= $competition ?>">
                <h3><?= $competition ?></h3>
            </div>
            <div class="matches-group">
                <?php foreach ($matchesInCompetition as $zapas): ?>
                    <?php $zapas->display(); ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
