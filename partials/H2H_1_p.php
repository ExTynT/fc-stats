<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>H2H</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="style/style.css">
    <script src="main.js"></script>

    <style>
    /* Štýly pre tabuľku */
.h2h_cele {
    width: 90%; 
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
}

.h2h_cele table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; 
}


.nazov {
    background-color: #007bff; 
    color: white;
    font-size: 1.2em;
    padding: 15px;
}


tr {
    background-color: #fff;
    transition: background-color 0.3s ease; 
}

tr:nth-child(even) { 
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e9ecef; 
}

td {
    padding: 10px;
    border: 1px solid #ddd; 
    text-align: center;
}


.vs { 
    font-weight: bold;
}

.vysledok { 
    font-weight: bold;
}


@media (max-width: 768px) {
    .h2h_cele table {
        font-size: 0.9em; 
    }

    .h2h_cele td {
        padding: 8px; 
    }

    .nazov {
        padding: 10px; 
    }
}
</style>

</head>

<body class="telo_h2h_1">

<div class="h2h_cele">
        <table>
            <tr>
                <th class="nazov" colspan="4">Last matches</th>
            </tr>
            <?php
            include '../_inc/functions.php';
            $conn = dbConnect();

            outputH2HData($conn, '1');  
            $conn->close(); 
            ?>
        </table>
    </div>
</body>
</html>