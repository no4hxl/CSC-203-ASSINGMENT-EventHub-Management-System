<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$id = intval($_GET['id']);

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['event_date'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $capacity = intval($_POST['capacity']);

    // Updated query to include all new columns
    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, location=?, category=?, capacity=? WHERE id=?");
    $stmt->bind_param("sssssii", $title, $description, $date, $location, $category, $capacity, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | EventHub Admin</title>
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
            <h1>Edit Event</h1>
            <p class="page-subtitle">Update your event information below to keep your audience informed.</p>
        </div>

        <div class="form-container" style="max-width: 850px;">
            <form method="post">
                <div class="form-group">
                    <label for="title">
                        <i class="fas fa-heading"></i> <span>Event Title</span>
                    </label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> <span>Event Description</span>
                    </label>
                    <textarea id="description" name="description" class="form-control" rows="5" 
                              required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="event_date">
                            <i class="far fa-calendar-alt"></i> <span>Event Date</span>
                        </label>
                        <input type="date" id="event_date" name="event_date" class="form-control" 
                               value="<?php echo $event['event_date']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            <i class="fas fa-map-marker-alt"></i> <span>Location / Venue</span>
                        </label>
                        <input type="text" id="location" name="location" class="form-control" 
                               value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="category">
                            <i class="fas fa-layer-group"></i> <span>Category</span>
                        </label>
                        <input type="text" id="category" name="category" class="form-control" 
                               value="<?php echo htmlspecialchars($event['category']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="capacity">
                            <i class="fas fa-users"></i> <span>Maximum Capacity</span>
                        </label>
                        <input type="number" id="capacity" name="capacity" class="form-control" 
                               value="<?php echo $event['capacity']; ?>">
                    </div>
                </div>

                <button type="submit" name="update" class="register-btn btn-block" style="margin-top: 20px;">
                    Save Changes <i class="fas fa-save"></i>
                </button>
            </form>

            <a href="dashboard.php" class="back-link">
                <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Cancel Changes
            </a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Administration Suite.</p>
    </footer>
</body>
</html>
