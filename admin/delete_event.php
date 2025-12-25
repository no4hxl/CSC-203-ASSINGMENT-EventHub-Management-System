<?php
session_start();

/**
 * Security Check: Ensure only logged-in admins can access this script.
 * If not logged in, redirect them to the login page immediately.
 */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Include your database connection file
include "../includes/db.php";

/**
 * Check if an 'id' has been passed in the URL (e.g., delete_event.php?id=5)
 */
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanitize the input by converting it to an integer
    $id = intval($_GET['id']);
    
    /**
     * Use a Prepared Statement for deletion.
     * This prevents SQL Injection attacks by separating the query from the data.
     */
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        /**
         * SUCCESS: Redirect back to the dashboard.
         * We pass a 'msg' parameter so the dashboard can show a "Deleted Successfully" alert.
         */
        header("Location: dashboard.php?msg=deleted");
        exit();
    } else {
        // ERROR: If the query fails, display the database error for debugging
        echo "Error deleting record: " . $conn->error;
    }
    
    // Close the prepared statement to free up resources
    $stmt->close();
} else {
    /**
     * If no valid ID was provided, just send the user back to the dashboard
     * to prevent them from staying on a blank page.
     */
    header("Location: dashboard.php");
    exit();
}
?>