<?php
// Include the database connection file
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the action to determine which table to insert into
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add_game':
            // Insert into the games table
            $title = $_POST['title'];
            $platform = $_POST['platform'];
            $genre = $_POST['genre'];
            $price_per_day = $_POST['price_per_day'];
            $stock_quantity = $_POST['stock_quantity'];

            $query = "INSERT INTO games (title, platform, genre, price_per_day, stock_quantity) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssdi", $title, $platform, $genre, $price_per_day, $stock_quantity);

            if ($stmt->execute()) {
                echo "Game added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;

        case 'add_booth':
            // Insert into the booths table
            $booth_name = $_POST['booth_name'];
            $location = $_POST['location'];
            $capacity = $_POST['capacity'];
            $prices = $_POST['prices'];

            $query = "INSERT INTO booths (booth_name, location, capacity, prices) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssii", $booth_name, $location, $capacity, $prices);

            if ($stmt->execute()) {
                echo "Booth added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;

        case 'add_tournament':
            // Insert into the tournaments table
            $name = $_POST['name'];
            $game_title = $_POST['game_title'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $prize_pool = $_POST['prize_pool'];
            $status = $_POST['status'];

            $query = "INSERT INTO tournaments (name, game_title, start_date, end_date, prize_pool, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssds", $name, $game_title, $start_date, $end_date, $prize_pool, $status);

            if ($stmt->execute()) {
                echo "Tournament added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;

        default:
            echo "Invalid action.";
            break;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
