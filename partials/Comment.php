<?php

class Comment {
    // Verejné atribúty komentára
    public $id;            // ID komentára 
    public $news_id;       // ID článku, ku ktorému komentár patrí 
    public $user_id;       // ID používateľa, ktorý komentár vytvoril 
    public $content;       // Text komentára 
    public $created_at;    // Dátum a čas vytvorenia komentára 
    public $username;     // Meno používateľa, ktorý komentár vytvoril 

   
    public function __construct($data) {
        // Priradenie hodnôt z poľa $data k vlastnostiam objektu

        // Null coalescing operator (??) - ak $data['id'] nie je nastavené, použije sa null
        $this->id = $data['id'] ?? null;        

        $this->news_id = $data['news_id'];     // ID článku
        $this->user_id = $data['user_id'];     // ID používateľa
        $this->content = $data['content'];     // Obsah komentára
        $this->created_at = $data['created_at']; // Dátum a čas vytvorenia
        $this->username = $data['username'];   // Meno používateľa
    }
}
