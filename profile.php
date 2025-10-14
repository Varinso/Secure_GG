<?php
session_start();
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT username, email, phone_number FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch rentals, booth bookings, and tournament participation
$rentals_query = "SELECT rentals.rental_date, rentals.return_date, rentals.total_cost, games.title AS game_title, games.platform, rentals.status FROM rentals JOIN games ON rentals.game_id = games.game_id WHERE rentals.user_id = ?";
$rentals_stmt = $conn->prepare($rentals_query);
$rentals_stmt->bind_param("i", $user_id);
$rentals_stmt->execute();
$rentals_result = $rentals_stmt->get_result();

$booth_query = "SELECT booths.booth_name, booths.location, booth_bookings.booking_date, booth_bookings.start_time, booth_bookings.end_time, booth_bookings.total_cost FROM booth_bookings JOIN booths ON booth_bookings.booth_id = booths.booth_id WHERE booth_bookings.user_id = ?";
$booth_stmt = $conn->prepare($booth_query);
$booth_stmt->bind_param("i", $user_id);
$booth_stmt->execute();
$booth_result = $booth_stmt->get_result();

$tournaments_query = "SELECT tournaments.name AS tournament_name, tournaments.game_title, tournaments.start_date, tournaments.end_date, tournaments.prize_pool, tournament_participants.team_name FROM tournament_participants JOIN tournaments ON tournament_participants.tournament_id = tournaments.tournament_id WHERE tournament_participants.user_id = ?";
$tournaments_stmt = $conn->prepare($tournaments_query);
$tournaments_stmt->bind_param("i", $user_id);
$tournaments_stmt->execute();
$tournaments_result = $tournaments_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="pp.css">

</head>

<body>
    <header>
        <div class="logo">Gamers Galaxy</div>
        <nav>
            <a href="pricing.php">Home</a>
            <a href="login.php" class="logout">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h1>Your Profile</h1>

        <div class="card">
            <h2>User Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone_number']); ?></p>
        </div>

        <div class="card">
            <h2>Games Rented</h2>
            <?php if ($rentals_result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Platform</th>
                        <th>Rental Date</th>
                        <th>Return Date</th>
                    </tr>
                    <?php while ($rental = $rentals_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($rental['game_title']); ?></td>
                            <td><?= htmlspecialchars($rental['platform']); ?></td>
                            <td><?= htmlspecialchars($rental['rental_date']); ?></td>
                            <td><?= htmlspecialchars($rental['return_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <form action="payment.php" method="GET"><br>
                    <button type="submit" class="btn-payment">Proceed to Pay</button>
                </form>
            <?php else: ?>
                <p>No games rented yet.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <!-- Booth Bookings Section -->
            <h2>Booth Bookings</h2>
            <?php if ($booth_result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Booth Name</th>
                        <th>Location</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                    <?php while ($booth = $booth_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($booth['booth_name']); ?></td>
                            <td><?= htmlspecialchars($booth['location']); ?></td>
                            <td><?= htmlspecialchars($booth['booking_date']); ?></td>
                            <td><?= htmlspecialchars($booth['start_time']); ?></td>
                            <td><?= htmlspecialchars($booth['end_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <form action="payment.php" method="GET"><br>
                    <button type="submit" class="btn-payment">Proceed to Pay</button>
                </form>

                <!-- Review Button -->
                <form action="review.php" method="GET"><br>
                    <button type="submit" class="btn-review">Leave a Review</button>
                </form>

            <?php else: ?>
                <p>No booth bookings yet.</p>
            <?php endif; ?>

        </div>

        <div class="card">
            <h2>Tournament Participation</h2>
            <?php if ($tournaments_result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Game</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Prize</th>
                        <th>Team</th>
                    </tr>
                    <?php while ($tournament = $tournaments_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournament['tournament_name']); ?></td>
                            <td><?= htmlspecialchars($tournament['game_title']); ?></td>
                            <td><?= htmlspecialchars($tournament['start_date']); ?></td>
                            <td><?= htmlspecialchars($tournament['end_date']); ?></td>
                            <td>Tk<?= htmlspecialchars($tournament['prize_pool']); ?></td>
                            <td><?= htmlspecialchars($tournament['team_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No tournament participation yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>