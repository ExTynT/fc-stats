<?php
include_once '../_inc/News.php';

class NewsRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Funkcia, ktorá získa články (novinky) podľa kategórie z databázy.
    public function getNewsByCategory($category = null, $limit = 3) {
        // Základný SQL dopyt na výber všetkých stĺpcov z tabuľky "news"
        $sql = "SELECT * FROM news";
    
        // Ak je zadaná kategória, pridáme do dopytu podmienku pre filtrovanie podľa kategórie
        if ($category !== null) {
            $sql .= " WHERE category = '$category'";
        }
    
        // Pridanie zoradenia podľa dátumu vytvorenia (od najnovších po najstaršie) a obmedzenie počtu výsledkov
        $sql .= " ORDER BY created_at DESC LIMIT ?";
    
        // Príprava pripraveného výrazu (prepared statement) na bezpečné vykonanie dopytu
        $stmt = $this->conn->prepare($sql);
    
        // Kontrola, či sa podarilo pripraviť výraz
        if ($stmt === false) {
            // Ak nie, ukončenie skriptu s chybovým hlásením
            die("Chyba pri príprave dopytu: " . $this->conn->error);
        }
    
        // Naviazanie parametra do pripraveného výrazu
        // Ak je zadaná kategória, naviažeme len parameter $limit
        // Ak nie je zadaná kategória, naviažeme parameter $category (ktorý bude null) aj $limit
        if ($category !== null) {
            $stmt->bind_param("i", $limit); 
        } else {
            $stmt->bind_param("si", $category, $limit); 
        }
    
        // Vykonanie dopytu s naviazanými parametrami
        $stmt->execute();
    
        // Získanie výsledkov dopytu
        $result = $stmt->get_result();
    
        // Inicializácia prázdneho poľa pre uloženie získaných článkov
        $newsItems = [];
    
        // Prechádzanie výsledkami dopytu a vytvorenie objektov News pre každý článok
        while ($row = $result->fetch_assoc()) {
            $newsItems[] = new News($row); 
        }
    
        // Vrátenie poľa s objektami News
        return $newsItems;
    }




// Získa komentár podľa jeho ID z databázy.
public function getCommentById($commentId) {

    // SQL dopyt na získanie údajov o komentári z tabuľky `comments` (c)
    // JOIN s tabuľkou `users` (u) pre získanie používateľského mena autora komentára
    $sql = "SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?";

    // Príprava pripraveného výrazu (prepared statement) na bezpečné vykonanie dopytu
    $stmt = $this->conn->prepare($sql);

    // Naviazanie parametra do pripraveného výrazu
    // "i" znamená, že parameter je integer (celé číslo)
    $stmt->bind_param("i", $commentId);

    // Vykonanie dopytu
    $stmt->execute();

    // Získanie výsledkov dopytu
    $result = $stmt->get_result();

    // Kontrola, či sa našiel presne jeden komentár
    if ($result->num_rows == 1) {

        // Získanie údajov komentára ako asociatívneho poľa
        $row = $result->fetch_assoc();

        // Vytvorenie nového objektu Comment z získaných údajov a jeho vrátenie
        return new Comment($row); 
    } else {
        // Ak sa komentár nenašiel, vráti null
        return null; 
    }
}




