<?php

// Funkcia na vytvorenie spojenia s databázou MySQL
function dbConnect() {

    // Prihlasovacie údaje k databáze
    $servername = "localhost";    // Adresa servera, kde beží MySQL (predvolený - localhost)
    $username = "root";          // Užívateľské meno pre prístup k databáze (predvolený - root)
    $password = "";              // Heslo pre prístup k databáze (predvolené - prázdne)
    $dbname = "fc_stats";        // Názov databázy, ku ktorej sa chceme pripojiť

    // Vytvorenie spojenia s databázou pomocou funkcie mysqli_connect
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Kontrola, či sa spojenie podarilo vytvoriť
    if ($conn->connect_error) {
        // Ak sa spojenie nepodarilo, skript sa ukončí a zobrazí sa chybové hlásenie
        die("Spojenie s databázou zlyhalo: " . $conn->connect_error);
    }

    // Ak je spojenie úspešné, funkcia vráti objekt spojenia ($conn), ktorý môžeme použiť na vykonávanie dopytov a iných operácií s databázou
    return $conn;
}




// Funkcia na výpis náhľadu zápasu a kurzov
function outputPreviewData($conn, $previewNumber) {
    // Dynamické určenie názvu tabuľky v databáze podľa čísla zápasu (previewNumber)
    // Tabuľky sú pomenované ako "preview_1", "preview_2" atď
    $table = "preview_" . $previewNumber; 

    // Vytvorenie SQL dopytu pre výber údajov z tabuľky
    // Dynamicky sa menia názvy stĺpcov podľa čísla zápasu,
    // takže sa vyberú správne údaje pre konkrétny zápas.
    $sql = "SELECT 
                preview_{$previewNumber}_text AS preview_text, 
                preview_{$previewNumber}_odds_win_1 AS odds_win_1, 
                preview_{$previewNumber}_odds_win_2 AS odds_win_2, 
                preview_{$previewNumber}_odds_draw AS odds_draw
            FROM $table";

    // Odoslanie dopytu do databázy a získanie výsledku
    $result = $conn->query($sql);

    // Kontrola, či dopyt vrátil nejaké výsledky a či sa vyskytli nejaké chyby
    if ($result && $result->num_rows > 0) {
        // Získanie prvého (a jediného očakávaného) riadku výsledku dopytu ako asociatívne pole
        $row = $result->fetch_assoc(); 

        // Výpis textu náhľadu zápasu do odseku <p>
        echo "<p>" . $row["preview_text"] . "</p>"; 

        // Začiatok HTML tabuľky pre zobrazenie kurzov
        echo "<table class='preview_1_odds'>";
        echo "<tr>"; // Prvý riadok tabuľky - hlavička s názvami tímov

        // Dynamické nastavenie názvov tímov v hlavičke tabuľky podľa čísla zápasu
        if ($previewNumber == 1) {
            echo "<th>Barcelona</th><th>Remíza</th><th>PSG</th>";
        } elseif ($previewNumber == 2) {
            echo "<th>BVB Dortmund</th><th>Remíza</th><th>Atletico Madrid</th>";
        } elseif ($previewNumber == 3) {
            echo "<th>Atalanta</th><th>Remíza</th><th>Leverkusen</th>";
        }

        echo "</tr>"; // Koniec hlavičky tabuľky
        echo "<tr>";  // Začiatok riadku s kurzami

        // Výpis kurzov pre výhru 1. tímu, remízu a výhru 2. tímu
        echo "<td>" . $row["odds_win_1"] . "</td>";
        echo "<td>" . $row["odds_draw"] . "</td>";
        echo "<td>" . $row["odds_win_2"] . "</td>";

        echo "</tr>"; // Koniec riadku s kurzami
        echo "</table>"; // Koniec tabuľky
    } else {
        // Ak dopyt nevrátil žiadne výsledky alebo nastala chyba,
        // vypíšu sa informácie o nedostupnosti náhľadu a kurzov
        echo "<p>No preview available.</p>"; 
        echo "<table class='preview_1_odds'><td colspan='3'>No odds available</td></table>"; 
    }
}



