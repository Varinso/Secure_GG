<?php
session_start();
require_once 'db.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to enroll in a tournament.");
}

// Retrieve user_id from the session
$user_id = $_SESSION['user_id'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the POST data
    $tournament_id = intval($_POST['tournament_id']);
    $team_name = isset($_POST['team_name']) ? trim($_POST['team_name']) : null;

    // Validate tournament ID
    $query = "SELECT * FROM tournaments WHERE tournament_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid tournament.");
    }

    // Check if the user is already enrolled in the tournament
    $check_query = "SELECT * FROM tournament_participants WHERE user_id = ? AND tournament_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $user_id, $tournament_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        die("You are already enrolled in this tournament.");
    }

    // Insert the participant into the tournament_participants table
    $registration_date = date("Y-m-d H:i:s");
    $insert_query = "INSERT INTO tournament_participants (tournament_id, user_id, registration_date, team_name) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);

    if ($insert_stmt) {
        $insert_stmt->bind_param("iiss", $tournament_id, $user_id, $registration_date, $team_name);

        if ($insert_stmt->execute()) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Tournament Enrollment</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .card {
                        max-width: 500px;
                        border-radius: 15px;
                        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                    }
                    .card-title {
                        color: #28a745;
                    }
                    .btn-custom {
                        width: 100%;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="card p-4 text-center">
                        <div class="card-body">
                            <h1 class="card-title">Enrollment Successful!</h1>
                            <p class="card-text">You have successfully enrolled in the tournament.</p>
                            <a href="profile.php" class="btn btn-secondary btn-custom mt-2">Go to Profile</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Error: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }

    $stmt->close();
    $check_stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
