<?php require_once __DIR__ . '/security/bootstrap.php'; ?>
<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="auto">

<!-- header top -->
<header class="position-absolute z-3 mt-3 w-100" data-bs-theme="dark">
	<nav class="navbar navbar-expand-xl" aria-label="Offcanvas navbar large">
		<div class="container py-1">
			<a href="pricing.php" class="navbar-brand">
				<img src="./assets/logo/LOGO.jpg" height="80" alt="logo">
			</a>

			<div class="dropdown ms-3 order-last">
				<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
					<symbol id="check2" viewBox="0 0 16 16">
						<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
					</symbol>
					<symbol id="circle-half" viewBox="0 0 16 16">
						<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
					</symbol>
					<symbol id="moon-stars-fill" viewBox="0 0 16 16">
						<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
						<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
					</symbol>
					<symbol id="sun-fill" viewBox="0 0 16 16">
						<path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
					</symbol>
				</svg>

				<button class="btn btn-primary text-white btn-sm rounded dropdown-toggle d-flex align-items-center"
					id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
					aria-label="Toggle theme (auto)">
					<svg fill="currentColor" class="bi my-1 theme-icon-active" width="1em" height="1em">
						<use href="#circle-half"></use>
					</svg>
					<span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
				</button>

				<ul class="p-1 dropdown-menu dropdown-menu-end dropdown-menu-hover end-0 rounded-3 shadow bg-body-tertiary"
					style="--bs-dropdown-min-width: 9rem;" aria-labelledby="bd-theme-text">

					<li style="color: var(--bs-tertiary-bg);">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
							class="mt-n1 d-inline-block position-absolute top-0 end-0 translate-middle" viewBox="0 0 16 16">
							<path class="carret-dropdown-path" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
						</svg>
					</li>

					<li>
						<button type="button" class="dropdown-item d-flex align-items-center rounded-1" data-bs-theme-value="light" aria-pressed="false">
							<svg fill="currentColor" class="bi me-2 theme-icon" width="1em" height="1em">
								<use href="#sun-fill"></use>
							</svg>
							Light
							<svg fill="currentColor" class="bi ms-auto d-none active-check" width="1em" height="1em">
								<use href="#check2"></use>
							</svg>
						</button>
					</li>

					<li>
						<button type="button" class="my-1 dropdown-item d-flex align-items-center rounded-1" data-bs-theme-value="dark" aria-pressed="false">
							<svg fill="currentColor" class="bi me-2 theme-icon" width="1em" height="1em">
								<use href="#moon-stars-fill"></use>
							</svg>
							Dark
							<svg fill="currentColor" class="bi ms-auto d-none active-check" width="1em" height="1em">
								<use href="#check2"></use>
							</svg>
						</button>
					</li>

					<li>
						<button type="button" class="dropdown-item d-flex align-items-center rounded-1 active" data-bs-theme-value="auto" aria-pressed="true">
							<svg fill="currentColor" class="bi me-2 theme-icon" width="1em" height="1em">
								<use href="#circle-half"></use>
							</svg>
							Auto
							<svg fill="currentColor" class="bi ms-auto d-none active-check" width="1em" height="1em">
								<use href="#check2"></use>
							</svg>
						</button>
					</li>
				</ul>
			</div>

			<button class="navbar-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar2" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="offcanvas offcanvas-end border-0 rounded-start-0 rounded-start-sm-4" tabindex="-1" id="offcanvasNavbar2" aria-labelledby="offcanvasNavbar2Label">
				<div class="offcanvas-header" style="padding: 2rem 2rem 1.5rem 2rem;">
					<h5 class="offcanvas-title m-0" id="offcanvasNavbar2Label">
						<a class="navbar-brand" href="javascript:;">
							<img src="./assets/logo/logo.png" height="32" alt="logo">
						</a>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>

				<div class="offcanvas-body">
					<ul class="navbar-nav align-items-xl-center flex-grow-1 column-gap-4 row-gap-4 row-gap-xl-2">
						<li class="nav-item ms-xl-auto">
							<a href="pricing.php" class="px-3 text-white bg-primary-hover nav-link rounded-3 text-base leading-6 fw-semibold" aria-current="page">
								Home
							</a>
						</li>



						<li class="nav-item">
							<a href="javascript:;" class="px-3 text-white bg-primary-hover nav-link rounded-3 text-base leading-6 fw-semibold">
								About
							</a>
						</li>

						<li class="nav-item">
							<div class="dropdown">
								<button class="btn w-100 text-start dropdown-toggle px-3 text-white bg-primary-hover nav-link rounded-3 text-base leading-6 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									Services
								</button>
								<ul class="dropdown-menu dropdown-menu-end dropdown-menu-hover end-0 text-sm shadow bg-white" style="--bs-dropdown-min-width: 9rem;">
									<li class="d-none d-xl-block" style="color: var(--bs-white);">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
											class="mt-n1 d-inline-block position-absolute top-0 end-0 translate-middle" viewBox="0 0 16 16">
											<path class="carret-dropdown-path" d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z" />
										</svg>
									</li>
									<li>
										<a class="dropdown-item text-dark bg-primary-hover text-white-hover py-2 text-base leading-6 fw-semibold" href="rental.php">
											Game Rent
										</a>
									</li>
									<li>
										<a class="dropdown-item text-dark bg-primary-hover text-white-hover py-2 text-base leading-6 fw-semibold" href="booth.php">
											Booth booking
										</a>
									</li>
									<li>
										<a class="dropdown-item text-dark bg-primary-hover text-white-hover py-2 text-base leading-6 fw-semibold" href="tournament.php"> Tournament
										</a>
									</li>
								</ul>
							</div>
						</li>

						<li class="nav-item ms-xl-auto">
							<a href="profile.php" class="px-3 text-white bg-primary nav-link rounded-3 text-base leading-6 fw-semibold text-center">
								Profile
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
</header>



