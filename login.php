<?php
session_start();
require __DIR__ . '/db.php';

$error = "";

/* ---------------------------
   CREATE ADMIN USER IF NOT EXISTS
---------------------------- */
$adminUsername = "Admin1";
$adminPassword = "admin123";
$adminEmail = "admin@store.com";

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $adminUsername);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    $insert = mysqli_prepare(
        $conn,
        "INSERT INTO users (username, password, email) VALUES (?, ?, ?)"
    );
    mysqli_stmt_bind_param($insert, "sss", $adminUsername, $hashedPassword, $adminEmail);
    mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);
}
mysqli_stmt_close($stmt);

/* ---------------------------
   REDIRECT IF LOGGED IN
---------------------------- */
if (isset($_SESSION['user_id'])) {
    header("Location: catalog.php");
    exit;
}

/* ---------------------------
   LOGIN LOGIC
---------------------------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $error = "All fields are required.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, username, password FROM users WHERE username = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $dbUser, $dbPass);

        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $dbPass)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $dbUser;
                header("Location: catalog.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - E-Commerce Store</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h1>🛒 Welcome</h1>
            <p>Please login to continue shopping</p>

            <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>

</html>