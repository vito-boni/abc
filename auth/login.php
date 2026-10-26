<?php
// Mulai sesi
session_start();
require_once "../config/connect.php";

// Cek login
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: ../pages/main.php");
    exit;
}

$error = "";

// Cek username
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek database
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        // Simpan sesi
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../pages/main.php");
        exit;
    } else {
        $error = "Wrong username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Cek error -->
    <?php if ($error): ?>
        <p style="color: red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="submit">Login</button>
    </form>
</body>
</html>
