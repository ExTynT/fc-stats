<?php

// Trieda pre komentáre
class Comment {
   
    public $id;            // ID komentára 
    public $news_id;       // ID článku, ku ktorému komentár patrí 
    public $user_id;       // ID používateľa, ktorý komentár vytvoril 
    public $content;       // Text komentára 
    public $created_at;    // Dátum a čas vytvorenia komentára 
    public $username;     // Meno používateľa, ktorý komentár vytvoril 

   
    public function __construct($data) {
        
        //ak $data['id'] nie je nastavené, použije sa null
        $this->id = $data['id'] ?? null;        

        $this->news_id = $data['news_id'];     
        $this->user_id = $data['user_id'];    
        $this->content = $data['content'];     
        $this->created_at = $data['created_at']; 
        $this->username = $data['username'];   
    }
}
