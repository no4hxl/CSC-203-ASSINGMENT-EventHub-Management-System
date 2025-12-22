<?php
include "../includes/db.php";
$message = "";
/**
 * Registration Logic
 */
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username exists
    $check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<span style='color:var(--accent);'>Username already taken.</span>";
    } else {
        // Insert with is_approved = 0
        $sql = "INSERT INTO admin (username, password, is_approved) VALUES ('$user', '$pass', 0)";
        if (mysqli_query($conn, $sql)) {
            $message = "<span style='color:var(--primary);'>Request sent! An existing admin must approve your account before you can log in.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin - EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo"><i class="fas fa-user-plus"></i> <span>Admin Registration</span></div>
            <a href="login.php" style="color: white; text-decoration: none;">Back to Login</a>
        </div>
    </header>

    <main class="container">
        <div class="form-container" style="max-width: 450px; margin: 4rem auto;">
            <h2>Create Admin Account</h2>
            <p style="margin-bottom: 20px; color: var(--gray);">Set up your credentials for dashboard access.</p>
            
            <?php if ($message) echo "<p style='margin-bottom:15px; text-align:center;'>$message</p>"; ?>

            <form method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="register-btn btn-block">Register Account</button>
            </form>
        </div>
    </main>
</body>
</html>