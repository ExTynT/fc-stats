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
        
        <p>
        <?php
        // Database connection
        $conn = mysqli_connect("localhost", "root", "", "fc_stats");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch preview text from the database
        $sql = "SELECT preview_2_text FROM preview_2";
        $result = $conn->query($sql);

        // Output preview text
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo $row['preview_2_text'];
            }
        } else {
            echo "No preview available.";
        }
        $conn->close();
        ?>
        </p>

        
        <table class="preview_1_odds">
            <tr>
                <th>BVB Dortmund</th>
                <th>Draw</th>
                <th>Atletico Madrid</th>
            </tr>
            <tr>
                <?php
                // Connect to the database
                $conn = mysqli_connect("localhost", "root", "", "fc_stats");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch odds from the database
                $sql = "SELECT preview_2_odds_win_1, preview_2_odds_win_2, preview_2_odds_draw FROM preview_2";
                $result = $conn->query($sql);

                // Output odds
                if ($result->num_rows > 0) {
                    // Output data of the first row
                    $row = $result->fetch_assoc();
                    echo "<td>" . $row['preview_2_odds_win_1'] . "</td>";
                    echo "<td>" . $row['preview_2_odds_draw'] . "</td>";
                    echo "<td>" . $row['preview_2_odds_win_2'] . "</td>";
                    
                } else {
                    echo "<td colspan='3'>No odds available</td>";
                }
                $conn->close();
                ?>
            </tr>
            </table>
        
    </div>
    
</body>
</html>
