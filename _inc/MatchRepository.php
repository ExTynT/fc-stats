<?php

// Absolútna cesta ku koreňovému adresáru 
require_once $_SERVER['DOCUMENT_ROOT'] . '/_inc/zapasy_created.php';


// Trieda pre používateľmi vytvorené zápasy
class MatchRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Funckia na vytvorenie zápasu
    public function createMatch(UserCreatedZapas $zapas) {
        
        $sql = "INSERT INTO matches_table (time, team1, team2, logo1, logo2, competition) 
                VALUES (?, ?, ?, ?, ?, ?)"; // Použitie zástupných znakov (?) pre bezpečnosť.
    
        
        $stmt = $this->conn->prepare($sql);
    
        // Kontrola, či sa podarilo pripraviť dopyt.
        if (!$stmt) {
            throw new Exception("Chyba pri príprave dopytu: " . $this->conn->error);
        }
    
        // Kontrola, či sú logá tímov reťazce (nie polia), a ich nastavenie na null, ak nie sú.
        $logo1 = is_array($zapas->logo1) ? null : $zapas->logo1;
        $logo2 = is_array($zapas->logo2) ? null : $zapas->logo2;
    
        
        $stmt->bind_param("ssssss", $zapas->time, $zapas->team1, $zapas->team2, $logo1, $logo2, $zapas->competition);
    
        // Vykonanie dopytu a kontrola úspešnosti.
        if ($stmt->execute()) {
            // Získanie ID vloženého zápasu.
            $matchId = $this->conn->insert_id;
    
            // Vytvorenie H2H dát a dát pre ukážku zápasu.
            $this->createH2hData($matchId, $zapas->h2hData);
            $this->createPreviewData($matchId, $zapas->previewData);
    
            // Vrátenie ID vloženého zápasu.
            return $matchId;
        } else {
            // Vyvolanie výnimky, ak sa vloženie nepodarilo.
            throw new Exception("Chyba pri vkladaní zápasu: " . $stmt->error);
        }
    }
    
  

    // Funckia na vytvorenie H2H
    public function createH2hData($matchId, $h2hData) {
        $sql = "INSERT INTO h2h_table (match_id, h2h_date, h2h_league, h2h_match, h2h_result)
                VALUES (?, ?, ?, ?, ?)"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", 
            $matchId,
            $h2hData['date'], 
            $h2hData['league'],
            $h2hData['match'],
            $h2hData['result']
        );
        if (!$stmt->execute()) {
            throw new Exception("Chyba pri pridávaní H2H údajov: " . $stmt->error);
        }
    }


    // Funkcia na vytvorenie Preview
    public function createPreviewData($matchId, $previewData) {
        $sql = "INSERT INTO previews_table (match_id, preview_text, preview_odds_win_1, preview_odds_draw, preview_odds_win_2) 
                VALUES (?, ?, ?, ?, ?)"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isddd", 
            $matchId, 
            $previewData['text'], 
            $previewData['odds_win_1'], 
            $previewData['odds_draw'], 
            $previewData['odds_win_2']
        ); 
        if (!$stmt->execute()) {
            throw new Exception("Chyba pri pridávaní preview údajov: " . $stmt->error);
        }
    }
   

    
    // Funkcia na získanie posledne vloženého ID
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    // Funkcia na získanie informácií o všetkých zápasoch
    public function getAllMatches() {
        $sql = "SELECT * FROM matches_table";
        $result = $this->conn->query($sql);
    
        $matches = [];
        while ($row = $result->fetch_assoc()) {
            $h2hData = $this->getH2hDataForMatch($row['id']);
            $previewData = $this->getPreviewDataForMatch($row['id']);
    
            $matches[] = new UserCreatedZapas(
                $row['time'],
                $row['team1'],
                $row['team2'],
                $row['logo1'],
                $row['logo2'],
                $h2hData,  
                $previewData, 
                $row['competition']
            );
        }
        return $matches;
    }




    // Získanie H2H dát pre zápas
    public function getH2hDataForMatch($matchId) {
        $sql = "SELECT * FROM h2h_table WHERE match_id = ?"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $matchId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : []; 
    }

    // Získanie Preview dát pre zápas
    public function getPreviewDataForMatch($matchId) {
        $sql = "SELECT * FROM previews_table WHERE match_id = ?"; 
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $matchId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : []; 
    }



}
