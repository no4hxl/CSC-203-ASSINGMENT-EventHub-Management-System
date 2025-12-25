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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrations | EventHub Admin</title>
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
            <h1>Attendee Report</h1>
            <p class="page-subtitle"><?php echo $title_prefix; ?> — Comprehensive list of event registrations.</p>
        </div>

        <div class="dashboard-section" style="padding: 10px 30px;">
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Attendee Name</th>
                            <th>Email Address</th>
                            <th>Event Joined</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><span class="event-id">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                                <td><i class="fas fa-envelope-open-text" style="color: #94a3b8; font-size: 0.8rem; margin-right: 5px;"></i> <?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <div style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 5px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; display: inline-block;">
                                        <i class="fas fa-tag" style="font-size: 0.75rem;"></i> <?php echo htmlspecialchars($row['event_name']); ?>
                                    </div>
                                </td>
                                <td><i class="far fa-clock"></i> <?php echo date("M j, Y", strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 60px; color: var(--text-muted);">
                                    <i class="fas fa-folder-open" style="font-size: 3rem; display: block; margin-bottom: 20px; opacity: 0.2;"></i>
                                    No registrations have been recorded for this view.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin: 30px 0; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                <p style="color: var(--text-muted); font-size: 0.9rem;">Total Registrations Found: <strong><?php echo mysqli_num_rows($result); ?></strong></p>
                <a href="dashboard.php" class="back-link">
                    <i class="fas fa-chevron-left" style="font-size: 0.8rem;"></i> Return to Dashboard
                </a>
            </div>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Administration Suite.</p>
    </footer>
</body>
</html>