<!-- header body -->
<div class="overflow-hidden py-9 py-xl-10 position-relative rounded-bottom-3 rounded-sm-4 rounded-xl-5 m-0 m-sm-2 m-xl-3 shadow">
	<img src="./assets/img/bg/logo.jpg" class="position-absolute z-n1 top-0 h-100 w-100 object-fit-cover" alt="Meeting">

	<div class="position-absolute z-n1 top-0 h-100 w-100 bg-dark"
		style="opacity: 0.85; mix-blend-mode: multiply; filter: contrast(1.15) brightness(0.85);">
	</div>

	<div class="position-absolute z-0 top-0 h-100 w-100">
		<div class="container h-100 d-flex align-items-center">
			<div class="max-w-2xl mx-auto text-center">
				<h1 class="m-0 mt-7 text-white tracking-tight text-6xl fw-bold" data-aos-delay="0" data-aos="fade" data-aos-duration="3000">
					Rent Games
				</h1>
				<p class="m-0 mt-4 text-white text-lg leading-8" data-aos-delay="100" data-aos="fade" data-aos-duration="3000">
					Rent your favorite games at affordable prices.
				</p>
			</div>
		</div>
	</div>
</div>



<head>
	<!-- Metadata and Styles -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gamer Galaxy - Rent Games</title>
	<link rel="stylesheet" href="./assets/css/main.min.css">
	<link rel="stylesheet" href="./assets/css/style.css">
</head>



<main class="container my-5">
	<h1 class="text-center mb-4">Available Games for Rent</h1>

	<?php
	// Database connection
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "GG";

	// Establish connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Fetch games from the database
	$sql = "SELECT game_id, title, platform, genre, price_per_day, stock_quantity FROM games";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		echo '<div class="row">';
		while ($row = $result->fetch_assoc()) {
			echo '
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row["title"]) . '</h5>
                            <p class="card-text"><strong>Platform:</strong> ' . htmlspecialchars($row["platform"]) . '</p>
                            <p class="card-text"><strong>Genre:</strong> ' . htmlspecialchars($row["genre"]) . '</p>
                            <p class="card-text"><strong>Price per Day:</strong> Tk' . htmlspecialchars($row["price_per_day"]) . '</p>
                            <p class="card-text"><strong>Stock:</strong> ' . htmlspecialchars($row["stock_quantity"]) . '</p>';

			// If the game is in stock, show rent button
			if ($row["stock_quantity"] > 0) {
				echo '
									<div>
									<form action="rent_booking.php" method="GET">
										<input type="hidden" name="game_id" value="' . htmlspecialchars($row['game_id']) . '">
										
										<button type="submit" ' . ($row['stock_quantity'] <= 0 ? 'disabled' : '') . '>Rent</button>
									</form>
									</div>';
			} else {
				echo '<button class="btn btn-secondary" disabled>Out of Stock</button>';
			}

			echo '
                        </div>
                    </div>
                </div>';
		}
		echo '</div>';
	} else {
		echo '<p class="text-center">No games available for rent at the moment.</p>';
	}

	// Close connection
	$conn->close();
	?>
</main>

<footer class="text-center py-4">
	<p>&copy; 2025 Gamer Galaxy. All Rights Reserved.</p>
</footer>

<!-- JavaScript -->
<script src="./assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>