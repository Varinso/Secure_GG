<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booth Booking</title>
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
        select,
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

<!-- Top Panel -->
<div style="display: flex; justify-content: space-between; align-items: center; background-color: #333; color: white; padding: 10px;">
    <div style="display: flex; align-items: center;">
        <a href="pricing.php">
            <img src="image/LOGO.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
        </a>
        <h1 style="margin: 0; font-size: 20px;">Gamers Galaxy</h1>
    </div>
    <div>
        <a href="profile.php" style="text-decoration: none; color: white; background-color: #007bff; padding: 5px 10px; border-radius: 5px;">Profile</a>
    </div>
</div>

<body>
    <div class="container">
        <h1>Book a Booth</h1>

        <?php
        if (isset($_GET['booth_id'])) {
            require_once 'db.php';

            $booth_id = intval($_GET['booth_id']);
            $query = "SELECT booth_name, location, capacity, prices FROM booths WHERE booth_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $booth_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $booth = $result->fetch_assoc();
                $booth_name = htmlspecialchars($booth['booth_name']);
                $location = htmlspecialchars($booth['location']);
                $capacity = htmlspecialchars($booth['capacity']);
                $prices = htmlspecialchars($booth['prices']);
            } else {
                die("Invalid Booth ID");
            }
        } else {
            die("Booth ID not provided");
        }
        ?>

        <form action="process_booth_booking.php" method="POST">
            <input type="hidden" name="booth_id" value="<?php echo $booth_id; ?>">

            <label for="booth_name">Booth Name:</label>
            <input type="text" id="booth_name" value="<?php echo $booth_name; ?>" readonly>

            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required>

            <label for="start_time">Select Time Slot:</label>
            <select id="start_time" name="start_time" required>
                <?php
                // Generate time slots from 9:00 AM to 11:00 PM
                $start = strtotime("09:00");
                $end = strtotime("23:00");
                while ($start < $end) {
                    $slot = date("H:i", $start);
                    $next_slot = date("H:i", strtotime('+1 hour', $start));
                    echo '<option value="' . $slot . '">' . $slot . ' - ' . $next_slot . '</option>';
                    $start = strtotime('+1 hour', $start);
                }
                ?>
            </select>

            <label for="total_cost">Total Cost:</label>
            <input type="text" id="total_cost" name="total_cost" value="<?php echo $prices; ?>" readonly>


            <button type="submit">Book Now</button>
        </form>
    </div>

    <script>
        // Auto-update total cost when date or time slot changes
        document.getElementById('start_time').addEventListener('change', updateTotalCost);
        document.getElementById('booking_date').addEventListener('change', updateTotalCost);

        function updateTotalCost() {
            const boothId = <?php echo $booth_id; ?>;
            const startTime = document.getElementById('start_time').value;
            const bookingDate = document.getElementById('booking_date').value;

            if (boothId && startTime && bookingDate) {
                fetch(`get_booth_cost.php?booth_id=${boothId}&start_time=${startTime}&booking_date=${bookingDate}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.total_cost) {
                            document.getElementById('total_cost').value = data.total_cost;
                        }
                    })
                    .catch(error => console.error('Error fetching total cost:', error));
            }
        }
    </script>
</body>

</html>