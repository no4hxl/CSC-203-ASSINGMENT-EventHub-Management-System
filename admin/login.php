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
    $username = $_POST['username'];
    $password = $_POST['password'];

    /**
     * SECURITY UPGRADE: Prepared Statement for Login
     * We use placeholders (?) to safely check the username and password.
     * Note: We are still using plain-text comparison for the password as requested.
     */
    $query = "SELECT * FROM admin WHERE username=? AND password=? AND is_approved=1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        // Specifically check if the account exists but isn't approved yet
        // Also using a prepared statement here for safety
        $pending_query = "SELECT * FROM admin WHERE username=? AND is_approved=0";
        $stmt_pending = mysqli_prepare($conn, $pending_query);
        mysqli_stmt_bind_param($stmt_pending, "s", $username);
        mysqli_stmt_execute($stmt_pending);
        $check_pending = mysqli_stmt_get_result($stmt_pending);
        
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | EventHub</title>
    <!-- Modern Icons & Premium Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body style="background-color: var(--background);">
    <header>
        <div class="container header-content">
            <div>
                <a href="../index.php" style="text-decoration: none; color: inherit;">
                    <div class="logo">
                        <i class="fas fa-calendar-alt"></i>
                        <span>EventHub</span>
                    </div>
                </a>
                <p class="tagline">Discovery & Registration Platform</p>
            </div>
            <div class="admin-link">
                <a href="../index.php" style="color: white; text-decoration: none; font-size: 0.95rem; font-weight: 600;">
                    <i class="fas fa-home"></i> Back to Site
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Admin Access</h1>
            <p class="page-subtitle">Please sign in to access your administrative dashboard.</p>
        </div>

        <div class="form-container" style="max-width: 480px;">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <div style="background: rgba(99, 102, 241, 0.1); width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <i class="fas fa-lock" style="font-size: 2.5rem; color: var(--primary);"></i>
                </div>
                <h2 style="font-size: 1.8rem; font-weight: 700;">Welcome Back</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="success-alert" style="background: #fee2e2; color: #991b1b; border-color: #fecaca; margin-bottom: 25px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user-circle"></i> <span>Username</span>
                    </label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Enter your username" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-shield-alt"></i> <span>Password</span>
                    </label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>

                <button type="submit" name="login" class="register-btn btn-block">
                    Sign In to Dashboard <i class="fas fa-sign-in-alt"></i>
                </button>
            </form>

            <div style="margin-top: 2.5rem; border-top: 1px solid var(--border); padding-top: 2rem; text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.2rem; font-weight: 500;">
                    First time here? Need an account?
                </p>
                <a href="register_admin.php" class="register-btn" style="background: var(--secondary); box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.3);">
                    <i class="fas fa-user-plus"></i> Create Admin Account
                </a>
            </div>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Administration Suite.</p>
    </footer>
</body>
</html>
