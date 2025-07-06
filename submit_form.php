<?php
session_start();

// Check if the form is submitted and all fields exist
if (
    isset($_POST['StudentType'], $_POST['Stream'], $_POST['StayingAt'], $_POST['AgeGroup'], $_POST['Gender'],
        $_POST['Q1'], $_POST['Q2'], $_POST['Q3'], $_POST['Q4'], $_POST['Q5'])
) 
{
    // Sanitize and collect data
    $StudentType = trim($_POST['StudentType']);
    $Stream = trim($_POST['Stream']);
    $StayingAt = trim($_POST['StayingAt']);
    $AgeGroup= intval($_POST['AgeGroup']);
    $Gender = trim($_POST['Gender']);

    $Q1 = intval($_POST['Q1']);
    $Q2 = intval($_POST['Q2']);
    $Q3 = intval($_POST['Q3']);
    $Q4 = intval($_POST['Q4']);
    $Q5 = intval($_POST['Q5']);
    $total_score = $Q1 + $Q2 + $Q3 + $Q4 + $Q5;

    $_SESSION['index_answers'] = [
        'StudentType' => $StudentType,
        'Stream' => $Stream,
        'StayingAt' => $StayingAt,
        'AgeGroup' => $AgeGroup,
        'Gender' => $Gender,
        'Q1' => $Q1,
        'Q2' => $Q2,
        'Q3' => $Q3,
        'Q4' => $Q4,
        'Q5' => $Q5,
        'total_score' => $total_score
    ];

    if ($total_score >= 15) {
        header("Location: form2_1.html");
    } else {
        header("Location: form2_2.html");
    }
    exit();
} else {
    die("Please complete all fields in the survey.");
}
?>
