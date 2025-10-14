<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Rental Booking</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header Section */
        header {
            background-color:rgb(0, 0, 0);
            color: #fff;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 1.75rem;
        }

        /* Container Design */
        .container {
            max-width: 700px;
            margin: 3rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 1.5rem;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        label {
            font-weight: bold;
            color: #495057;
        }

        input[type="text"],
        input[type="date"],
        button {
            width: 100%;
            padding: 0.9rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }

        button {
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease-in-out;
            border-radius: 8px;
        }

        button:hover {
            background-color: #218838;
        }

        .payable-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
            margin-top: 1rem;
        }

        .alert {
            font-size: 0.95rem;
            color: #e74c3c;
            display: none;
            margin-top: -1rem;
            padding: 0.5rem 1rem;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-link {
            font-size: 1rem;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                margin: 2rem 1rem;
                padding: 1.5rem;
            }

            button {
                font-size: 1rem;
                padding: 0.75rem;
            }

            .payable-amount {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Game Rental Booking</h1>
    </header>

    <div class="container">
        <h1>Rent a Game</h1>

        <?php
        if (isset($_GET['game_id'])) {
            require_once 'db.php';

            $game_id = intval($_GET['game_id']);
            $query = "SELECT title, price_per_day FROM games WHERE game_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $game_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $game = $result->fetch_assoc();
                $game_name = htmlspecialchars($game['title']);
                $price_per_day = htmlspecialchars($game['price_per_day']);
            } else {
                die("Invalid Game ID");
            }
        } else {
            die("Game ID not provided");
        }
        ?>

        <form action="process_booking.php" method="POST">
            <div class="form-group">
                <label for="game_name">Game Name:</label>
                <input type="text" id="game_name" name="game_name" value="<?php echo $game_name; ?>" readonly>
            </div>

            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
            <input type="hidden" id="price_per_day" name="price_per_day" value="<?php echo $price_per_day; ?>">

            <div class="form-group">
                <label for="rental_date">Rental Date:</label>
                <input type="date" id="rental_date" name="rental_date" required>
            </div>

            <div class="form-group">
                <label for="return_date">Return Date:</label>
                <input type="date" id="return_date" name="return_date" required>
                <div class="alert" id="date-alert">Return date must be after the rental date.</div>
            </div>

            <p>Total Payable Amount: <span class="payable-amount" id="payable-amount">$0</span></p>

            <div class="form-footer">
            
                <button type="submit">Book Now</button>
                
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rentalDateInput = document.getElementById('rental_date');
            const returnDateInput = document.getElementById('return_date');
            const payableAmountSpan = document.getElementById('payable-amount');
            const pricePerDay = parseFloat(document.getElementById('price_per_day').value);

            function calculatePayableAmount() {
                const rentalDate = new Date(rentalDateInput.value);
                const returnDate = new Date(returnDateInput.value);

                if (returnDate > rentalDate) {
                    const timeDiff = returnDate - rentalDate;
                    const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days
                    const totalAmount = days * pricePerDay;
                    payableAmountSpan.textContent = `$${totalAmount.toFixed(2)}`;
                } else {
                    payableAmountSpan.textContent = `$0`;
                }
            }

            rentalDateInput.addEventListener('change', calculatePayableAmount);
            returnDateInput.addEventListener('change', () => {
                const rentalDate = new Date(rentalDateInput.value);
                const returnDate = new Date(returnDateInput.value);

                if (returnDate <= rentalDate) {
                    alert("Return date must be after the rental date.");
                    returnDateInput.value = '';
                    payableAmountSpan.textContent = `$0`;
                } else {
                    calculatePayableAmount();
                }
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rentalDateInput = document.getElementById('rental_date');
            const returnDateInput = document.getElementById('return_date');
            const payableAmountSpan = document.getElementById('payable-amount');
            const pricePerDay = parseFloat(document.getElementById('price_per_day').value);
            const dateAlert = document.getElementById('date-alert');

            function calculatePayableAmount() {
                const rentalDate = new Date(rentalDateInput.value);
                const returnDate = new Date(returnDateInput.value);

                if (returnDate > rentalDate) {
                    const timeDiff = returnDate - rentalDate;
                    const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // Convert milliseconds to days
                    const totalAmount = days * pricePerDay;
                    payableAmountSpan.textContent = `$${totalAmount.toFixed(2)}`;
                    dateAlert.style.display = 'none';
                } else {
                    payableAmountSpan.textContent = `$0`;
                    dateAlert.style.display = 'block';
                }
            }

            rentalDateInput.addEventListener('change', calculatePayableAmount);
            returnDateInput.addEventListener('change', calculatePayableAmount);
        });
    </script>
</body>

</html>
