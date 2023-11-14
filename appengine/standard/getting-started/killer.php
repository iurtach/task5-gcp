<?php
$servername = '34.22.184.234'; #"your_server_name"; "sql-instance-task5"; 'prod-401919:europe-west1:sql-instance-task5'
$username = 'user';
$password = 'user';
$dbname = 'employees';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get processlist
$result = $conn->query("SHOW FULL PROCESSLIST");

// Check each row
while ($row = $result->fetch_assoc()) {
    $process_id = $row["Id"];
    $time = $row["Time"];

    // If query has been running for more than 10 seconds
    if ($time > 10) {
        // Kill query
        $kill = $conn->query("KILL QUERY $process_id");

        if ($kill === TRUE) {
            echo "Query $process_id has been killed successfully\n";
        } else {
            echo "Error killing query $process_id: " . $conn->error . "\n";
        }
    }
}

$conn->close();
?>