<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Preview</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        <?php include '../style/style.css'; ?>
    </style>
    <script src='main.js'></script>
</head>
<body class="preview_1_b">  

<div class="preview_1_body">

<h1>Preview</h1>

<?php
include '../_inc/functions.php';
$conn = dbConnect();



outputPreviewData($conn, 3);  

$conn->close(); 
?>

</div>
</body>
</html>
