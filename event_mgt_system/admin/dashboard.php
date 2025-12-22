<?php
session_start();
include "../includes/db.php";

// Protect the dashboard: Redirect to login if admin is not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// HANDLE APPROVAL ACTION - MUST BE BEFORE HTML OUTPUT
if (isset($_GET['approve_id'])) {
    $aid = intval($_GET['approve_id']);
    mysqli_query($conn, "UPDATE admin SET is_approved = 1 WHERE id = $aid");
    header("Location: dashboard.php?msg=admin_approved");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem; }
        .stat-card { background: var(--primary); color: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .action-list { list-style: none; padding: 0; }
        .action-item { background: white; padding: 1rem; margin-bottom: 0.5rem; border-radius: 8px; box-shadow: var(--shadow-light); display: flex; justify-content: space-between; align-items: center; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: var(--shadow-light); }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--light-gray); }
        th { background: var(--light-gray); }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo"><i class="fas fa-user-shield"></i> <span>Admin Panel</span></div>
            <a href="logout.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <main class="container">
        <div class="page-title">
            <h1>Control Center</h1>
            <p>Manage events and monitor registrations</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'admin_approved'): ?>
            <p style="color: var(--primary); margin-bottom: 15px;"><i class="fas fa-check-circle"></i> Administrator account approved successfully!</p>
        <?php endif; ?>

        <div class="admin-grid">
            <section>
                <h3>Overview</h3>
                <?php
                $events_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM events"))['count'];
                $registrations_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations"))['count'];
                ?>
                <div class="stat-card"><span>Total Events</span><h2><?php echo $events_count; ?></h2></div>
                <div class="stat-card" style="background: var(--secondary);"><span>Total Attendees</span><h2><?php echo $registrations_count; ?></h2></div>

                <div style="margin-top: 2rem;">
                    <a href="create_event.php" class="register-btn btn-block" style="text-align: center; text-decoration: none;"><i class="fas fa-plus"></i> Create New Event</a>
                </div>
            </section>

            <section>
                <h3>Existing Events</h3>
                <table>
                    <thead>
                        <tr><th>Title</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                            <td><?php echo date("M j, Y", strtotime($row['event_date'])); ?></td>
                            <td>
                                <a href="edit_event.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit" style="color: var(--primary);"></i></a>
                                <a href="delete_event.php?id=<?php echo $row['id']; ?>" style="margin-left: 10px; color: var(--accent);" onclick="return confirm('Delete this event?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <section style="margin-top: 3rem;">
            <h3>Pending Admin Requests</h3>
            <div class="form-container" style="max-width: 100%;">
                <?php
                $pending_admins = mysqli_query($conn, "SELECT * FROM admin WHERE is_approved = 0");
                if (mysqli_num_rows($pending_admins) > 0): ?>
                    <table>
                        <thead><tr><th>Username</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($pending_admins)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><a href="dashboard.php?approve_id=<?php echo $row['id']; ?>" class="register-btn" style="padding: 5px 15px; font-size: 0.8rem; text-decoration: none;">Approve</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No pending admin requests.</p>
                <?php endif; ?>
            </div>
        </section>

        <section style="margin-top: 3rem;">
            <h3>Recent Activity</h3>
            <div class="form-container" style="max-width: 100%;">
                <?php
                $activity = mysqli_query($conn, "SELECT r.*, e.title FROM registrations r JOIN events e ON r.event_id = e.id ORDER BY r.created_at DESC LIMIT 5");
                if (mysqli_num_rows($activity) > 0): ?>
                    <ul class="action-list">
                        <?php while ($row = mysqli_fetch_assoc($activity)): ?>
                            <li class="action-item">
                                <span><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></span>
                                <span class="event-id">Joined <strong><?php echo htmlspecialchars($row['title']); ?></strong></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No registrations yet.</p>
                <?php endif; ?>
                <a href="view_registrations.php" class="back-link">View All Attendee Details</a>
            </div>
        </section>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Admin Dashboard</p>
    </footer>
</body>
</html>