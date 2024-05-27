<?php

//Funkcia na vykreslenie HTML reprezentácie jedného článku
function renderNewsItem($newsItem) {
    // Začiatok kontajnera pre článok
    echo "<div class='news-item'>"; 

    // Kontajner pre obrázok článku s triedou CSS pre štýlovanie
    echo "<div class='news-image-container'>";
    // Výpis obrázku článku
    // - src: Cesta k obrázku článku (získaná z vlastnosti $newsItem->image_path)
    // - alt: Alternatívny text obrázku (získaný z vlastnosti $newsItem->title)
    echo "<img src='{$newsItem->image_path}' alt='{$newsItem->title}'>";
    echo "</div>"; // Koniec kontajnera pre obrázok článku

    // Výpis nadpisu článku ako <h2>
    echo "<h2>{$newsItem->title}</h2>";

    // Výpis obsahu článku ako odsek <p>
    echo "<p>{$newsItem->content}</p>";

    echo "</div>"; // Koniec kontajnera pre článok
}