<?php
// Create connection
$con= new mysqli("sql106.infinityfree.com","if0_39185232", "n6pPPKNt8f", "if0_39185232_mental_health_survey");
if (!$con) {
    die(mysqli_error($con));
}
echo "Connected successfully!";
?>