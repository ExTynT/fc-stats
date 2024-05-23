<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Zapasy</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        .zapasy_nazov_sutaze{
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    margin-top: 10px;
    
}
    </style>
    <script src='main.js'></script>
</head>
<?php

$matches = array(
    array(
        'time' => '20:30',
        'team1' => 'Barcelona',
        'team2' => 'PSG',
        'logo1' => 'FCB_LOGO.png',
        'logo2' => 'PSG_LOGO.png',
        'h2h' => 'partials/H2H_1_P.php',
        'preview' => 'partials/preview_1.php',
        'competition' => 'Champions League'
    ),
    array(
        'time' => '20:30',
        'team1' => 'Dortmund',
        'team2' => 'Atletico Madrid',
        'logo1' => 'bvb.png',
        'logo2' => 'atletico.png',
        'h2h' => 'partials/H2H_2_p.php',
        'preview' => 'partials/preview_2.php',
        'competition' => 'Champions League'
    ),
    array(
        'time'=> '21:00',
        'team1'=> 'Atalanta',
        'team2'=> 'Leverkusen',
        'logo1'=> 'Atalanta.jpg',
        'logo2'=> 'Leverkusen.jpg',
        'h2h'=> 'partials/H2H_3_p.php',
        'preview'=> 'partials/preview_3.php',
        'competition' => 'Europa League'
        ),
);
?>

<div class="zapasy">
    <?php
    include '_inc/functions.php';

    // Zoskupenie podla sutaze
    $groupedMatches = [];
    foreach ($matches as $match) {
        $groupedMatches[$match['competition']][] = $match;
    }

    // Zobrazenie zapasov
    foreach ($groupedMatches as $competition => $matchesInCompetition) {
        echo "<div class='zapasy_nazov_sutaze'>";
        echo "<div class='zapasy_nazov_sutaze_img'>";
        echo "<img src='img/" . strtolower(str_replace(' ', '-', $competition)) . ".png' alt='$competition'>";
        echo "</div>";
        echo "<div class='zapasy_nazov_sutaze_sutaz'>";
        echo "<h3>$competition</h3>";
        echo "<h5>Europe</h5>";
        echo "</div>";
        echo "</div>"; 

        foreach ($matchesInCompetition as $match) {
            outputMatchDetails($match); 
        }
    }
    ?>
</div>
</body>
</html>
