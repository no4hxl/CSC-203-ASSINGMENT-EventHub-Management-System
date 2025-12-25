# EventHub - Event Management System

EventHub is a professional, web-based platform built with PHP and MySQL for managing event discoveries and registrations. It features a modern user interface and a robust administrative back-end with a secure approval-based workflow.

## 🚀 Features

### **Public Portal**

- **Event Discovery**: A clean, card-based layout to browse upcoming events.
- **Live Search**: Search functionality that filters events by title, location, or category.
- **Dynamic Registration**: Simple registration flow for users to join events.

### **Administrative Suite**

- **Dashboard Overview**: Real-time statistics showing total events and attendee counts.
- **Event Management (CRUD)**: Full capability to Create, Read, Update, and Delete events.
- **Admin Approval System**: New administrative accounts are created in a "Pending" state and must be approved by an existing admin before they can log in.
- **Recent Activity Tracking**: Monitor the latest registrations directly from the dashboard.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (Updated to handle NULL types in PHP 8.1+)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Font Awesome Icons
- **Security**: SQL Prepared Statements for data protection

## 📋 Prerequisites

To run this project locally, ensure you have:

- **XAMPP** or **WAMP** (Apache & MySQL)
- **PHP 8.1+** installed
- A web browser

## 🔧 Installation & Setup

1. **Clone the Project**
   Place the project folder in your local server's root directory (e.g., `C:/xampp/htdocs/eventhub`).

2. **Database Configuration**

   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `event_mgt_system`.
   - Click the **Import** tab at the top.
   - Choose the `database.sql` file provided in the project root and click **Go**.
     _(This automatically creates all tables and the required `is_approved` structure)._

3. **Connect the Application**
   Open `includes/db.php` and update your database credentials:
   ```php
   $conn = mysqli_connect("localhost", "root", "your_password", "event_mgt_system");

   ```
4. **Launch**

- Start your Apache and MySQL modules.
- View the site at: `http://localhost/eventhub/index.php`
- Access Admin Login at: `http://localhost/eventhub/admin/login.php`

## 🔐 Administrative Workflow

1. **Register**: A new admin signs up via `admin/register_admin.php`.
2. **Pending State**: The account is initially locked and cannot log in.
3. **Approve**: An existing admin logs into the dashboard, views "Pending Admin Requests," and clicks **Approve**.

- _Note: The very first admin must be approved manually in phpMyAdmin by setting `is_approved` to 1 in the `admin` table._

## 📄 License

Distributed under the MIT License.
