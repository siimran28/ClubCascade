<?php
session_start(); // Start session at the beginning

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clubcascade";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Changed to fetch all events instead of just 3
$sql = "SELECT * FROM events ORDER BY event_id DESC";
$result = $conn->query($sql);

// Check if logout was requested
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home-user.php"); // Redirect to home page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Management | MIT ADT</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    /* Add these styles to your existing styles.css or here */
    .auth-buttons {
      display: flex;
      gap: 10px;
    }
    .nav-btn {
      padding: 8px 16px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .login-btn {
      background-color: #A67C00;
      color: white;
    }
    .signup-btn {
      background-color: #A67C00;
      color: white;
    }
    .logout-btn {
      background-color: #d9534f;
      color: white;
    }
    .nav-btn:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }
    .add-event-btn {
      background-color: #5cb85c;
      color: white;
      margin-right: 10px;
    }
    /* New styles for scrolling events */
    .event-list-container {
      max-height: 600px; /* Adjust this value as needed */
      overflow-y: auto;
      padding: 10px;
      border-radius: 8px;
      background-color: #f8f9fa;
      box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    }
    .event-list {
      display: grid;
      gap: 20px;
    }
    /* Optional: Style scrollbar */
    .event-list-container::-webkit-scrollbar {
      width: 8px;
    }
    .event-list-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    .event-list-container::-webkit-scrollbar-thumb {
      background: #6C3082;
      border-radius: 10px;
    }
    .event-list-container::-webkit-scrollbar-thumb:hover {
      background: #5a2570;
    }
  </style>
</head>
<body>
  <header>
    <h1>ClubCascade</h1>
    <nav>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="aboutus.php">About Us</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <?php if (isset($_SESSION['user_id']) || isset($_SESSION['org_id'])): ?>
        <!-- Show logout button if user/organizer is logged in -->
        <button class="nav-btn logout-btn" onclick="location.href='?logout=1'">Logout</button>
      <?php else: ?>
        <!-- Show login/signup buttons if not logged in -->
        <button class="nav-btn login-btn" onclick="location.href='login-select.html'">Login</button>
        <button class="nav-btn signup-btn" onclick="location.href='signup.html'">Sign Up</button>
      <?php endif; ?>
    </div>
  </header>

  <section id="home" class="hero">
    <h2>Welcome to ClubCascade</h2>
    <p>Stay updated with all upcoming events at MIT ADT University.</p>
  </section>

  <section id="events" class="events">
    <h2>Upcoming Events</h2>
    <div class="event-list-container">
      <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="event">';
                echo '<h3>' . $row['event_name'] . '</h3>';
                echo '<p><strong>Date:</strong> ' . $row['event_date'] . '</p>';
                echo '<p><strong>Venue:</strong> ' . $row['venue'] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row['description'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No upcoming events found.</p>';
        }
        $conn->close();
        ?>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 ClubCascade | MIT ADT University</p>
  </footer>
</body>
</html>