<?php
session_start(); // Start the session
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in to book a booth.");
}

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve POST data
    $booth_id = intval($_POST['booth_id']);
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = date("H:i", strtotime("+1 hour", strtotime($start_time))); // Calculate end time as 1 hour later
    $total_cost = floatval($_POST['total_cost']);

    // Rule 1: Ensure the user doesn't already have a booking for this time slot
    $user_check_query = "SELECT * FROM booth_bookings 
                         WHERE user_id = ? AND booth_id = ? AND booking_date = ? 
                         AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))";
    $user_check_stmt = $conn->prepare($user_check_query);
    $user_check_stmt->bind_param("iisssss", $user_id, $booth_id, $booking_date, $start_time, $start_time, $end_time, $end_time);
    $user_check_stmt->execute();
    $user_check_result = $user_check_stmt->get_result();

    if ($user_check_result->num_rows > 0) {
        die("You already have a booking for this time slot. Please choose another time.");
    }

    // Rule 2: Ensure there is capacity for this booth at the selected time slot
    $capacity_check_query = "SELECT capacity FROM booths WHERE booth_id = ?";
    $capacity_check_stmt = $conn->prepare($capacity_check_query);
    $capacity_check_stmt->bind_param("i", $booth_id);
    $capacity_check_stmt->execute();
    $capacity_check_result = $capacity_check_stmt->get_result();
    $capacity_row = $capacity_check_result->fetch_assoc();

    if ($capacity_row['capacity'] <= 0) {
        die("The booth is fully booked. Please choose another booth.");
    }

    // Proceed with booking
    $query = "INSERT INTO booth_bookings (user_id, booth_id, booking_date, start_time, end_time, total_cost) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("iisssd", $user_id, $booth_id, $booking_date, $start_time, $end_time, $total_cost);

        if ($stmt->execute()) {
            // Decrease booth capacity
            $update_capacity_query = "UPDATE booths SET capacity = capacity - 1 WHERE booth_id = ?";
            $update_capacity_stmt = $conn->prepare($update_capacity_query);
            $update_capacity_stmt->bind_param("i", $booth_id);
            $update_capacity_stmt->execute();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Booth Booking Confirmation</title>
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
                        color: #007bff;
                    }
                    .btn-custom {
                        width: 100%;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="card p-4">
                        <div class="card-body text-center">
                            <h1 class="card-title">Booking Successful!</h1>
                            <p class="card-text">Your booth has been booked successfully.</p>
                            <p class="fw-bold">Total Cost: $<?php echo number_format($total_cost, 2); ?></p>
                            <a href="payment.php" class="btn btn-primary btn-custom">Proceed to Pay</a>
                            <a href="profile.php" class="btn btn-secondary btn-custom mt-2">Go to Profile</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
