<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - EventHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Specific styling for the success message */
        .success-card {
            text-align: center;
            padding: 3rem;
        }
        .success-icon {
            font-size: 4rem;
            color: #4cc9f0; /* Using your --success variable color */
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-calendar-alt"></i>
                <span>EventHub</span>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="form-container success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 style="margin-bottom: 1rem;">Registration Complete!</h1>
            <p style="color: var(--gray); margin-bottom: 2rem;">
                Thank you for registering. We have received your details and your spot is now reserved. 
                Check your email for further instructions.
            </p>
            
            <a href="index.php" class="register-btn">
                <i class="fas fa-home"></i> Return to Home
            </a>
        </div>
    </main>

    <footer class="container">
        <p>&copy; <?php echo date("Y"); ?> Event Management System.</p>
    </footer>
</body>
</html>