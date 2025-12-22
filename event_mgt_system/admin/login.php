<?php
/**
 * Admin Login Page
 * Handles authentication for the administrative back-end.
 */
session_start();
include "../includes/db.php";
// Redirect to dashboard if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}
// ... inside your login logic ...
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check credentials AND approval status
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password' AND is_approved=1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        // Specifically check if the account exists but isn't approved yet
        $check_pending = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND is_approved=0");
        if (mysqli_num_rows($check_pending) > 0) {
            $error = "Your account is pending approval from a senior admin.";
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body style="background-color: var(--light-gray);">
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-user-shield"></i>
                <span>Admin Access</span>
            </div>
            <a href="../index.php" style="color: white; text-decoration: none;">
                <i class="fas fa-home"></i> Back to Site
            </a>
        </div>
    </header>

    <main class="container">
        <div class="form-container" style="max-width: 450px; margin: 4rem auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <i class="fas fa-lock" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                <h2>Sign In</h2>
            </div>

            <?php if (isset($error)): ?>
                <div style="color: var(--accent); margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="register-btn btn-block">
                    Login to Dashboard
                </button>
            </form>

            <div style="margin-top: 2rem; border-top: 1px solid var(--light-gray); padding-top: 1.5rem; text-align: center;">
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Need an account?</p>
                <a href="register_admin.php" class="register-btn" style="background-color: var(--secondary); text-decoration: none; display: inline-block;">
                    <i class="fas fa-user-plus"></i> Create Admin Account
                </a>
            </div>
        </div>
    </main>
</body>
</html>