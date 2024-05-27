<?php
class News {
    // Vlastnosti triedy (reprezentujú atribúty článku)
    public $id;            // ID článku 
    public $title;         // Nadpis článku
    public $content;       // Obsah článku
    public $category;      // Kategória, do ktorej článok patrí
    public $image_path;    // Cesta k obrázku článku 

    /**
     * Konštruktor triedy News
     *
     * Inicializuje objekt News a nastaví jeho vlastnosti na základe vstupných údajov.
     *
     *  Asociatívne pole obsahujúce údaje článku:
     *     - id: ID článku
     *     - title: Nadpis článku
     *     - content: Obsah článku
     *     - category: Kategória článku
     *     - image_path: Cesta k obrázku článku
     */
    public function __construct($data) {
        // Priradenie hodnôt z poľa $data k jednotlivým vlastnostiam objektu
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->category = $data['category'];
        $this->image_path = $data['image_path'];
    }
}
