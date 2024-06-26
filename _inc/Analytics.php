<?php
require_once 'functions.php'; 

class Analytics { 
    private $conn; 

    public function __construct($conn) {  
       
        $this->conn = dbConnect();  
    }

    // Získanie celkového počtu používateľov.
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) AS total FROM users";  
        
        $result = $this->conn->query($sql);  
        return $result->fetch_assoc()['total'];  
        // Vrátenie výsledku ako číslo.
    }

    // Získanie celkového počtu komentárov.
    public function getTotalComments() {
        $sql = "SELECT COUNT(*) AS total FROM comments";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    // Získanie celkového počtu článkov.
    public function getTotalArticles() {
        $sql = "SELECT COUNT(*) AS total FROM news";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    // Získanie najaktívnejších používateľov.
    public function getMostActiveUsers($limit = 5) { 
        // Parameter $limit určuje počet vrátených používateľov (5).
        $sql = "SELECT users.username, COUNT(comments.id) AS comment_count 
                FROM users 
                LEFT JOIN comments ON users.id = comments.user_id 
                GROUP BY users.id 
                ORDER BY comment_count DESC
                LIMIT ?";
        // Dopyt na získanie používateľov zoradených podľa počtu komentárov, ktoré pridali.

        $stmt = $this->conn->prepare($sql);  
        $stmt->bind_param("i", $limit);   
        $stmt->execute();                  
        $result = $stmt->get_result();      

        $activeUsers = [];                 
        while ($row = $result->fetch_assoc()) {
            $activeUsers[] = $row;         // Pridanie výsledku do poľa.
        }
        return $activeUsers;                
    }

    // Získanie najkomentovanejších článkov.
    public function getMostCommentedArticles($limit = 5) {
        // Podobná štruktúra ako getMostActiveUsers, ale pre články.
        $sql = "SELECT news.title, COUNT(comments.id) AS comment_count
                FROM news
                LEFT JOIN comments ON news.id = comments.news_id
                GROUP BY news.id
                ORDER BY comment_count DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $commentedArticles = [];
        while ($row = $result->fetch_assoc()) {
            $commentedArticles[] = $row;
        }
        return $commentedArticles;
    }

    // Výpočet priemerného počtu komentárov na článok.
    public function getAverageCommentsPerArticle() {
        $totalComments = $this->getTotalComments();
        $totalArticles = $this->getTotalArticles();
        return ($totalArticles > 0) ? $totalComments / $totalArticles : 0; 
        // Zabránenie deleniu nulou.
    }

    // Získanie počtu nových používateľov za posledný mesiac.
    public function getNewUsersLastMonth() {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }
}
