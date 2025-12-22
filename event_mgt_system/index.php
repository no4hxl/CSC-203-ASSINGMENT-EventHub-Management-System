<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "includes/db.php";

// SEARCH LOGIC
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    // Search in title, location, or category
    $sql = "SELECT * FROM events WHERE 
            title LIKE '%$search_query%' OR 
            location LIKE '%$search_query%' OR 
            category LIKE '%$search_query%' 
            ORDER BY event_date ASC";
} else {
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <div class="logo">
                        <i class="fas fa-calendar-alt"></i>
                        <span>EventHub</span>
                    </div>
                </a>
                <p class="tagline">Discover and register for amazing events near you</p>
            </div>
            <div class="admin-link">
                <a href="admin/login.php" style="color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-user-shield"></i> Event Management System
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-title">
            <h1>Available Events</h1>
            <p class="page-subtitle">Browse through our curated selection of upcoming events</p>
        </div>

        <div class="search-container" style="max-width: 800px; margin: 0 auto 3rem auto;">
            <form action="index.php" method="GET" style="display: flex; gap: 10px;">
                <div style="position: relative; flex-grow: 1;">
                    <i class="fas fa-search" style="position: absolute; left: 15px; top: 15px; color: var(--gray);"></i>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by event name, location, or category..." 
                           value="<?php echo htmlspecialchars($search_query ?? ""); ?>"
                           style="padding-left: 45px; height: 50px;">
                </div>
                <button type="submit" class="register-btn" style="height: 50px; padding: 0 30px;">
                    Search
                </button>
                <?php if(!empty($search_query)): ?>
                    <a href="index.php" class="register-btn" style="height: 50px; background: var(--gray); display: flex; align-items: center; text-decoration: none;">
                        Clear
                    </a>
                <?php endif; ?>
            </form>
            <?php if(!empty($search_query)): ?>
                <p style="margin-top: 10px; color: var(--gray); font-style: italic;">
                    Showing results for: "<?php echo htmlspecialchars($search_query ?? ""); ?>"
                </p>
            <?php endif; ?>
        </div>

        <div class="events-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventDate = date("F j, Y", strtotime($row['event_date']));
            ?>
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-date"><i class="far fa-calendar"></i> <?php echo $eventDate; ?></span>
                        <h3 class="event-title"><?php echo htmlspecialchars($row['title'] ?? ""); ?></h3>
                    </div>
                    <div class="event-body">
                        <p class="event-description"><?php echo htmlspecialchars($row['description'] ?? ""); ?></p>
                        <div class="event-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i> 
                                <span><?php echo htmlspecialchars($row['location'] ?? ""); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-tag"></i> 
                                <span><?php echo htmlspecialchars($row['category'] ?? "Uncategorized"); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="event-footer">
                        <a href="register.php?id=<?php echo $row['id']; ?>" class="register-btn">Register</a>
                        <div class="event-id">#<?php echo $row['id']; ?></div>
                    </div>
                </div>
            <?php 
                }
            } else {
            ?>
                <div class="no-events">
                    <i class="far fa-calendar-times"></i>
                    <h3>No Events Found</h3>
                    <p>We couldn't find anything matching "<?php echo htmlspecialchars($search_query ?? ""); ?>". Try a different keyword.</p>
                </div>
            <?php } ?>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> Event Management System.</p>
    </footer>
</body>
</html>