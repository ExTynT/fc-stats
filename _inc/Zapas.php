<?php
class Zapas {
    // Verejné vlastnosti triedy (atribúty zápasu)
    public $time;         // Čas zápasu 
    public $team1;        // Názov prvého tímu
    public $team2;        // Názov druhého tímu
    public $logo1;        // Názov súboru loga prvého tímu 
    public $logo2;        // Názov súboru loga druhého tímu
    public $h2h;          // Odkaz na stránku s H2H štatistikami
    public $preview;      // Odkaz na stránku s náhľadom (preview) zápasu
    public $competition;  // Názov súťaže 


    public function __construct($time, $team1, $team2, $logo1, $logo2, $h2h, $preview, $competition) {
        // Priradenie parametrov k vlastnostiam objektu
        $this->time = $time;
        $this->team1 = $team1;
        $this->team2 = $team2;
        $this->logo1 = $logo1;
        $this->logo2 = $logo2;
        $this->h2h = $h2h;
        $this->preview = $preview;
        $this->competition = $competition;
    }

    //Vykreslí HTML reprezentáciu zápasu.
    public function display() {
        // Kontrola existencie loga prvého tímu. Ak neexistuje, použije sa predvolený obrázok 'default.png'.
        $logoPath1 = file_exists("img/$this->logo1") ? "img/$this->logo1" : 'img/default.png';
        // To isté pre logo druhého tímu.
        $logoPath2 = file_exists("img/$this->logo2") ? "img/$this->logo2" : 'img/default.png';

        // Začiatok HTML výstupu
        echo "<div class='zapas'>"; // Kontajner pre celý zápas

        // Výpis času zápasu
        echo "<h3>$this->time</h3>"; 

        // Výpis informácií o prvom tíme (logo a názov)
        echo "<div class='zapas_left'>";
        echo "<img src='$logoPath1' alt='$this->team1'>";
        echo "<p>$this->team1</p>";
        echo "</div>";

        // Výpis informácií o druhom tíme (logo a názov)
        echo "<div class='zapas_right'>";
        echo "<img src='$logoPath2' alt='$this->team2'>";
        echo "<p>$this->team2</p>";
        echo "</div>";

        // Výpis odkazov na H2H a preview zápasu
        echo "<div class='zapas_links'>";
        echo "<a href='$this->h2h'>H2H</a>";
        echo "<a href='$this->preview'>Preview</a>";
        echo "</div>";

        echo "</div>"; // Koniec kontajnera pre zápas
    }
}
