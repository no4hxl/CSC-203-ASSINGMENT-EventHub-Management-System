<?php
// Include DB Connection
include "includes/db.php";

// 1. Validate Event ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid event selection. Please go back and select an event.");
}

$event_id = intval($_GET['id']);

// 2. Handle Form Submission
if (isset($_POST['submit'])) {
    $name  = $_POST['fullname'];
    $email = $_POST['email'];

    /**
     * SECURITY UPGRADE: Prepared Statement for Registration
     * Even though you had a prepared statement here, I've simplified it
     * and added comments to explain how it protects your database.
     */
    $insert_query = "INSERT INTO registrations (event_id, fullname, email) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    
    // "iss" means: integer, string, string
    mysqli_stmt_bind_param($stmt, "iss", $event_id, $name, $email);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect on success to the success page
        header("Location: success.php");
        exit();
    } else {
        $error_message = "Registration failed: " . mysqli_stmt_error($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | EventHub</title>
    <!-- Modern Icons & Premium Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div>
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <div class="logo">
                        <i class="fas fa-calendar-alt"></i>
                        <span>EventHub</span>
                    </div>
                </a>
                <p class="tagline">Discovery & Registration Platform</p>
            </div>
            <div style="color: white; font-weight: 600; font-size: 0.95rem;">
                <i class="fas fa-user-circle"></i> Attendee Portal
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Event Registration</h1>
            <p class="page-subtitle">Complete the form below to secure your spot at the event.</p>
        </div>

        <div class="form-container">
            <!-- Professional Error Handling -->
            <?php if (isset($error_message)): ?>
                <div class="success-alert" style="background: #fee2e2; color: #991b1b; border-color: #fecaca;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="fullname">
                        <i class="fas fa-user-tag"></i> <span>Full Name</span>
                    </label>
                    <input type="text" id="fullname" name="fullname" class="form-control" 
                           placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-at"></i> <span>Email Address</span>
                    </label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="Enter your email" required>
                </div>

                <!-- Hidden Event ID -->
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                <button type="submit" name="submit" class="register-btn btn-block">
                    Confirm Seat <i class="fas fa-check-circle"></i>
                </button>
            </form>

            <a href="index.php" class="back-link">
                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Back to All Events
            </a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. All rights reserved.</p>
    </footer>
</body>
</html>
