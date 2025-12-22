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
    <title>Create New Event - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-plus-circle"></i>
                <span>Create Event</span>
            </div>
            <a href="dashboard.php" style="color: white; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </header>

    <main class="container">
        <div class="form-container" style="max-width: 800px; margin: 2rem auto;">
            <h2 style="margin-bottom: 10px;">Event Details</h2>
            <p style="color: var(--gray); margin-bottom: 25px;">Fill in the information below to publish a new event.</p>
            
            <?php echo $message; ?>

            <form method="post">
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Event Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Lagos Tech Summit 2025" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Provide a detailed description of the event..." required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label><i class="far fa-calendar-alt"></i> Event Date</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Physical address or 'Online'" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Technology, Seminar, Music">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-users"></i> Maximum Capacity</label>
                        <input type="number" name="capacity" class="form-control" placeholder="Number of available spots">
                    </div>
                </div>

                <button type="submit" name="create" class="register-btn btn-block" style="margin-top: 10px;">
                    <i class="fas fa-paper-plane"></i> Publish Event
                </button>
            </form>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Admin Panel</p>
    </footer>
</body>
</html>