<?php

// Funkcia na vytvorenie spojenia s databázou MySQL
function dbConnect() {

    $servername = "localhost";   
    $username = "root";          
    $password = "";             
    $dbname = "fc_stats";        

    // Vytvorenie spojenia s databázou pomocou funkcie mysqli_connect
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Kontrola, či sa spojenie podarilo vytvoriť
    if ($conn->connect_error) {
    
        die("Spojenie s databázou zlyhalo: " . $conn->connect_error);
    }

    return $conn;
}




// Funkcia na výpis náhľadu zápasu a kurzov
function outputPreviewData($conn, $previewNumber) {

    $table = "preview_" . $previewNumber; 

    $sql = "SELECT 
                preview_{$previewNumber}_text AS preview_text, 
                preview_{$previewNumber}_odds_win_1 AS odds_win_1, 
                preview_{$previewNumber}_odds_win_2 AS odds_win_2, 
                preview_{$previewNumber}_odds_draw AS odds_draw
            FROM $table";

    
    $result = $conn->query($sql);

    // Kontrola, či dopyt vrátil nejaké výsledky a či sa vyskytli nejaké chyby
    if ($result && $result->num_rows > 0) {

        // Získanie prvého (a jediného očakávaného) riadku výsledku dopytu ako asociatívne pole
        $row = $result->fetch_assoc(); 

       
        echo "<p>" . $row["preview_text"] . "</p>"; 

        
        echo "<table class='preview_1_odds'>";
        echo "<tr>"; 

        // Dynamické nastavenie názvov tímov v hlavičke tabuľky podľa čísla zápasu
        if ($previewNumber == 1) {
            echo "<th>Barcelona</th><th>Remíza</th><th>PSG</th>";
        } elseif ($previewNumber == 2) {
            echo "<th>BVB Dortmund</th><th>Remíza</th><th>Atletico Madrid</th>";
        } elseif ($previewNumber == 3) {
            echo "<th>Atalanta</th><th>Remíza</th><th>Leverkusen</th>";
        }

        echo "</tr>"; 
        echo "<tr>";  

        // Výpis kurzov pre výhru 1. tímu, remízu a výhru 2. tímu
        echo "<td>" . $row["odds_win_1"] . "</td>";
        echo "<td>" . $row["odds_draw"] . "</td>";
        echo "<td>" . $row["odds_win_2"] . "</td>";

        echo "</tr>"; 
        echo "</table>"; 

    } else {

        echo "<p>No preview available.</p>"; 
        echo "<table class='preview_1_odds'><td colspan='3'>No odds available</td></table>"; 

    }
}



// Funkcia na výpis H2H (head-to-head) údajov
function outputH2HData($conn, $tableNumber) {

    $table = "h2h_" . $tableNumber;

    $sql = "SELECT * FROM $table";

    $result = $conn->query($sql);

    // Kontrola, či dopyt bol úspešný
    if ($result) {

        if ($result->num_rows > 0) {
            // Ak áno, prechádzame cez jednotlivé riadky výsledku
            while ($row = $result->fetch_assoc()) {
             
                echo "<tr>"; 

                
                echo "<td class='datum'>" . $row["H2H_{$tableNumber}_Datum"] . "</td>";

                
                echo "<td class='liga'>" . $row["H2H_{$tableNumber}_Liga"] . "</td>";

                
                echo "<td class='team_1'>" . $row["H2H_{$tableNumber}_Zapas"] . "</td>";

               
                echo "<td class='vysledok'>" . $row["H2H_{$tableNumber}_Vysledok"] . "</td>";

                echo "</tr>"; 
            }
        } else {
            // Ak dopyt nevrátil žiadne výsledky, zobrazí sa správa o nedostupnosti H2H údajov
            echo "<tr><td colspan='4'>No H2H data available</td></tr>"; 
        }
    } else {
        // Ak dopyt zlyhal, skript sa ukončí a zobrazí sa chybové hlásenie
        die("Error executing query: " . $conn->error); 
    }
}






