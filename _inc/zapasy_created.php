
<?php

require_once 'functions.php';
require_once 'MatchRepository.php';
require_once 'Zapas.php';

// Trieda pre zápasy vytvorené používateľom
class UserCreatedZapas extends Zapas {
    // Atribúty špecifické pre zápasy vytvorené používateľom
    public $h2hData;      // Pole s údajmi o H2H (dátum, liga, zápas, výsledok)
    public $previewData; // Pole s údajmi o preview (text, kurzy)

    
    public function __construct($time, $team1, $team2, $logo1, $logo2, $h2hData, $previewData, $competition) {
        // Zavolanie konštruktora rodičovskej triedy Zapas
        parent::__construct($time, $team1, $team2, $logo1, $logo2, null, null, $competition);

        
        $this->h2hData = $h2hData;
        $this->previewData = $previewData;
    }

    // Funkcia na zobrazenie zápasov
    public function display() {
        // Vykreslenie základných informácií o zápase (čas, tímy, logá, súťaž)
        parent::display();
    
        $h2hOutput = "<div class='zapasy_hry_H2H'>";
        $h2hOutput .= "<h3>H2H</h3>";
        if (!empty($this->h2hData)) {
            $h2hOutput .= "<p>Dátum: {$this->h2hData['h2h_date']}</p>";
            $h2hOutput .= "<p>Liga: {$this->h2hData['h2h_league']}</p>";
            $h2hOutput .= "<p>Zápas: {$this->h2hData['h2h_match']}</p>";
            $h2hOutput .= "<p>Výsledok: {$this->h2hData['h2h_result']}</p>";
        } else {
            $h2hOutput .= "<p>Nie sú k dispozícii žiadne dáta H2H</p>";
        }
        $h2hOutput .= "</div>";
        echo $h2hOutput; // volanie textovej premennej
    
        
        $previewOutput = "<div class='zapasy_hry_analyza'>";
        $previewOutput .= "<h3>Preview</h3>";
        if (!empty($this->previewData)) {
            $previewOutput .= "<p>{$this->previewData['preview_text']}</p>";
            $previewOutput .= "<p>Kurz na výhru tímu 1: {$this->previewData['preview_odds_win_1']}</p>";
            $previewOutput .= "<p>Kurz na remízu: {$this->previewData['preview_odds_draw']}</p>";
            $previewOutput .= "<p>Kurz na výhru tímu 2: {$this->previewData['preview_odds_win_2']}</p>";
        } else {
            $previewOutput .= "<p>Nie je k dispozícii žiadny náhľad</p>";
        }
        $previewOutput .= "</div>";
        echo $previewOutput; 
    }
    

    // Funkcia na zobrazenie bez loga - user zápasy
    public function displayWithoutLogos() {
        static $matchCounter = 1; 
    
        $h2hDisplay = '';
        $previewDisplay = '';
    
       
        if (!empty($this->h2hData)) {
            $h2hDisplay .= "
                <p class='match-detail'>Dátum: <span class='user-created-data'>{$this->h2hData['h2h_date']}</span></p>
                <p class='match-detail'>Liga: <span class='user-created-data'>{$this->h2hData['h2h_league']}</span></p>
                <p class='match-detail'>Zápas: <span class='user-created-data'>{$this->h2hData['h2h_match']}</span></p>
                <p class='match-detail'>Výsledok: <span class='user-created-data'>{$this->h2hData['h2h_result']}</span></p>";
        } else {
            $h2hDisplay = "<p class='match-detail'>Nie sú k dispozícii žiadne dáta H2H</p>";
        }
    
        
        if (!empty($this->previewData)) {
            $previewDisplay .= "
                <p class='match-detail'><span class='user-created-data'>{$this->previewData['preview_text']}</span></p>
                <p class='match-detail'>Kurz na výhru tímu 1: <span class='user-created-data'>{$this->previewData['preview_odds_win_1']}</span></p>
                <p class='match-detail'>Kurz na remízu: <span class='user-created-data'>{$this->previewData['preview_odds_draw']}</span></p>
                <p class='match-detail'>Kurz na výhru tímu 2: <span class='user-created-data'>{$this->previewData['preview_odds_win_2']}</span></p>";
        } else {
            $previewDisplay = "<p class='match-detail'>Nie je k dispozícii žiadny náhľad</p>";
        }
        
        // Overenie existencie loga prvého tímu. Ak neexistuje, použijeme predvolený obrázok 'default.png'.
        $logoPath1 = !empty($this->logo1) ? "img/{$this->logo1}" : 'img/default.png';
        $logoPath2 = !empty($this->logo2) ? "img/{$this->logo2}" : 'img/default.png';
    

        // Výpis celého HTML naraz ,  heredoc 
        echo <<<HTML
        <div class='match-container'>
            <div class='match-time'><p class='match-detail'>{$this->time}</p></div> 
            <div class='match-teams'>
                <div class='team-with-logo'>
                    <img src='{$logoPath1}' alt='Logo of {$this->team1}' class='team-logo'>
                    <p class='team-name'>{$this->team1}</p>
                </div>
                <div class='team-with-logo'>
                    <img src='{$logoPath2}' alt='Logo of {$this->team2}' class='team-logo'>
                    <p class='team-name'>{$this->team2}</p>
                </div>
            </div>
            <div class='match-data'>
                <div class='match-h2h-$matchCounter'>
                    <h3 class='user-created-heading'>H2H</h3>
                    $h2hDisplay
                </div>
                <div class='match-preview-$matchCounter'>
                    <h3 class='user-created-heading'>Preview</h3>
                    $previewDisplay
                </div>
            </div>
            
        </div>
    HTML;
    
        $matchCounter++;
    }
    
    
    
    
    
        
    
    
}
