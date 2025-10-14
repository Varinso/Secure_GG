<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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

        .section {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin Panel</h1>

        <!-- Add Games -->
        <div class="section">
            <h2>Add Game</h2>
            <form action="process_admin.php" method="POST">
                <input type="hidden" name="action" value="add_game">

                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="platform">Platform:</label>
                <input type="text" id="platform" name="platform" required>

                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre" required>

                <label for="price_per_day">Price Per Day:</label>
                <input type="number" id="price_per_day" name="price_per_day" step="0.01" required>

                <label for="stock_quantity">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required>

                <button type="submit">Add Game</button>
            </form>
        </div>

        <!-- Add Booths -->
        <div class="section">
            <h2>Add Booth</h2>
            <form action="process_admin.php" method="POST">
                <input type="hidden" name="action" value="add_booth">

                <label for="booth_name">Booth Name:</label>
                <input type="text" id="booth_name" name="booth_name" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="capacity">Capacity:</label>
                <input type="number" id="capacity" name="capacity" required>

                <label for="prices">Price:</label>
                <input type="number" id="prices" name="prices" step="0.01" required>

                <button type="submit">Add Booth</button>
            </form>
        </div>

        <!-- Add Tournaments -->
        <div class="section">
            <h2>Add Tournament</h2>
            <form action="process_admin.php" method="POST">
                <input type="hidden" name="action" value="add_tournament">

                <label for="name">Tournament Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="game_title">Game Title:</label>
                <input type="text" id="game_title" name="game_title" required>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>

                <label for="prize_pool">Prize Pool:</label>
                <input type="number" id="prize_pool" name="prize_pool" step="0.01" required>

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>

                <button type="submit">Add Tournament</button>
            </form>
        </div>
    </div>
</body>

</html>