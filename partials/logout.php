<?php
session_start();

// Vymazanie relácie
session_unset();
session_destroy();

// Vymazanie cookie 'loggedIn'
setcookie('loggedIn', '', time() - 3600, '/'); 

// Presmerovanie na prihlasovaciu stránku
header('Location: ../index.php');
exit;