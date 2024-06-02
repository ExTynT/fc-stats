<?php
include_once '../_inc/News.php';



// Trieda pre správy
class NewsRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Funkcia, ktorá získa články podľa kategórie z databázy.
    public function getNewsByCategory($category = null, $limit = 3) {
        
        $sql = "SELECT * FROM news";
    
        // Ak je zadaná kategória, pridáme do dopytu podmienku pre filtrovanie podľa kategórie
        if ($category !== null) {
            $sql .= " WHERE category = '$category'";
        }
    
        // Pridanie zoradenia podľa dátumu vytvorenia (od najnovších po najstaršie) a obmedzenie počtu výsledkov
        $sql .= " ORDER BY created_at DESC LIMIT ?";
    
       
        $stmt = $this->conn->prepare($sql);
    
        // Kontrola, či sa podarilo pripraviť výraz
        if ($stmt === false) {
            
            die("Chyba pri príprave dopytu: " . $this->conn->error);
        }
    

        if ($category !== null) {
            $stmt->bind_param("i", $limit); 
        } else {
            $stmt->bind_param("si", $category, $limit); 
        }
    
        
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $newsItems = [];
    
        // Prechádzanie výsledkami dopytu a vytvorenie objektov News pre každý článok
        while ($row = $result->fetch_assoc()) {
            $newsItems[] = new News($row); 
        }
    
       
        return $newsItems;
    }




// Funkcia, ktorá získa komentár podľa jeho ID z databázy.
public function getCommentById($commentId) {

    $sql = "SELECT c.*, u.username 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?";


    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param("i", $commentId);

    $stmt->execute();

    $result = $stmt->get_result();

    // Kontrola, či sa našiel presne jeden komentár
    if ($result->num_rows == 1) {

        
        $row = $result->fetch_assoc();

        // Vytvorenie nového objektu Comment z získaných údajov a jeho vrátenie
        return new Comment($row); 
    } else {
        
        return null; 
    }
}




// Funckia, ktorá zmaže komentár z databázy na základe jeho ID.
public function deleteComment($commentId) {

    $sql = "DELETE FROM comments WHERE id = ?"; 

    $stmt = $this->conn->prepare($sql); 

    $stmt->bind_param("i", $commentId); 

    // Overenie oprávnení používateľa na zmazanie komentára

    // Získanie informácií o komentári vrátane ID jeho autora 
    $comment = $this->getCommentById($commentId); 

    // Kontrola, či komentár existuje (getCommentById vráti null, ak nie) a či má používateľ právo ho zmazať
    if ($comment && (
            // Prvá podmienka: Overenie, či je ID používateľa, ktorý sa snaží komentár zmazať, rovnaké ako ID autora komentára
            $comment->user_id == $_SESSION['user_id'] || 

            // Druhá podmienka: Overenie, či je používateľ, ktorý sa snaží komentár zmazať, administrátor
            (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')
        )) {
        
        // Ak je splnená aspoň jedna z podmienok, môže sa komentár zmazať
        
        // Vykonanie dopytu na zmazanie
        $success = $stmt->execute();  
    } else {
       
        return 'unauthorized'; 
    }

    
    $stmt->close(); 
 
    return $success ? 'deleted' : 'not_deleted'; 
}








    // Funkcia, ktorá aktualizuje obsah komentára v databáze
    public function updateComment($commentId, $newContent) {
    
      
        $sql = "UPDATE comments SET content = ? WHERE id = ?"; 
    
        
        $stmt = $this->conn->prepare($sql); 
    
       
        if (!$stmt) {
            
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
    
            
            return false;
        }
    
        
        $stmt->bind_param("si", $newContent, $commentId); 
    
    
        if ($stmt->execute()) { 
            
            $stmt->close();
            return true;
        } else {
            
            error_log("Chyba pri aktualizácii komentára: " . $stmt->error);
    
            return false;
        }
    }







    // Funckia, ktorá získa ID používateľa na základe jeho používateľského mena
    public function getUserIdByUsername($username) {

       
        $sql = "SELECT id FROM users WHERE username = ?"; 
    
        
        $stmt = $this->conn->prepare($sql); 
    
       
        if (!$stmt) {
            
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
            return null;
        }
    
       
        $stmt->bind_param("s", $username); 
    
        
        $stmt->execute();
    
        
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


    // Funckia, ktorá získa všetky články z databázy
    public function getAllArticles() {

        $sql = "SELECT id, title FROM news";
    
        
        $result = $this->conn->query($sql); 
    
        
        $articles = []; 
    
        // 4. Prechádzanie výsledkami dopytu a uloženie údajov do poľa $articles
        while ($row = $result->fetch_assoc()) { 
            $articles[] = $row; 
        }
    
       
        return $articles; 
    }
   




    // Funkcia na získanie článku podľa ID
    public function getArticleById($articleId) {

        
        $sql = "SELECT id, title, content FROM news WHERE id = ?"; 
    
        
        $stmt = $this->conn->prepare($sql); 
    
        
        $stmt->bind_param("i", $articleId); 
    
        
        $stmt->execute();
    
        
        $result = $stmt->get_result();
    

        return $result->fetch_assoc();
    
    }




    // Funckia na aktualizáciu článku
    public function updateArticle($articleId, $newTitle, $newContent) {

        
        $sql = "UPDATE news SET title = ?, content = ? WHERE id = ?";
    
        
        $stmt = $this->conn->prepare($sql);
    
        
        if (!$stmt) {
            
            error_log("Chyba pri príprave dopytu: " . $this->conn->error);
            return false; 
        }
    
        
        $stmt->bind_param("ssi", $newTitle, $newContent, $articleId);
        
    
        
        if ($stmt->execute()) { 
            $stmt->close();
            return true;
        } else {
            
            error_log("Chyba pri aktualizácii článku: " . $stmt->error);
            return false; 
        }
    }




    // Metóda na získanie chybového hlásenia (pre sendErrorResponse)
    public function getErrorMessage() {
        return $this->conn->error;
    }






    

    // Funckia na presmerovanie na základe ID
    public function getNewsRedirectUrl($newsId, $category = null) {
        $redirectUrl = "news.php"; // Základná URL

        // Ak nie je kategória v parametroch, zoberie sa s databázy
        if (!$category) {
            $sql = "SELECT category FROM news WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $newsId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $category = $row['category'];
            }
        }

        // Pridanie kategórie do URL ak je dostupná
        if ($category) {
            $redirectUrl .= "?category=" . urlencode($category);
        }

        return $redirectUrl;
    }
    

}    

?>