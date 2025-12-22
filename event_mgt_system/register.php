<?php
// Include DB Connection
include "includes/db.php";

// 1. Validate Event ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid event selection. Please go back and select an event.");
}

$event_id = intval($_GET['id']);

// 2. Handle Form Submission
// We check for POST request before rendering HTML to allow for redirection
if (isset($_POST['submit'])) {
    $name  = $_POST['fullname'];
    $email = $_POST['email'];

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO registrations (event_id, fullname, email) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $event_id, $name, $email);

    if ($stmt->execute()) {
        // Redirect on success (Keeping your original logic)
        header("Location: success.php");
        exit();
    } else {
        $error_message = "Registration failed: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <div class="logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>EventHub</span>
                </div>
                <p class="tagline">Secure your spot for the event</p>
            </div>
            <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem;">
                <i class="fas fa-user-circle"></i> Event Management System
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-title">
            <h1>Event Registration</h1>
            <p class="page-subtitle">Please fill in your details below to complete your registration.</p>
        </div>

        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="fullname"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                <button type="submit" name="submit" class="register-btn btn-block">
                    Confirm Registration
                </button>
            </form>

            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Events</a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>