// Funkcia na získanie údajov o všetkých zápasoch z databázy
function getMatchesData() {
    
    $conn = dbConnect();

   
    $sql = "SELECT * FROM matches_table";

    
    $result = $conn->query($sql);

    
    $matches = [];

    // Kontrola, či dopyt vrátil nejaké výsledky
    if ($result->num_rows > 0) {
       
        while ($row = $result->fetch_assoc()) {
            // Každý riadok (reprezentujúci jeden zápas) sa pridá do poľa $matches ako asociatívne pole
            $matches[] = $row; 
        }
    }


    $conn->close();

    
    return $matches;
}



// Funkcia na vytvorenie HTML kódu pre odkaz na H2H (Head-to-Head) stránku zápasu.
function outputH2HLink($link) {

   
    echo "<div class='zapasy_hry_H2H'>"; 

    
    echo "  <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=355px,height=210px'); return false;\">H2H</a></p>";
    
 
    echo "</div>";  

}



// Funkcia na vytvorenie HTML kódu pre odkaz na preview zápasu.
function outputPreviewLink($link) {

    
    echo "<div class='zapasy_hry_analyza'>";

    
    echo " <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=540px,height=800px'); return false;\">Preview</a></p>";

    
    echo "</div>";
}





class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Funkcia na získanie informácií o používateľovi
    public function getInfo($userId) {
   
        // Používa sa pripravený výraz (prepared statement) na zabránenie SQL injection útokom
        $stmt = $this->conn->prepare("SELECT username, role FROM users WHERE id = ?");

        // Kontrola, či sa podarilo pripraviť dopyt
        if ($stmt) {
  
            $stmt->bind_param("i", $userId);

            
            $stmt->execute();

            
            $result = $stmt->get_result();


            return $result->fetch_assoc(); 
        } else {
            // Ak sa nepodarilo pripraviť dopyt, vráti null
            return null;
        }
    }
}








// Funkcia na získanie údajov o užívateľovi z cookies
function getUserInfoFromCookie($conn, $username) {

    $sql = "SELECT id, role FROM users WHERE username = ?";

   
    $stmt = $conn->prepare($sql);

    if ($stmt) {

        
        $stmt->bind_param("s", $username);

      
        $stmt->execute();

        
        $result = $stmt->get_result();

        // Kontrola, či sa našiel práve jeden používateľ s daným menom
        if ($result->num_rows == 1) {
            // Vrátenie údajov používateľa ako asociatívne pole
            return $result->fetch_assoc();
        } else {
            // Ak sa nenašiel žiadny alebo viacero používateľov, vrátime null
            return null;
        }
    } else {
        // Ak sa nepodarilo pripraviť dopyt, vráti null
        return null; 
    }
}


    

// Funkcia na upload súborov
function uploadFile($fieldName, $targetDir = '../img/', $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']) {

    // Kontrola, či bol súbor vybraný a úspešne nahraný
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null; 
    }

    // Cesta k cieľovému súboru (vrátane názvu)
    $targetFile = $targetDir . basename($_FILES[$fieldName]["name"]);

    // Získanie prípony súboru (v malých písmenách) ,PATHINFO - konštanta, chceme len príponu súboru
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Overenie, či je súbor skutočne obrázok (pomocou getimagesize())
    $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
    if ($check === false) {
        return ['error' => 'Súbor nie je obrázok.'];
    }

    // Overenie, či súbor s rovnakým menom už existuje
    if (file_exists($targetFile)) {
        // Generovanie unikátneho názvu súboru ak už existuje
        $originalName = pathinfo($_FILES[$fieldName]["name"], PATHINFO_FILENAME);
        $counter = 1;
        while (file_exists($targetFile)) {
            $targetFile = $targetDir . $originalName . "_" . $counter . "." . $imageFileType;
            $counter++;
        }
    }

    // Overenie veľkosti súboru
    if ($_FILES[$fieldName]["size"] > 500000) { 
        return ['error' => 'Súbor je príliš veľký.'];
    }

    // Overenie typu/prípony súboru
    if (!in_array($imageFileType, $allowedExtensions)) {
        return ['error' => 'Nepovolený formát súboru.'];
    }

    // Presunutie nahraného súboru z dočasného umiestnenia do cieľového adresára
    if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $targetFile)) {
        return basename($targetFile); // Vrátenie názvu úspešne nahraného súboru
    } else {
        return ['error' => 'Vyskytla sa chyba pri nahrávaní súboru.'];
    }
}


