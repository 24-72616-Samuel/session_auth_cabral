<?php
session_start();

if (!isset($_SESSION["user_id"])) {
	header("Location: index.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<link href="bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-header {
            background-color: #000;
            color: #fff;
            border-color: #444;
        }
    </style>
</head>
<body class="bg-light">
    <header class="dashboard-header border-bottom">
        <div class="container py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h1 class="h4 mb-0">SessionAuthSystem Dashboard</h1>
            <div class="d-flex align-items-center gap-3">
                <span><?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8") ?></span>
                <a class="btn btn-outline-light" href="logout.php">Log out</a>
            </div>
        </div>
    </header>
    <main class="container py-4">
        <div class="card shadow-sm">
			<div class="card-body">
				<h1 class="h3 text-primary ">Welcome, <?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8") ?>!</h1>
                <p>This page is protected and only visible to logged-in users.</p>
				<button class="btn-primary btn">Explore Dashboard</button>
			</div>
		</div>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h2 class="h5 text-success">Account Status</h2>
                        <p>Your session is active and protected against session</p>
                        <p>fixation using <code>session_regenerate_id(true)</code>.</p>
                        <br><br>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h2 class="h5 text-primary">User Details</h2>
                        <p><strong>User ID:</strong> <?= htmlspecialchars($_SESSION["user_id"], ENT_QUOTES, "UTF-8") ?></p>
                        <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8") ?></p>
                        <br><br>

                       
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h2 class="h5 text-warning">Active Session Id</h2>
                        <p><strong>Session ID:<br></strong> <?= session_id() ?></p>
                        <p>Generated after login call to <code>session_regenerate_id(true)</code>.</p>
                        
                    </div>
                </div>
            </div>

        </div>
	</main>
</body>
</html>