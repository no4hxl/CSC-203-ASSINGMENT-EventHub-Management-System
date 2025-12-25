<?php
session_start();
include "../includes/db.php";

// Protect the dashboard: Redirect to login if admin is not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// HANDLE APPROVAL ACTION - SECURED WITH PREPARED STATEMENTS
if (isset($_GET['approve_id'])) {
    $aid = intval($_GET['approve_id']);
    $stmt = mysqli_prepare($conn, "UPDATE admin SET is_approved = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $aid);
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php?msg=admin_approved");
    exit();
}

// HANDLE DECLINE/REJECT ACTION - SECURED WITH PREPARED STATEMENTS
if (isset($_GET['decline_id'])) {
    $did = intval($_GET['decline_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM admin WHERE id = ? AND is_approved = 0");
    mysqli_stmt_bind_param($stmt, "i", $did);
    mysqli_stmt_execute($stmt);
    header("Location: dashboard.php?msg=admin_declined");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EventHub</title>
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
                <a href="logout.php" style="color: white; text-decoration: none; font-size: 0.95rem; font-weight: 600;">
                    <i class="fas fa-sign-out-alt"></i> Secure Logout
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Control Center</h1>
            <p class="page-subtitle">Manage your community, monitor events, and regulate access.</p>
        </div>

        <!-- Professional Alerts -->
        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'admin_approved'): ?>
                <div class="success-alert">
                    <i class="fas fa-check-circle"></i> <span>Administrator account approved successfully!</span>
                </div>
            <?php elseif($_GET['msg'] == 'admin_declined'): ?>
                <div class="success-alert" style="background: #fee2e2; color: #991b1b; border-color: #fecaca;">
                    <i class="fas fa-user-slash"></i> <span>The administrative request has been declined and removed.</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="admin-grid">
            <!-- Left Column: Stats & Actions -->
            <aside>
                <div class="dashboard-section">
                    <h3><i class="fas fa-chart-pie" style="color: var(--primary);"></i> Overview</h3>
                    <div class="stat-cards-container">
                        <?php
                        $events_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM events"))['count'];
                        $registrations_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM registrations"))['count'];
                        ?>
                        <div class="stat-card stat-primary">
                            <span>Total Events</span>
                            <h2><?php echo $events_count; ?></h2>
                        </div>
                        <div class="stat-card stat-secondary">
                            <span>Total Attendees</span>
                            <h2><?php echo $registrations_count; ?></h2>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <a href="create_event.php" class="register-btn btn-block" style="justify-content: center;">
                            <i class="fas fa-plus-circle"></i> Create New Event
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Right Column: Main Tables -->
            <section>
                <div class="dashboard-section">
                    <h3><i class="fas fa-calendar-day" style="color: var(--secondary);"></i> Active Events</h3>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Scheduled Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
                                while ($row = mysqli_fetch_assoc($result)): 
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td><i class="far fa-clock"></i> <?php echo date("M j, Y", strtotime($row['event_date'])); ?></td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn-approve" style="background: #eff6ff; color: #1e40af;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn-decline" style="margin-left: 8px;" onclick="return confirm('Permanently delete this event?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="dashboard-section">
                    <h3><i class="fas fa-user-clock" style="color: #f59e0b;"></i> Pending Admin Access</h3>
                    <div class="data-table-wrapper">
                        <?php
                        $pending_admins = mysqli_query($conn, "SELECT * FROM admin WHERE is_approved = 0");
                        if (mysqli_num_rows($pending_admins) > 0): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Requested On</th>
                                        <th>Regulate Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($pending_admins)): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                        <td><span class="event-id">New Request</span></td>
                                        <td style="display: flex; gap: 10px;">
                                            <a href="dashboard.php?approve_id=<?php echo $row['id']; ?>" class="btn-approve">
                                                <i class="fas fa-check"></i> Approve
                                            </a>
                                            <a href="dashboard.php?decline_id=<?php echo $row['id']; ?>" class="btn-decline" onclick="return confirm('Decline this admin request?');">
                                                <i class="fas fa-times"></i> Decline
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div style="text-align: center; padding: 20px; color: var(--text-muted);">
                                <i class="fas fa-user-check" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                                <p>No pending administrative requests at this time.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dashboard-section">
                    <h3><i class="fas fa-history" style="color: var(--primary);"></i> Recent Registrations</h3>
                    <?php
                    $activity = mysqli_query($conn, "SELECT r.*, e.title FROM registrations r JOIN events e ON r.event_id = e.id ORDER BY r.created_at DESC LIMIT 5");
                    if (mysqli_num_rows($activity) > 0): ?>
                        <div class="data-table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Attendee</th>
                                        <th>Event Joined</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($activity)): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><span class="event-id"><?php echo date("M j", strtotime($row['created_at'])); ?></span></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--text-muted); text-align: center;">No activity recorded yet.</p>
                    <?php endif; ?>
                    <div style="margin-top: 20px; text-align: right;">
                        <a href="view_registrations.php" class="back-link">
                            View Detailed Activity <i class="fas fa-external-link-alt" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Admin Dashboard. Built with Security & Design.</p>
    </footer>
</body>
</html>
