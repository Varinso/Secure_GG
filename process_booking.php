<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GG"; // Updated database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and has a valid user_id in the session
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a booking.");
}

$user_id = $_SESSION['user_id']; // Retrieve the user ID from the session

// Validate incoming POST data
$game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : null;
$rental_date = isset($_POST['rental_date']) ? $_POST['rental_date'] : null;
$return_date = isset($_POST['return_date']) ? $_POST['return_date'] : null;

// Validate input
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (!$game_id || !validateDate($rental_date) || !validateDate($return_date)) {
    die("Invalid input. Please provide valid game ID, rental date, and return date.");
}

// Fetch game details to calculate total cost
$sql = "SELECT price_per_day, stock_quantity FROM games WHERE game_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $game = $result->fetch_assoc();
    $price_per_day = $game['price_per_day'];
    $stock_quantity = $game['stock_quantity'];

    // Check if the game is in stock
    if ($stock_quantity <= 0) {
        die("The game is out of stock.");
    }

    // Calculate total cost
    $start = new DateTime($rental_date);
    $end = new DateTime($return_date);
    $days = $end->diff($start)->days;
    $total_cost = $days * $price_per_day;

    // Insert the rental record
    $sql = "INSERT INTO rentals (user_id, game_id, rental_date, return_date, total_cost, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $status = "Pending"; // Default status
    $stmt->bind_param("iissds", $user_id, $game_id, $rental_date, $return_date, $total_cost, $status);

    if ($stmt->execute()) {
        // Decrease stock quantity by 1
        $update_stock_sql = "UPDATE games SET stock_quantity = stock_quantity - 1 WHERE game_id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_sql);
        $update_stock_stmt->bind_param("i", $game_id);
    
        if ($update_stock_stmt->execute()) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Booking Confirmation</title>
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
                            <p class="card-text">Your rental has been booked. Enjoy your game!</p>
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
            echo "<h1>Booking Successful, but stock update failed</h1><p>Your rental has been booked, but there was an error updating the stock: " . $update_stock_stmt->error . "</p>";
        }
    
        $update_stock_stmt->close();
    } else {
        echo "<h1>Booking Failed</h1><p>There was an error processing your booking: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    die("Game not found.");
}

$conn->close();
?>
