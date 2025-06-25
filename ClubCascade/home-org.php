<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clubcascade";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Changed to fetch all events instead of just 3
$sql = "SELECT * FROM events ORDER BY event_id DESC";
$result = $conn->query($sql);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home-user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Organizer Dashboard | MIT ADT</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
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
    header {
      background-color: rgba(108, 48, 130, 0.9);
    }
    /* New styles for scrolling events */
    .event-list-container {
      max-height: 500px; /* Adjust this value as needed */
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
    <h1>ClubCascade Organizers</h1>
    <nav>
      <ul>
        <li><a href="#home">Dashboard</a></li>
        <li><a href="#events">My Events</a></li>
        <li><a href="add-events.php" class="btn-add">Add Event</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="aboutus.php">About Us</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <?php if (isset($_SESSION['org_id'])): ?>
        <button class="nav-btn logout-btn" onclick="location.href='?logout=1'">Logout</button>
      <?php else: ?>
        <button class="nav-btn login-btn" onclick="location.href='login-org.php'">Organizer Login</button>
        <button class="nav-btn signup-btn" onclick="location.href='register-org.php'">Register</button>
      <?php endif; ?>
    </div>
  </header>

  <section id="home" class="hero">
    <h2>Organizer Dashboard</h2>
    <p>Manage your events and track registrations.</p>
  </section>

  <section id="events" class="events">
    <h2>My Events</h2>
    <div class="event-list-container">
      <div class="event-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="event">';
                echo '<h3>' . $row['event_name'] . '</h3>';
                echo '<p><strong>Date:</strong> ' . $row['event_date'] . '</p>';
                echo '<p><strong>Venue:</strong> ' . $row['venue'] . '</p>';
                echo '<p><strong>Attendees:</strong> ' . ($row['attendees'] ?? '0') . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No events found.</p>';
        }
        $conn->close();
        ?>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 ClubCascade Organizers | MIT ADT University</p>
  </footer>
</body>
</html>