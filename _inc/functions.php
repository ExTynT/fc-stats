<?php
// Funkcia na spojenie s databázou
function dbConnect() {
    $conn = mysqli_connect("localhost", "root", "", "fc_stats");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Funkcia na načítanie a výstup textu náhľadu a kurzov
function outputPreviewData($conn, $previewNumber) {
    $table = "preview_$previewNumber";
    $sql = "SELECT preview_{$previewNumber}_text, preview_{$previewNumber}_odds_win_1, preview_{$previewNumber}_odds_win_2, preview_{$previewNumber}_odds_draw FROM $table";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        
        echo "<p>" . $row["preview_{$previewNumber}_text"] . "</p>";

        
        echo "<table class='preview_1_odds'>";
        echo "<tr>";
        if ($previewNumber == 1) {
            echo "<th>Barcelona</th>";
            echo "<th>Draw</th>";
            echo "<th>PSG</th>";
        } elseif ($previewNumber == 2) {
            echo "<th>BVB Dortmund</th>";
            echo "<th>Draw</th>";
            echo "<th>Atletico Madrid</th>";
        } elseif ($previewNumber == 3) {
            echo "<th>Atalanta</th>";
            echo "<th>Draw</th>";
            echo "<th>Leverkusen</th>";
        } 

        echo "</tr>";
        echo "<tr>";
        echo "<td>" . $row["preview_{$previewNumber}_odds_win_1"] . "</td>";
        echo "<td>" . $row["preview_{$previewNumber}_odds_draw"] . "</td>";
        echo "<td>" . $row["preview_{$previewNumber}_odds_win_2"] . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "<p>No preview available.</p>";
        echo "<table class='preview_1_odds'><td colspan='3'>No odds available</td></table>"; 
    }
}

// H2H zobrazenie
function outputH2HData($conn, $tableNumber) {
    
    $table = "h2h_$tableNumber"; 

    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='datum'>" . $row["H2H_{$tableNumber}_Datum"] . "</td>";
                echo "<td class='liga'>" . $row["H2H_{$tableNumber}_Liga"] . "</td>";
                echo "<td class='team_1'>" . $row["H2H_{$tableNumber}_Zapas"] . "</td>";
                echo "<td class='vysledok'>" . $row["H2H_{$tableNumber}_Vysledok"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No H2H data available</td></tr>";
        }
    } else {
        die("Error executing query: " . $conn->error);  
    }
}

// udaje o zapasoch
function getMatchesData() {
    
    $conn = dbConnect();  

     
    $sql = "SELECT * FROM matches_table"; 
    $result = $conn->query($sql);

    $matches = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $matches[] = $row; 
        }
    }

    $conn->close();
    return $matches;
}


// udaje o zapase
function outputMatchDetails($match) {
    echo "<div class='zapasy_hry_1'>";
    echo "  <div class='zapasy_hry_cas'><p>{$match['time']}</p></div>";
    echo "  <div class='zapasy_hry_logo'>";
    echo "    <img src='img/{$match['logo1']}' alt='{$match['team1']}'>";
    echo "    <img src='img/{$match['logo2']}' alt='{$match['team2']}'>";
    echo "  </div>";
    echo "  <div class='zapasy_hry_games'>";
    echo "    <p>{$match['team1']}</p>";
    echo "    <p>{$match['team2']}</p>";
    echo "  </div>";

    
    outputH2HLink($match['h2h']);
    outputPreviewLink($match['preview']);

    echo "  <div class='zapasy_hry_live'>";
    echo "    <a href='https://www.premiersport.sk/program/' target='_blank'><img src='img/live.png' alt='live-match'></a>";
    echo "  </div>";
    echo "</div>";
}

// H2H link funkcia
function outputH2HLink($link) {
    echo "<div class='zapasy_hry_H2H'>";
    echo "  <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=355px,height=210px'); return false;\">H2H</a></p>";
    echo "</div>";
}

// preview link funkcia
function outputPreviewLink($link) {
    echo "<div class='zapasy_hry_analyza'>";
    echo "  <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=540px,height=800px'); return false;\">Preview</a></p>";
    echo "</div>";
}