<?php
include "../includes/db.php";
$message = "";
/**
 * Registration Logic
 */
if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    /**
     * SECURITY UPGRADE: Prepared Statements
     * We use mysqli_prepare to check if the username already exists safely.
     */
    $check_query = "SELECT * FROM admin WHERE username=?";
    $stmt_check = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt_check, "s", $user);
    mysqli_stmt_execute($stmt_check);
    $check_result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "<span style='color:var(--accent);'>Username already taken.</span>";
    } else {
        /**
         * SECURITY UPGRADE: Prepared Statement for Insertion
         * We insert the new admin with is_approved = 0.
         */
        $insert_query = "INSERT INTO admin (username, password, is_approved) VALUES (?, ?, 0)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "ss", $user, $pass);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $message = "<span style='color:var(--primary);'>Request sent! An existing admin must approve your account before you can log in.</span>";
        } else {
            $message = "<span style='color:var(--accent);'>Registration error. Please try again.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin | EventHub</title>
    <!-- Modern Icons & Premium Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
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
                <!-- Clean, professional link to administration -->
                <a href="login.php" style="color: white; text-decoration: none; font-size: 0.95rem; font-weight: 600;">
                    <i class="fas fa-sign-in-alt"></i> Back to Login
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Admin Registration</h1>
            <p class="page-subtitle">Request administrative access to manage your events and attendees.</p>
        </div>

        <div class="form-container" style="max-width: 500px;">
            <!-- Professional Message Handling -->
            <?php if ($message): ?>
                <div class="success-alert" style="margin-bottom: 25px;">
                    <i class="fas fa-info-circle"></i>
                    <span><?php echo $message; ?></span>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user-shield"></i> <span>Username</span>
                    </label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Choose a professional username" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-key"></i> <span>Password</span>
                    </label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Set a strong password" required>
                </div>

                <button type="submit" name="register" class="register-btn btn-block">
                    Request Access <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <a href="../index.php" class="back-link">
                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Return to Site
            </a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Administration Suite.</p>
    </footer>
</body>
</html>