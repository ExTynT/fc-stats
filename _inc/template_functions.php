<?php

// Funkcia na vykreslenie HTML reprezentácie jedného článku
function renderNewsItem($newsItem) {
    
    echo "<div class='news-item'>"; 

    
    echo "<div class='news-image-container'>";
    

    echo "<img src='{$newsItem->image_path}' alt='{$newsItem->title}'>";
    echo "</div>"; 
  
    echo "<h2>{$newsItem->title}</h2>";

    
    echo "<p>{$newsItem->content}</p>";

    echo "</div>"; 
}