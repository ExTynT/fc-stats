<?php
include_once '../_inc/News.php';
class NewsRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getNewsByCategory($category = null, $limit = 3) {
        $sql = "SELECT * FROM news";

        
        if ($category !== null) {
            $sql .= " WHERE category = '$category'";
        }
        $sql .= " ORDER BY created_at DESC LIMIT ?";

        $stmt = $this->conn->prepare($sql);

        
        if ($stmt === false) {
            die("Error preparing statement: " . $this->conn->error); 
        }

        if ($category !== null) {
            $stmt->bind_param("i", $limit);
        } else {
            $stmt->bind_param("s", $category, $limit);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $newsItems = [];
        while ($row = $result->fetch_assoc()) {
            $newsItems[] = new News($row);
        }
        return $newsItems;
    }
}