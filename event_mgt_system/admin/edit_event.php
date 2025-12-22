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
    <title>Edit Event - EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo"><i class="fas fa-edit"></i> <span>Edit Event</span></div>
            <a href="dashboard.php" style="color: white; text-decoration: none;">Cancel</a>
        </div>
    </header>
    <main class="container">
        <div class="form-container">
            <form method="post">
                <div class="form-group">
                    <label>Event Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="event_date" class="form-control" value="<?php echo $event['event_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($event['category']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="<?php echo $event['capacity']; ?>">
                    </div>
                </div>
                <button type="submit" name="update" class="register-btn btn-block">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>