// Zmaže komentár z databázy na základe jeho ID.
public function deleteComment($commentId) {

    // 1. Príprava SQL dopytu na odstránenie komentára
    $sql = "DELETE FROM comments WHERE id = ?"; // Vytvorenie SQL príkazu, ktorý zmaže riadok z tabuľky "comments", kde sa hodnota stĺpca "id" zhoduje s $commentId

    // 2. Príprava bezpečného vykonania dopytu
    $stmt = $this->conn->prepare($sql); // Vytvorenie pripraveného vyhlásenia pre SQL dopyt, čo pomáha predchádzať SQL injection útokom

    // 3. Nahradenie zástupného symbolu v SQL dopyte hodnotou $commentId, ktorý je typu integer (i)
    $stmt->bind_param("i", $commentId); 

    // 4. Overenie oprávnení používateľa na zmazanie komentára

    // Získanie informácií o komentári vrátane ID jeho autora pomocou funkcie getCommentById()
    $comment = $this->getCommentById($commentId); 

    // Kontrola, či komentár existuje (getCommentById vráti null, ak nie) a či má používateľ právo ho zmazať
    if ($comment && (
            // Prvá podmienka: Overenie, či je ID používateľa, ktorý sa snaží komentár zmazať, rovnaké ako ID autora komentára
            $comment->user_id == $_SESSION['user_id'] || 

            // Druhá podmienka: Overenie, či je používateľ, ktorý sa snaží komentár zmazať, administrátor
            (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') // Skontroluje sa, či je v session premennej 'user_role' nastavená hodnota 'admin'
        )) {
        
        // Ak je splnená aspoň jedna z podmienok, môže sa komentár zmazať
        
        // 5. Vykonanie dopytu na zmazanie
        $success = $stmt->execute();  // Výsledok vykonania dopytu sa uloží do premennej $success (true, ak úspešné, false, ak neúspešné)
    } else {
        // Ak používateľ nemá oprávnenie na zmazanie, vráti sa 'unauthorized'
        return 'unauthorized'; 
    }

    // 6. Ukončenie práce s pripraveným vyhlásením
    $stmt->close(); 

    // 7. Vrátenie výsledku akcie
    // Ternárny operátor vráti 'deleted' ak bol komentár úspešne zmazaný ($success je true), inak vráti 'not_deleted' 
    return $success ? 'deleted' : 'not_deleted'; 
}








    //Aktualizuje obsah komentára v databáze.
    public function updateComment($commentId, $newContent) {
    
        // 1. Príprava SQL dopytu na aktualizáciu obsahu komentára
        $sql = "UPDATE comments SET content = ? WHERE id = ?"; // Vytvorenie SQL príkazu, ktorý aktualizuje obsah stĺpca `content` v tabuľke `comments` pre riadok s daným `id`
    
        // 2. Príprava pripraveného výrazu (prepared statement) na bezpečné vykonanie dopytu
        $stmt = $this->conn->prepare($sql); 
    
        // Kontrola, či sa podarilo pripraviť dopyt
        if (!$stmt) {
            // Ak nie, zapíše sa chybová správa do error logu (zvyčajne súbor na serveri)
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
    
            // Vráti sa false, čo indikuje neúspešnú aktualizáciu
            return false;
        }
    
        // 3. Naviazanie parametrov do pripraveného výrazu
        $stmt->bind_param("si", $newContent, $commentId); // "si" znamená, že prvý parameter je reťazec (nový obsah) a druhý je integer (ID komentára)
    
        // 4. Vykonanie dopytu a kontrola úspešnosti
        if ($stmt->execute()) { 
            // Ak sa dopyt vykonal úspešne, zatvorí sa pripravený výraz a vráti sa true
            $stmt->close();
            return true;
        } else {
            // Ak sa vyskytla chyba pri vykonávaní dopytu, zapíše sa chybová správa do error logu
            error_log("Chyba pri aktualizácii komentára: " . $stmt->error);
    
            // Vráti sa false, čo indikuje neúspešnú aktualizáciu
            return false;
        }
    }







    //Získa ID používateľa na základe jeho používateľského mena.
    public function getUserIdByUsername($username) {

        // 1. Príprava SQL dopytu
        $sql = "SELECT id FROM users WHERE username = ?"; // Dopyt na získanie ID používateľa z tabuľky `users` na základe zadaného používateľského mena
    
        // 2. Príprava pripraveného výrazu (prepared statement)
        $stmt = $this->conn->prepare($sql); // Príprava dopytu na bezpečné vykonanie, chráni pred SQL injection
    
        // Kontrola, či sa podarilo pripraviť dopyt
        if (!$stmt) {
            // Ak nie, zapíšeme chybu do error logu a vrátime null
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
            return null;
        }
    
        // 3. Naviazanie parametrov
        $stmt->bind_param("s", $username); // Nahradenie zástupného symbolu "?" v dopyte skutočným používateľským menom
    
        // 4. Vykonanie dopytu
        $stmt->execute();
    
        // Získanie výsledkov dopytu
        $result = $stmt->get_result();
    
        // Kontrola, či bol nájdený práve jeden používateľ
        if ($result->num_rows == 1) {
            // Získanie ID používateľa z výsledku a vrátenie tejto hodnoty
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            // Ak sa nenašiel žiadny alebo viacero používateľov s daným menom, vráti null
            return null; 
        }
    }


    // Získa všetky články z databázy.
    public function getAllArticles() {

        // 1. Príprava SQL dopytu
        // Vyberieme len id a title, pretože tieto údaje sú potrebné pre výber článku v ďalšej časti kódu. 
        // Obsah článku sa nenačítava, aby sa optimalizoval výkon a znížilo zaťaženie databázy.
        $sql = "SELECT id, title FROM news";
    
        // 2. Vykonanie dopytu
        $result = $this->conn->query($sql); // Výsledok dopytu sa uloží do premennej $result
    
        // 3. Vytvorenie poľa článkov
        $articles = []; // Inicializácia prázdneho poľa pre články
    
        // 4. Prechádzanie výsledkami dopytu a uloženie údajov do poľa $articles
        while ($row = $result->fetch_assoc()) { 
            // fetch_assoc() vráti riadok z výsledku dopytu ako asociatívne pole (kľúče sú názvy stĺpcov)
            // Každý článok (riadok) sa pridá do poľa $articles
            $articles[] = $row; 
        }
    
        // 5. Vrátenie výsledku
        return $articles; // Vráti sa pole $articles, ktoré obsahuje asociatívne polia pre každý článok, pričom každé pole má dve položky: 'id' a 'title'
    }
   




    // Získanie článku podľa ID
    public function getArticleById($articleId) {

        // 1. Príprava SQL dopytu
        $sql = "SELECT id, title, content FROM news WHERE id = ?"; // Vyberieme iba stĺpce id, title a content, pretože tieto údaje sú potrebné pre zobrazenie článku
    
        // 2. Príprava pripraveného výrazu (prepared statement)
        $stmt = $this->conn->prepare($sql); // Príprava dopytu na bezpečné vykonanie, chráni pred SQL injection
    
        // 3. Naviazanie parametrov
        $stmt->bind_param("i", $articleId); // Nahradenie zástupného symbolu "?" v dopyte skutočným ID článku
    
        // 4. Vykonanie dopytu
        $stmt->execute();
    
        // Získanie výsledkov dopytu
        $result = $stmt->get_result();
    
        // 5. Vrátenie výsledku
        // Ak bol dopyt úspešný a našiel sa článok s daným ID, vrátime asociatívne pole s jeho údajmi (id, title, content)
        // Ak sa článok nenašiel, vrátime null
        return $result->fetch_assoc(); // Vráti sa asociatívne pole s údajmi článku alebo null, ak sa článok nenašiel
    
    }




    public function updateArticle($articleId, $newTitle, $newContent) {

        // 1. Príprava SQL dopytu na aktualizáciu článku
        $sql = "UPDATE news SET title = ?, content = ? WHERE id = ?";
    
        // 2. Príprava pripraveného výrazu (prepared statement)
        $stmt = $this->conn->prepare($sql);
    
        // Kontrola, či sa podarilo pripraviť dopyt
        if (!$stmt) {
            // Ak nie, zapíše sa chybová správa do error logu (zvyčajne súbor na serveri)
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
            return false; // Vráti false, čo indikuje neúspešnú aktualizáciu
        }
    
        // 3. Naviazanie parametrov do pripraveného výrazu
        $stmt->bind_param("ssi", $newTitle, $newContent, $articleId);
        // "ssi" znamená, že prvé dva parametre sú reťazce (nový nadpis a obsah) a tretí je integer (ID článku)
    
        // 4. Vykonanie dopytu a kontrola úspešnosti
        if ($stmt->execute()) { // Ak sa dopyt vykonal úspešne, zatvorí sa prepared statement a vráti sa true
            $stmt->close();
            return true;
        } else {
            // Ak sa vyskytla chyba pri vykonávaní dopytu, zapíše sa chybová správa do error logu
            error_log("Chyba pri aktualizácii článku: " . $stmt->error);
            return false; // Vráti false, čo indikuje neúspešnú aktualizáciu
        }
    }

    // Metóda na získanie chybového hlásenia (pre sendErrorResponse)
    public function getErrorMessage() {
        return $this->conn->error;
    }

}    

?>