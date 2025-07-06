<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// ✅ Add this block before using $db_host etc.
$db_host = "sql106.infinityfree.com";
$db_user = "if0_39185232";
$db_pass = "n6pPPKNt8f";
$db_name = "if0_39185232_mental_health_survey";


// Check session for index answers
if (!isset($_SESSION['index_answers'])) {
    // If index data is missing, display an error message using the styled box
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #e0f2f7;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
                box-sizing: border-box;
                text-align: center;
            }
            .message-box {
                background-color: #ffffff;
                border: 2px solid #ffcc00; 
                border-radius: 16px;
                padding: 32px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                max-width: 600px;
                width: 100%;
                box-sizing: border-box;
            }
            h2 {
                color: #e05c00;
                font-size: 1.875rem;
                font-weight: bold;
                margin-bottom: 16px;
            }
            p {
                color: #4a5568;
                font-size: 1.125rem;
                margin-bottom: 12px;
                font-weight: 500;
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>Error!</h2>
            <p>Form 1 data missing. Please complete the first survey form.</p>
        </div>
    </body>
    </html>
    <?php
    exit(); 
}

$index = $_SESSION['index_answers'];

// Check if form2 answers and personal info are set
if (
    isset($_POST['Q6']) && isset($_POST['Q7']) && isset($_POST['Q8']) &&
    isset($_POST['Q9']) && isset($_POST['Q10'])
) {
    // Sanitize inputs
    $Name = $index['Name'];
    $Email = $index['Email'];
    $Phone = $index['Phone'];
    $Age = $index['Age'];
    $Gender = $index['Gender'];

    if (!$Email) {
        // If email is invalid, display an error message using the styled box
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #e0f2f7;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    box-sizing: border-box;
                    text-align: center;
                }
                .message-box {
                    background-color: #ffffff;
                    border: 2px solid #ffcc00; 
                    border-radius: 16px;
                    padding: 32px;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    max-width: 600px;
                    width: 100%;
                    box-sizing: border-box;
                }
                h2 {
                    color: #e05c00; 
                    font-size: 1.875rem;
                    font-weight: bold;
                    margin-bottom: 16px;
                }
                p {
                    color: #4a5568;
                    font-size: 1.125rem;
                    margin-bottom: 12px;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>Error!</h2>
                <p>Invalid email format.</p>
            </div>
        </body>
        </html>
        <?php
        exit();
    }

    // Convert Q6-Q10 to integers
    $Q6 = intval($_POST['Q6']);
    $Q7 = intval($_POST['Q7']);
    $Q8 = intval($_POST['Q8']);
    $Q9 = intval($_POST['Q9']);
    $Q10 = intval($_POST['Q10']);
    $overall_total = $index['total_score'] + $Q6 + $Q7 + $Q8 + $Q9 + $Q10;

    // Connect to DB
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($mysqli->connect_error) {
        // If DB connection fails, display an error message using the styled box
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #e0f2f7;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    box-sizing: border-box;
                    text-align: center;
                }
                .message-box {
                    background-color: #ffffff;
                    border: 2px solid #ff0000; 
                    border-radius: 16px;
                    padding: 32px;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    max-width: 600px;
                    width: 100%;
                    box-sizing: border-box;
                }
                h2 {
                    color: #cc0000; 
                    font-size: 1.875rem;
                    font-weight: bold;
                    margin-bottom: 16px;
                }
                p {
                    color: #4a5568;
                    font-size: 1.125rem;
                    margin-bottom: 12px;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>Database Connection Error!</h2>
                <p>Database connection failed: <?php echo $mysqli->connect_error; ?></p>
            </div>
        </body>
        </html>
        <?php
        exit(); // Stop execution
    }

    // Prepare insert statement to prevent SQL injection
    $stmt= $mysqli->prepare("INSERT INTO mental_health_survey 
        (Name, Email, Phone, Age, Gender, Q1, Q2, Q3, Q4, Q5, Q6, Q7, Q8, Q9, Q10, TotalScore) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssiiiiiiiiiiiii",
        $Name, $Email, $Phone, $Age, $Gender,
        $index['Q1'], $index['Q2'], $index['Q3'], $index['Q4'], $index['Q5'],
        $Q6, $Q7, $Q8, $Q9, $Q10,
        $overall_total
    );

    if ($stmt->execute()) {
        // Clear session data after successful insert
        unset($_SESSION['index_answers']);
        // Output the success HTML
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Submission Successful!</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif; 
                    background-color: #e0f2f7; 
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh; 
                    margin: 0;
                    padding: 20px; 
                    box-sizing: border-box; 
                    text-align: center; 
                }

                .message-box {
                    background-color: #ffffff; 
                    border: 2px solid #a7d9f2; 
                    border-radius: 16px; 
                    padding: 32px; 
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
                    max-width: 600px; 
                    width: 100%;
                    box-sizing: border-box; 
                }

                h2 {
                    color: #2c5282; 
                    font-size: 1.875rem;
                    font-weight: bold;
                    margin-bottom: 16px;
                }

                p {
                    color: #4a5568; 
                    font-size: 1.125rem; 
                    margin-bottom: 12px; 
                    font-weight: 500; 
                }

                .complementary-message {
                    margin-top: 24px; 
                    font-size: 0.9rem; 
                    color: #6b7280; 
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>Thank you, your responses have been recorded successfully.</h2>
                <p><strong>Your Total Mental Health Score:</strong> <?php echo $overall_total; ?></p>
            </div>
        </body>
        </html>
        <?php
    } else {
        // If saving data fails, display an error message using the styled box
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #e0f2f7;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    box-sizing: border-box;
                    text-align: center;
                }
                .message-box {
                    background-color: #ffffff;
                    border: 2px solid #ff0000;
                    border-radius: 16px;
                    padding: 32px;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    max-width: 600px;
                    width: 100%;
                    box-sizing: border-box;
                }
                h2 {
                    color: #cc0000; 
                    font-size: 1.875rem;
                    font-weight: bold;
                    margin-bottom: 16px;
                }
                p {
                    color: #4a5568;
                    font-size: 1.125rem;
                    margin-bottom: 12px;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>Error Saving Data!</h2>
                <p>Error saving data: <?php echo $stmt->error; ?></p>
            </div>
        </body>
        </html>
        <?php
    }

    $stmt->close();
    $mysqli->close();
} else {
    // If form fields are incomplete, display an error message using the styled box
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #e0f2f7;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 20px;
                box-sizing: border-box;
                text-align: center;
            }
            .message-box {
                background-color: #ffffff;
                border: 2px solid #ffcc00; 
                border-radius: 16px;
                padding: 32px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                max-width: 600px;
                width: 100%;
                box-sizing: border-box;
            }
            h2 {
                color: #e05c00; 
                font-size: 1.875rem;
                font-weight: bold;
                margin-bottom: 16px;
            }
            p {
                color: #4a5568;
                font-size: 1.125rem;
                margin-bottom: 12px;
                font-weight: 500;
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>Error!</h2>
            <p>Please complete all fields in the survey.</p>
        </div>
    </body>
    </html>
    <?php
}
?>