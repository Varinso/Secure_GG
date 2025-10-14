<?php
session_start();
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch booth names and booking IDs
$bookings_query = "SELECT booth_bookings.booking_id, booths.booth_name 
                   FROM booth_bookings
                   JOIN booths ON booth_bookings.booth_id = booths.booth_id
                   WHERE booth_bookings.user_id = ?";
$bookings_stmt = $conn->prepare($bookings_query);
$bookings_stmt->bind_param("i", $user_id);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();
$bookings = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings[] = $row;
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_text = trim($_POST['review_text']);
    $booking_id = intval($_POST['booking_id']);
    $rating = intval($_POST['rating']);

    if ($review_text && $booking_id && $rating > 0 && $rating <= 5) {
        $query = "INSERT INTO reviews (user_id, booking_id, review_text, rating, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisi", $user_id, $booking_id, $review_text, $rating);
        if ($stmt->execute()) {
            $message = "Review submitted successfully!";
        } else {
            $message = "Error submitting review: " . $conn->error;
        }
    } else {
        $message = "All fields are required, and the rating must be between 1 and 5.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 1rem;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            margin-top: 0.5rem;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            margin-top: 1.5rem;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 1rem;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Submit Review</h1>
        <?php if (isset($message)): ?>
            <p class="<?= strpos($message, 'Error') !== false ? 'error' : 'message' ?>"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="booth_name">Booth Name:</label>
            <select id="booth_name" name="booking_id" required>
                <option value="">Select Booth</option>
                <?php foreach ($bookings as $booking): ?>
                    <option value="<?= htmlspecialchars($booking['booking_id']); ?>">
                        <?= htmlspecialchars($booking['booth_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="rating">Rating (1-5):</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <label for="review_text">Your Review:</label>
            <textarea id="review_text" name="review_text" rows="4" required></textarea>

            <button type="submit">Submit Review</button>
        </form>
        <form action="pricing.php" method="GET"><br>
            <button type="submit" class="btn-Home">Home</button>
        </form>
    </div>
</body>

</html>