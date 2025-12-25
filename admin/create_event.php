<?php
session_start();
// Security check: only logged-in admins can access this page
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "../includes/db.php";

$message = "";

// Handle Form Submission
if (isset($_POST['create'])) {
    // Collect and sanitize input
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['event_date'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $capacity = !empty($_POST['capacity']) ? intval($_POST['capacity']) : NULL;

    // Use prepared statements for security
    $query = "INSERT INTO events (title, description, event_date, location, category, capacity) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $title, $description, $date, $location, $category, $capacity);

    if ($stmt->execute()) {
        $message = "<div style='color: #4cc9f0; margin-bottom: 20px;'><i class='fas fa-check-circle'></i> Event created successfully!</div>";
    } else {
        $message = "<div style='color: #f72585; margin-bottom: 20px;'><i class='fas fa-exclamation-circle'></i> Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | EventHub Admin</title>
    <!-- Modern Icons & Premium Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div>
                <a href="dashboard.php" style="text-decoration: none; color: inherit;">
                    <div class="logo">
                        <i class="fas fa-calendar-alt"></i>
                        <span>EventHub</span>
                    </div>
                </a>
                <p class="tagline">Discovery & Registration Platform</p>
            </div>
            <div class="admin-link">
                <a href="dashboard.php" style="color: white; text-decoration: none; font-size: 0.95rem; font-weight: 600;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Create New Event</h1>
            <p class="page-subtitle">Fill in the details below to launch your next amazing event.</p>
        </div>

        <div class="form-container" style="max-width: 850px;">
            <!-- Professional Message Handling -->
            <?php echo $message; ?>

            <form method="post">
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> <span>Event Title</span>
                    </label>
                    <input type="text" id="title" name="title" class="form-control" 
                           placeholder="e.g., Lagos Tech Summit 2025" required>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> <span>Event Description</span>
                    </label>
                    <textarea id="description" name="description" class="form-control" rows="5" 
                              placeholder="Provide a detailed description of the event..." required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="event_date">
                            <i class="far fa-calendar-alt"></i> <span>Event Date</span>
                        </label>
                        <input type="date" id="event_date" name="event_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            <i class="fas fa-map-marker-alt"></i> <span>Location / Venue</span>
                        </label>
                        <input type="text" id="location" name="location" class="form-control" 
                               placeholder="Physical address or 'Online Event'" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-layer-group"></i> <span>Category</span>
                        </label>
                        <input type="text" id="category" name="category" class="form-control" 
                               placeholder="e.g., Technology, Seminar, Music">
                    </div>

                    <div class="form-group">
                        <label for="capacity">
                            <i class="fas fa-users"></i> <span>Maximum Capacity</span>
                        </label>
                        <input type="number" id="capacity" name="capacity" class="form-control" 
                               placeholder="Available spots (leave empty for unlimited)">
                    </div>
                </div>

                <button type="submit" name="create" class="register-btn btn-block" style="margin-top: 20px;">
                    Publish Event <i class="fas fa-paper-plane"></i>
                </button>
            </form>

            <a href="dashboard.php" class="back-link">
                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Cancel and Return
            </a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Administration Suite.</p>
    </footer>
</body>
</html>
