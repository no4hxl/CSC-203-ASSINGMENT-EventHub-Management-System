# EventHub - Event Management System

EventHub is a simple and professional website for managing events. People can use it to **find events and register**, while admins can use the dashboard to **manage attendees and create new events**.

## 📚 Tech Glossary

Here is a quick breakdown of the tools we are using:

* **XAMPP**: This is a "Local Server Environment." It allows you to run a website on your own computer without needing to pay for hosting.
* **Apache (The Web Server)**: Think of this as the "Waiter." When you type a URL in your browser, Apache goes to your folder, finds the right page, and brings it back to your screen.
* **MySQL (The Database)**: This is like a digital filing cabinet. It stores all the information about your events, admin accounts, and registrations in organized tables.
* **PHP**: This is the "Brain" of the website. It handles things like checking if a password is correct or calculating how many people have registered for an event.
* **phpMyAdmin**: A simple web tool included with XAMPP that lets you see and manage your MySQL database tables without writing code.

* 
---

## 🚀 Key Features

* **Public Website**: Users can search for events by name or location and register instantly.
* **Admin Dashboard**: Manage all events (Add, Edit, Delete) and see how many people registered.
* **Approval System**: New admin accounts must be "Approved" by an existing admin before they can log in.

## 🛠️ What You Need (Prerequisites)

Before you start, make sure you have **XAMPP** installed on your computer. 
* **XAMPP** provides the "Server" (Apache) and the "Database" (MySQL) needed to run this project.
* You can download it for free from [Apache Friends](https://www.apachefriends.org/).

---

---

## 🔧 Setup Instructions (Step-by-Step)

### 1. Move the Project Files
Copy the entire `eventhub` folder and paste it into your XAMPP "htdocs" folder.
* **Path:** `C:\xampp\htdocs\eventhub`

### 2. Start the Server
Open the **XAMPP Control Panel** and click the **Start** button for both **Apache** and **MySQL**.

### 3. Setup the Database
1.  Open your web browser and go to: `http://localhost/phpmyadmin`
2.  Click **New** on the left sidebar and name the database: `event_mgt_system`
3.  Click the **Import** tab at the top of the page.
4.  Click **Choose File** and select the `database.sql` file located inside your project folder.
5.  Scroll to the bottom and click **Go**. Your tables are now ready!

### 4. Connect the Code to the Database
1.  Go to your project folder and open `includes/db.php` with Notepad or VS Code.
2.  Look for this line: 
    `$conn = mysqli_connect("localhost", "root", "", "event_mgt_system");`
3.  If you have a password on your MySQL, put it in the empty quotes `""`. Most beginners can leave it empty.

### 5. Launch the Website
* **To view the site:** Go to `http://localhost/eventhub/index.php`
* **To log in as Admin:** Go to `http://localhost/eventhub/admin/login.php`

---

## 🔐 Admin "First-Time" Login
Because of the security system, the **very first admin** needs to be approved manually:
1.  Register an account at `admin/register_admin.php`.
2.  Go back to **phpMyAdmin**, click the `admin` table.
3.  Click **Edit** on your account and change `is_approved` from **0** to **1**.
4.  Now you can log in! Future admins can be approved directly from your Dashboard.

---

## 🆘 Common Troubleshooting

**Problem: "Database Connection Failed"**
* **Fix:** Ensure your XAMPP Control Panel shows MySQL is running (it should be green).
* **Fix:** Check `includes/db.php` to ensure the database name `event_mgt_system` matches exactly what you created in phpMyAdmin.

**Problem: "404 Not Found"**
* **Fix:** Ensure your folder name in `htdocs` is exactly `eventhub`. If you renamed it, the URL must change too (e.g., `http://localhost/your-new-name/`).
* 
