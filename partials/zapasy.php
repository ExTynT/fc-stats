<?php

$matches = array(
    array(
        'time' => '20:30',
        'team1' => 'Barcelona',
        'team2' => 'PSG',
        'logo1' => 'FCB_LOGO.png',
        'logo2' => 'PSG_LOGO.png',
        'h2h' => 'partials/H2H_1_P.php',
        'preview' => 'partials/preview_1.php'
    ),
    array(
        'time' => '20:30',
        'team1' => 'Dortmund',
        'team2' => 'Atletico Madrid',
        'logo1' => 'bvb.png',
        'logo2' => 'atletico.png',
        'h2h' => 'partials/H2H_2_p.php',
        'preview' => 'partials/preview_2.php'
    )
);
?>

<div class="zapasy">
    <div class="zapasy_nazov_sutaze">
        <div class="zapasy_nazov_sutaze_img">
            <img src="img/liga_majstrov.png" alt="liga-majstrov">
        </div>
        <div class="zapasy_nazov_sutaze_sutaz">
            <h3>Champions League</h3>
            <h5>Europe</h5>
        </div>
    </div>

    <?php foreach ($matches as $match): ?>
    <div class="zapasy_hry_1">
        <div class="zapasy_hry_cas">
            <p><?php echo $match['time']; ?></p>
        </div>
        <div class="zapasy_hry_logo">
            <img src="img/<?php echo $match['logo1']; ?>" alt="<?php echo $match['team1']; ?>">
            <img src="img/<?php echo $match['logo2']; ?>" alt="<?php echo $match['team2']; ?>">
        </div>
        <div class="zapasy_hry_games">
            <p><?php echo $match['team1']; ?></p>
            <p><?php echo $match['team2']; ?></p>
        </div>
        <div class="zapasy_hry_H2H">
            <p><a href="<?php echo $match['h2h']; ?>" onclick="window.open(this.href, '_blank', 'width=355px,height=210px'); return false;">H2H</a></p>
        </div>
        <div class="zapasy_hry_analyza">
            <p><a href="<?php echo $match['preview']; ?>" onclick="window.open(this.href, '_blank', 'width=540px,height=800px'); return false;">Preview</a></p>
        </div>
        <div class="zapasy_hry_live">
            <a href="https://www.premiersport.sk/program/" target="_blank"><img src="img/live.png" alt="live-match"></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>