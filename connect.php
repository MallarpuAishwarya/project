<?php
// Create connection
$con = new mysqli("sql106.infinityfree.com", "if0_39185232", "n6pPPKNt8f", "if0_39185232_mental_health_survey");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

echo "Connected successfully!";
?>