// Funkcia na výpis H2H (head-to-head) údajov
function outputH2HData($conn, $tableNumber) {
    // Dynamické určenie názvu tabuľky v databáze podľa čísla zápasu
    // Tabuľky sú pomenované ako "h2h_1", "h2h_2" atď.
    $table = "h2h_" . $tableNumber;

    // Vytvorenie SQL dopytu pre výber všetkých údajov z tabuľky
    $sql = "SELECT * FROM $table";

    // Odoslanie dopytu do databázy a získanie výsledku
    $result = $conn->query($sql);

    // Kontrola, či dopyt bol úspešný
    if ($result) {
        // Kontrola, či dopyt vrátil nejaké výsledky
        if ($result->num_rows > 0) {
            // Ak áno, prechádzame cez jednotlivé riadky výsledku
            while ($row = $result->fetch_assoc()) {
                // Výpis údajov z každého riadku do HTML tabuľky
                echo "<tr>"; // Začiatok nového riadku tabuľky

                // Výpis dátumu zápasu (predpokladá sa stĺpec s názvom "H2H_{$tableNumber}_Datum")
                echo "<td class='datum'>" . $row["H2H_{$tableNumber}_Datum"] . "</td>";

                // Výpis ligy, v ktorej sa zápas odohral (predpokladá sa stĺpec s názvom "H2H_{$tableNumber}_Liga")
                echo "<td class='liga'>" . $row["H2H_{$tableNumber}_Liga"] . "</td>";

                // Výpis názvov tímov, ktoré hrali zápas (predpokladá sa stĺpec s názvom "H2H_{$tableNumber}_Zapas")
                echo "<td class='team_1'>" . $row["H2H_{$tableNumber}_Zapas"] . "</td>";

                // Výpis výsledku zápasu (predpokladá sa stĺpec s názvom "H2H_{$tableNumber}_Vysledok")
                echo "<td class='vysledok'>" . $row["H2H_{$tableNumber}_Vysledok"] . "</td>";

                echo "</tr>"; // Koniec riadku tabuľky
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
    // Vytvorenie spojenia s databázou pomocou funkcie dbConnect()
    $conn = dbConnect();

    // Vytvorenie SQL dopytu pre výber všetkých stĺpcov a riadkov z tabuľky "matches_table"
    $sql = "SELECT * FROM matches_table";

    // Odoslanie dopytu do databázy a získanie výsledku
    $result = $conn->query($sql);

    // Inicializácia prázdneho poľa pre uloženie získaných údajov o zápasoch
    $matches = [];

    // Kontrola, či dopyt vrátil nejaké výsledky
    if ($result->num_rows > 0) {
        // Ak áno, prechádzame cez jednotlivé riadky výsledku
        while ($row = $result->fetch_assoc()) {
            // Každý riadok (reprezentujúci jeden zápas) sa pridá do poľa $matches ako asociatívne pole
            $matches[] = $row; 
        }
    }

    // Uzatvorenie spojenia s databázou, aby sa uvoľnili zdroje
    $conn->close();

    // Vrátenie poľa $matches, ktoré obsahuje údaje o všetkých zápasoch z databázy
    return $matches;
}



// Funkcia na vytvorenie HTML kódu pre odkaz na H2H (Head-to-Head) stránku zápasu.
function outputH2HLink($link) {

    // Začiatok kontajneru pre odkaz s triedou CSS pre štýlovanie.
    echo "<div class='zapasy_hry_H2H'>"; 

    // Výpis odkazu s textom "H2H".
    echo "  <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=355px,height=210px'); return false;\">H2H</a></p>";
    
    // Koniec kontajneru pre odkaz.
    echo "</div>";  
}

// Funkcia na vytvorenie HTML kódu pre odkaz na preview zápasu.
function outputPreviewLink($link) {

    // Začiatok kontajneru pre odkaz s triedou CSS pre štýlovanie.
    echo "<div class='zapasy_hry_analyza'>";

    // Výpis odkazu s textom "Preview".
    echo "  <p><a href='{$link}' onclick=\"window.open(this.href, '_blank', 'width=540px,height=800px'); return false;\">Preview</a></p>";

    // Koniec kontajneru pre odkaz.
    echo "</div>";
}


class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getInfo($userId) {
        // Príprava SQL dopytu na získanie mena a role používateľa z tabuľky "users" podľa ID
        // Používa sa pripravený výraz (prepared statement) na zabránenie SQL injection útokom
        $stmt = $this->conn->prepare("SELECT username, role FROM users WHERE id = ?");

        // Kontrola, či sa podarilo pripraviť dopyt
        if ($stmt) {
            // Naviazanie parametra do pripraveného dopytu (ID používateľa)
            // "i" znamená, že parameter je integer (celé číslo)
            $stmt->bind_param("i", $userId);

            // Vykonanie dopytu
            $stmt->execute();

            // Získanie výsledkov dopytu
            $result = $stmt->get_result();

            // Vrátenie výsledkov dopytu ako asociatívneho poľa
            // Ak sa používateľ nenašiel, vráti null
            return $result->fetch_assoc(); 
        } else {
            // Ak sa nepodarilo pripraviť dopyt, vráti null (môžete pridať logovanie chýb)
            return null;
        }
    }
}


// Funkcia na získanie údajov o užívateľovi z cookies
function getUserInfoFromCookie($conn, $username) {

    // Príprava SQL dopytu na získanie ID a role používateľa z tabuľky `users` podľa jeho používateľského mena
    // Používa sa pripravený výraz (prepared statement) na zabránenie SQL injection útokom
    $sql = "SELECT id, role FROM users WHERE username = ?";

    // Príprava vyhlásenia pre dopyt
    $stmt = $conn->prepare($sql);

    // Kontrola, či sa podarilo pripraviť dopyt
    if ($stmt) {

        // Naviazanie parametra do pripraveného dopytu
        // "s" znamená, že parameter je reťazec (string)
        $stmt->bind_param("s", $username);

        // Vykonanie dopytu
        $stmt->execute();

        // Získanie výsledkov dopytu
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