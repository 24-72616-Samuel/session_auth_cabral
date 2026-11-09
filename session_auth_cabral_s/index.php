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
$loginValue = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginValue = trim($_POST["login"] ?? "");
    $password = $_POST["password"] ?? "";

    $statement = $conn->prepare("SELECT id, username, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1");
    $statement->bind_param("ss", $loginValue, $loginValue);
    $statement->execute();
    $user = $statement->get_result()->fetch_assoc();
    $statement->close();

    if ($user && password_verify($password, $user["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit();
    }else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link href="bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="card shadow-sm w-100" style="max-width: 400px;">
            <div class="card-body p-4">
                <h1 class="h3 mb-4" style="text-align:center">Log in</h1>
                <?php if ($error !== ""): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
                <?php endif; ?>
                <?php if (($_GET["registered"] ?? "") === "1"): ?>
                    <div class="alert alert-success" role="status">Registration successful. Please log in.</div>
                <?php endif; ?>
                <form method="post" action="index.php">
                    <div class="mb-3">
                        <label class="form-label" for="login">Username or email</label>
                        <input class="form-control" type="text" name="login" id="login" value="<?= htmlspecialchars($loginValue, ENT_QUOTES, "UTF-8") ?>" autocomplete="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" type="password" name="password" id="password" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Log in</button>
                </form>
                <p class="mt-3 mb-0">Need an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </main>
</body>
</html>
