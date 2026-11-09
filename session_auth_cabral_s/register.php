<?php
session_start();

if (isset($_SESSION["user_id"])) {
	header("Location: dashboard.php");
	exit();
}

ob_start();
require_once "db.php";
ob_end_clean();

$error = "";
$usernameValue = "";
$emailValue = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$usernameValue = trim($_POST["username"] ?? "");
	$emailValue = trim($_POST["email"] ?? "");
	$password = $_POST["password"] ?? "";

	if ($usernameValue === "" || strlen($usernameValue) > 100) {
		$error = "Enter a username with no more than 100 characters.";
	} elseif (!filter_var($emailValue, FILTER_VALIDATE_EMAIL) || strlen($emailValue) > 100) {
		$error = "Enter a valid email address with no more than 100 characters.";
	} elseif (strlen($password) < 8) {
		$error = "Your password must be at least 8 characters long.";
	} else {
		$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
		$check->bind_param("ss", $usernameValue, $emailValue);
		$check->execute();
		$existingUser = $check->get_result()->fetch_assoc();
		$check->close();

		if ($existingUser) {
			$error = "That username or email is already registered.";
		} else {
			$passwordHash = password_hash($password, PASSWORD_DEFAULT);
			$insert = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
			$insert->bind_param("sss", $usernameValue, $emailValue, $passwordHash);
			$insert->execute();
			$insert->close();

			header("Location: index.php?registered=1");
			exit();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Create account</title>
	<link href="bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
	<main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
		<div class="card shadow-sm w-100" style="max-width: 460px;">
			<div class="card-body p-4">
				<h1 class="h3 mb-4" style="text-align:center">Create account</h1>
				<?php if ($error !== ""): ?>
					<div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
				<?php endif; ?>
				<form method="post" action="register.php">
					<div class="mb-3">
						<label class="form-label" for="username">Username</label>
						<input class="form-control" type="text" name="username" id="username" maxlength="100" value="<?= htmlspecialchars($usernameValue, ENT_QUOTES, "UTF-8") ?>" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="email">Email</label>
						<input class="form-control" type="email" name="email" id="email" maxlength="100" value="<?= htmlspecialchars($emailValue, ENT_QUOTES, "UTF-8") ?>" required>
					</div>
					<div class="mb-3">
						<label class="form-label" for="password">Password</label>
						<input class="form-control" type="password" name="password" id="password" minlength="8" required>
					</div>
					<button class="btn btn-primary w-100" type="submit">Register</button>
				</form>
				<p class="mt-3 mb-0">Already registered? <a href="index.php">Log in</a></p>
			</div>
		</div>
	</main>
</body>
</html>
