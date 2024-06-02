<?php

// Trieda news 
class News {
   
    public $id;            // ID článku 
    public $title;         // Nadpis článku
    public $content;       // Obsah článku
    public $category;      // Kategória, do ktorej článok patrí
    public $image_path;    // Cesta k obrázku článku 


    public function __construct($data) {
        // Priradenie hodnôt z poľa $data k jednotlivým vlastnostiam objektu
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->category = $data['category'];
        $this->image_path = $data['image_path'];
    }
}
