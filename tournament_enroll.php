<?php
session_start();
require_once 'db.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to enroll in a tournament.");
}

// Retrieve user_id from the session
$user_id = $_SESSION['user_id'];

// Fetch tournament_id from the query string
if (!isset($_GET['tournament_id'])) {
    die("Tournament not specified.");
}
$tournament_id = intval($_GET['tournament_id']);

// Fetch tournament details
$query = "SELECT * FROM tournaments WHERE tournament_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Tournament not found.");
}
$tournament = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Enrollment</title>
    <link rel="stylesheet" href="styles.css">
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
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-weight: bold;
        }

        input,
        button {
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Top Panel -->
    <div style="display: flex; justify-content: space-between; align-items: center; background-color: #333; color: white; padding: 10px;">
        <div style="display: flex; align-items: center;">
            <a href="index.php">
                <img src="image/LOGO.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
            </a>
            <h1 style="margin: 0; font-size: 20px;">Gamers Galaxy</h1>
        </div>
        <div>
            <a href="profile.php" style="text-decoration: none; color: white; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Profile</a>
        </div>
    </div>

    <div class="container">
        <h1>Enroll in "<?php echo htmlspecialchars($tournament['name']); ?>"</h1>
        <form action="process_tournament_enroll.php" method="POST">
            <input type="hidden" name="tournament_id" value="<?php echo htmlspecialchars($tournament['tournament_id']); ?>">
            
            <label for="team_name">Team Name (Optional):</label>
            <input type="text" id="team_name" name="team_name" placeholder="Enter team name">

            <button type="submit">Enroll Now</button>
        </form>
    </div>
</body>

</html>
