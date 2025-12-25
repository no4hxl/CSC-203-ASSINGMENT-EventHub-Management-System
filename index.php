<?php
// Enable error reporting for debugging - useful for school projects!
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include our cleaned up database connection
include "includes/db.php";

// SEARCH LOGIC
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    
    /**
     * SECURITY UPGRADE: Prepared Statements
     * Instead of putting $search_query directly into the SQL string, we use '?' as placeholders.
     * This prevents SQL Injection by making sure the database treats the input as text only.
     */
    $sql = "SELECT * FROM events WHERE 
            title LIKE ? OR 
            location LIKE ? OR 
            category LIKE ? 
            ORDER BY event_date ASC";
            
    $stmt = mysqli_prepare($conn, $sql);
    
    // Create the search term with wildcards (%)
    $search_param = "%" . $search_query . "%";
    
    // Bind the parameters: "sss" means three strings
    mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param);
    
    // Execute the query
    mysqli_stmt_execute($stmt);
    
    // Get the results
    $result = mysqli_stmt_get_result($stmt);
} else {
    // If no search, show all events
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventHub | Discover Amazing Events</title>
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
            <div class="admin-link">
                <!-- Clean, professional link to administration -->
                <a href="admin/login.php" style="color: white; text-decoration: none; font-size: 0.95rem; font-weight: 600;">
                    <i class="fas fa-user-shield"></i> Admin Portal
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Visual Title Section -->
        <div class="page-title">
            <h1>Discover Your Next Event</h1>
            <p class="page-subtitle">Join amazing gatherings, workshops, and meetups happening near you.</p>
        </div>

        <!-- Modern Search UI -->
        <div class="search-container" style="max-width: 850px;">
            <form action="index.php" method="GET">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search events, locations, or categories..." 
                           value="<?php echo htmlspecialchars($search_query ?? ""); ?>">
                </div>
                <button type="submit" class="register-btn">
                    <i class="fas fa-filter"></i> Search Events
                </button>
                <?php if(!empty($search_query)): ?>
                    <a href="index.php" class="register-btn" style="background: var(--text-muted); opacity: 0.8;">
                        Clear
                    </a>
                <?php endif; ?>
            </form>
            
            <?php if(!empty($search_query)): ?>
                <div class="results-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Showing results for: "<?php echo htmlspecialchars($search_query); ?>"</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dynamic Events Grid -->
        <div class="events-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Logic to format date nicely
                    $eventDate = date("M d, Y", strtotime($row['event_date']));
            ?>
                <!-- Premium Event Card -->
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-date">
                            <i class="far fa-calendar-check"></i> <?php echo $eventDate; ?>
                        </div>
                        <h3 class="event-title"><?php echo htmlspecialchars($row['title'] ?? ""); ?></h3>
                    </div>
                    
                    <div class="event-body">
                        <p class="event-description"><?php echo htmlspecialchars($row['description'] ?? ""); ?></p>
                        
                        <div class="event-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i> 
                                <span><?php echo htmlspecialchars($row['location'] ?? "Remote / TBD"); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-layer-group"></i> 
                                <span><?php echo htmlspecialchars($row['category'] ?? "General"); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-footer">
                        <a href="register.php?id=<?php echo $row['id']; ?>" class="register-btn">
                            Book Spot <i class="fas fa-arrow-right"></i>
                        </a>
                        <div class="event-id">Ref #<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></div>
                    </div>
                </div>
            <?php 
                }
            } else {
            ?>
                <!-- Modern Empty State -->
                <div class="no-events">
                    <i class="far fa-folder-open"></i>
                    <h3>No Events Found</h3>
                    <p>We couldn't find any results for your search. Try adjusting your keywords!</p>
                </div>
            <?php } ?>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> EventHub Platform. Built for Excellence.</p>
    </footer>
</body>
</html>
