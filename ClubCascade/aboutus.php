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

$sql = "SELECT * FROM events ORDER BY event_id DESC";
$result = $conn->query($sql);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home-user.php");
    exit();
}

$homeLink = "home-user.php";
if (isset($_SESSION['user_id'])) {
    $homeLink = "home-user.php";
} elseif (isset($_SESSION['org_id'])) {
    $homeLink = "home-org.php";
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
    .event-list-container {
      max-height: 600px;
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
    .body {
      margin-left: 20%;
      margin-right: 20%;
      margin-top: 3%;
      margin-bottom: 3%;
    }
    .image-container {
      float: right;
      margin-left: 20px;
      margin-bottom: 10px;
      width: 250px;
      text-align: center;
    }
    .image-container img {
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .caption {
      font-weight: bold;
      margin-top: 10px;
    }
    .designation {
      font-size: 14px;
      font-weight: normal;
      line-height: 1.4;
    }
    @media (max-width: 768px) {
      .image-container {
        float: none;
        margin: 0 auto 20px auto;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>ClubCascade</h1>
    <nav>
      <ul>
        <li><a href="<?php echo $homeLink; ?>">Home</a></li>
        <li><a href="<?php echo $homeLink; ?>#events">Events</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="aboutus.php">About Us</a></li>
      </ul>
    </nav>
    <div class="auth-buttons">
      <?php if (isset($_SESSION['user_id']) || isset($_SESSION['org_id'])): ?>
        <button class="nav-btn logout-btn" onclick="location.href='?logout=1'">Logout</button>
      <?php else: ?>
        <button class="nav-btn login-btn" onclick="location.href='login-select.html'">Login</button>
        <button class="nav-btn signup-btn" onclick="location.href='signup.html'">Sign Up</button>
      <?php endif; ?>
    </div>
  </header>

  <section class="body">
    <h1>Welcome to ClubCascade – Your Gateway to College Event Excellence</h1>

    <div class="image-container">
      <img src="ab3.webp" alt="Founder Image" />
      <div class="caption">Prof. Dr. Vishwanath D. Karad</div>
      <div class="designation">
        Founder, Executive President & Managing Trustee<br/>
        MIT ADT University, Pune
      </div>
    </div>

    <p><strong>ClubCascade</strong> is a powerful and dynamic digital platform designed to revolutionize the way college events are organized, promoted, and experienced. Developed with a student-first mindset, our mission is to empower student communities by making campus events more accessible, engaging, and easier to manage for everyone involved.</p>

    <p>Whether you're a student eager to participate in events or a club organizer working behind the scenes, ClubCascade streamlines the entire experience from start to finish. At its core, ClubCascade bridges the gap between event organizers and attendees.</p>

    <p>In many universities, students miss out on events due to poor communication or a lack of visibility. ClubCascade eliminates these barriers with a centralized platform that showcases all ongoing and upcoming events in one easy-to-navigate space. From tech talks and workshops to cultural fests and competitions, students can now discover opportunities tailored to their interests in real-time.</p>

    <h2>For Students</h2>
    <p>ClubCascade is more than just an event calendar. It’s an interactive platform where students can browse events by category, register with a single click, get notified about updates or changes, and even provide feedback after attending.</p>

    <p>Our personalized dashboard allows users to track their event history, download participation certificates, and connect with other attendees, making the entire event journey seamless and rewarding.</p>

    <h2>For Event Organizers</h2>
    <p>ClubCascade offers a robust suite of tools that simplifies the entire event management lifecycle. From creating visually appealing event listings with banners, descriptions, and registration links to managing RSVPs and collecting real-time analytics – everything is handled effortlessly through our admin panel.</p>

    <p>Organizers can communicate with registered participants directly via the platform, send out reminders, and publish last-minute announcements with ease. This significantly reduces the hassle of using multiple tools or relying on social media posts that often get lost in the clutter.</p>

    <h2>Smart Integration & Seamless UX</h2>
    <p>One of our standout features is <strong>smart integration</strong> with MIT ADT University’s existing digital infrastructure. ClubCascade is tailored specifically to our campus, ensuring compatibility with student portals, official communication channels, and academic calendars.</p>

    <p>We’ve also prioritized a user-friendly experience with a clean, modern interface that works across devices – whether you’re on a smartphone, tablet, or desktop. With real-time updates and cloud-based architecture, ClubCascade ensures that no event goes unnoticed or unappreciated.</p>

    <h2>Our Vision</h2>
    <p>Proudly developed and maintained by the students of <strong>MIT ADT University</strong>, ClubCascade reflects our commitment to fostering a more vibrant, connected, and collaborative campus culture. Our vision goes beyond just event management – we aim to spark innovation, support student-led initiatives, and build a thriving digital ecosystem for co-curricular engagement.</p>

    <p>Whether you’re planning something extraordinary or just looking for something exciting to do this weekend, ClubCascade is your trusted companion in navigating the dynamic world of campus life.</p>

    <p><strong>Discover. Participate. Celebrate. – Only with ClubCascade.</strong></p>
  </section>

  <footer>
    <p>&copy; 2025 ClubCascade | MIT ADT University</p>
  </footer>
</body>
</html>
