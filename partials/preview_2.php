<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Preview</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        /* Všeobecný štýl stránky */
body {
    font-family: sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}


.preview_1_body {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}


h1 {
    color: #007bff;
    text-align: center;
    margin-bottom: 20px;
}


p {
    margin-bottom: 15px;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 20px auto;
    border-radius: 8px;
}

blockquote {
    background-color: #f9f9f9;
    border-left: 4px solid #ccc;
    margin: 20px 0;
    padding: 10px 20px;
}


.preview_1_odds {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    table-layout: fixed; 
}

.preview_1_odds th,
.preview_1_odds td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

.preview_1_odds th {
    background-color: #007bff; 
    font-weight: bold;
}


.preview_1_odds .odds-value {
    font-size: 1.2em;
    font-weight: bold;
}


@media (max-width: 768px) {
    .preview_1_body {
        padding: 15px; 
    }

    img {
        margin: 10px auto; 
    }

    .zapas_left, .zapas_right {
        flex-direction: column; 
    }

    .preview_1_odds { 
        font-size: 0.9em; 
    }

    .preview_1_odds th,
    .preview_1_odds td {
        padding: 8px; 
    }
}

    </style>
    <script src='main.js'></script>
</head>
<body class="preview_1_b">  

<div class="preview_1_body">

<h1>Preview</h1>

<?php
include '../_inc/functions.php';
$conn = dbConnect();



outputPreviewData($conn, 2);  

$conn->close(); 
?>

</div>
</body>
</html>
