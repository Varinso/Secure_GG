<?php
session_start();
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle money storage
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["store_money"])) {
    $amount = floatval($_POST["amount"]);

    if ($amount > 0) {
        $update_query = "UPDATE users SET balance = balance + ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("di", $amount, $user_id);
        $stmt->execute();
    }
}

// Fetch user balance
$balance_query = "SELECT balance FROM users WHERE user_id = ?";
$balance_stmt = $conn->prepare($balance_query);
$balance_stmt->bind_param("i", $user_id);
$balance_stmt->execute();
$balance_result = $balance_stmt->get_result();
$user = $balance_result->fetch_assoc();
$balance = $user['balance'];

// Fetch unpaid booth bookings
$booth_query = "SELECT bb.booking_id, b.booth_name, bb.total_cost 
                FROM booth_bookings bb 
                JOIN booths b ON bb.booth_id = b.booth_id 
                WHERE bb.user_id = ? AND bb.total_cost > 0";
$booth_stmt = $conn->prepare($booth_query);
$booth_stmt->bind_param("i", $user_id);
$booth_stmt->execute();
$booth_result = $booth_stmt->get_result();

// Fetch unpaid rentals
$rental_query = "SELECT r.rental_id, g.title AS game_title, r.total_cost 
                 FROM rentals r 
                 JOIN games g ON r.game_id = g.game_id 
                 WHERE r.user_id = ? AND r.total_cost > 0";
$rental_stmt = $conn->prepare($rental_query);
$rental_stmt->bind_param("i", $user_id);
$rental_stmt->execute();
$rental_result = $rental_stmt->get_result();

// Handle payments
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pay"])) {
    $item_id = intval($_POST["item_id"]);
    $cost = floatval($_POST["cost"]);
    $type = $_POST["type"];

    if ($balance >= $cost) {
        $conn->begin_transaction();

        try {
            // Deduct balance
            $update_balance = "UPDATE users SET balance = balance - ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_balance);
            $stmt->bind_param("di", $cost, $user_id);
            $stmt->execute();

            // Store payment record
            $desc = ($type == "booth") ? "Booth Booking Payment" : "Game Rental Payment";
            $payment_query = "INSERT INTO payments (user_id, amount, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($payment_query);
            $stmt->bind_param("ids", $user_id, $cost, $desc);
            $stmt->execute();

            // Mark items as paid (set total_cost to 0)
            if ($type == "booth") {
                $update_booth = "UPDATE booth_bookings SET total_cost = 0 WHERE booking_id = ?";
                $stmt = $conn->prepare($update_booth);
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
            } else {
                $update_rental = "UPDATE rentals SET total_cost = 0 WHERE rental_id = ?";
                $stmt = $conn->prepare($update_rental);
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
            }

            $conn->commit();

            // Redirect to prevent duplicate submission
            header("Location: payment.php?success=1");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error processing payment: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Insufficient balance!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            text-align: center;
        }

        .balance {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input {
            padding: 10px;
            width: 80%;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table th,
        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .info-table th {
            background-color: #007bff;
            color: white;
        }

        .pay-btn {
            background-color: #28a745;
        }

        .pay-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Your Balance</h1>
        <div class="balance">Current Balance: <strong>Tk<?= number_format($balance, 2); ?></strong></div>

        <form method="POST">
            <input type="number" name="amount" placeholder="Enter amount to store" required>
            <button type="submit" name="store_money">Store Money</button>
        </form>

        <h2>Pending Payments</h2>

        <table class="info-table">
            <tr>
                <th>Type</th>
                <th>Item</th>
                <th>Cost</th>
                <th>Action</th>
            </tr>
            <?php while ($booth = $booth_result->fetch_assoc()): ?>
                <tr>
                    <td>Booth Booking</td>
                    <td><?= htmlspecialchars($booth['booth_name']); ?></td>
                    <td>Tk<?= number_format($booth['total_cost'], 2); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="item_id" value="<?= $booth['booking_id']; ?>">
                            <input type="hidden" name="cost" value="<?= $booth['total_cost']; ?>">
                            <input type="hidden" name="type" value="booth">
                            <button type="submit" name="pay" class="pay-btn">Pay Now</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
<div class="container">
    <table class="info-table">
        <?php while ($rental = $rental_result->fetch_assoc()): ?>
            <tr>
                <td>Game Rental</td>
                <td><?= htmlspecialchars($rental['game_title']); ?></td>
                <td>Tk<?= number_format($rental['total_cost'], 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="item_id" value="<?= $rental['rental_id']; ?>">
                        <input type="hidden" name="cost" value="<?= $rental['total_cost']; ?>">
                        <input type="hidden" name="type" value="rental">
                        <button type="submit" name="pay" class="pay-btn">Pay Now</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<div style="text-align: center; margin-top: 20px;">
    <a href="profile.php">
        <button type="button">Profile</button>
    </a>
    <a href="pricing.php">
        <button type="button">Home</button>
    </a>
</div>

</html>