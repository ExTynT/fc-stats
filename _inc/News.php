<?php
class News {
    public $id;
    public $title;
    public $content;
    public $category;
    public $image_path;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->category = $data['category'];
        $this->image_path = $data['image_path'];
    }
}
