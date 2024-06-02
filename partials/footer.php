<?php
// Získanie aktuálneho roku a času
$currentYear = date('Y');
$currentTime = date('H:i:s'); 


echo '
<div class="footer">
    <div class="autor">
        <p>Ivan Klopček</p>
    </div>
    <div class="copyright">
        <p>' . $currentYear . ' © Copyright</p>
    </div>
    <div class="current-time">
        <p>Aktuálny čas: <span id="time-display">' . $currentTime . '</span></p> 
    </div>
</div>';
?>

<script>
    
    function updateTime() {
        const timeDisplay = document.getElementById("time-display");
        const now = new Date();
        const timeString = now.toLocaleTimeString(); // Získanie aktuálneho času v lokálom formáte
        timeDisplay.textContent = timeString;
    }

    // Spustenie funkcie updateTime() každú sekundu
    setInterval(updateTime, 1000); 
</script>
