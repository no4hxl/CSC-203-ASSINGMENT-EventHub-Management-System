<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include "../includes/db.php";

// If a specific event ID is passed, filter for that event only
$event_filter = "";
$title_prefix = "All Registrations";
if (isset($_GET['event_id'])) {
    $e_id = intval($_GET['event_id']);
    $event_filter = " WHERE r.event_id = $e_id";
    $title_prefix = "Attendees for Event #$e_id";
}

// Fetch registration details joined with event titles
$query = "SELECT r.*, e.title as event_name 
          FROM registrations r 
          JOIN events e ON r.event_id = e.id 
          $event_filter
          ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Registrations - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; box-shadow: var(--shadow-light); border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--light-gray); }
        th { background: var(--primary); color: white; }
        tr:hover { background-color: #f9f9ff; }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo"><i class="fas fa-users"></i> <span>Registrations</span></div>
            <a href="dashboard.php" style="color: white; text-decoration: none;">Back to Dashboard</a>
        </div>
    </header>

    <main class="container">
        <div class="page-title">
            <h1><?php echo $title_prefix; ?></h1>
            <p>Full list of attendee contact details</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Event Registered</th>
                    <th>Date Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><span class="event-date" style="background: var(--secondary);"><?php echo htmlspecialchars($row['event_name']); ?></span></td>
                        <td><?php echo date("M j, Y - H:i", strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No registrations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer class="container" style="margin-top: 50px;">
        <p>&copy; <?php echo date("Y"); ?> EventHub Admin</p>
    </footer>
</body>
</html>