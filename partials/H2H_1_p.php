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
    body {
        margin: 0;
        padding: 0;
    }


    body {
        background-color: #cbd1d3;
    }


    .nazov {
        background-color: #999fa2; 
        color: white; 
        font-size: 20px;
        font-weight: bold; 
        padding: 10px; 
        text-align: center; 
    }


    tr {
        background-color: #ffffff; 
    }

    
    td {
        padding: 8px;
        border: 1px solid #ccc; 
        
    }

    
    .vs {
        font-weight: bold; 
        text-align: center; 
    }

    .vysledok {font-weight: bold;}
</style>

</head>

<body class="telo_h2h_1">

<div class="h2h_cele">
    <table>
        <tr>
            <th class="nazov" colspan="6">Last matches</th>
        </tr>
        <?php
        // Connect to the MySQL database
        $conn = mysqli_connect("localhost", "root", "", "fc_stats");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch data from the database table
        $sql = "SELECT * FROM h2h_1";
        $result = $conn->query($sql);

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='datum'>" . $row['H2H_1_Datum'] . "</td>";
            echo "<td class='liga'>" . $row['H2H_1_Liga'] . "</td>";
            echo "<td class='team_1'>" . $row['H2H_1_Zapas'] . "</td>";
            echo "<td class='vysledok'>" . $row['H2H_1_Vysledok'] . "</td>";
            echo "</tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>