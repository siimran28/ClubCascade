<?php
session_start();

// Check if organizer is logged in
if (!isset($_SESSION['org_id'])) {
    header("Location: login-org.php");
    exit();
}

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

// Initialize variables
$name = $date = $time = $venue = $committee = $duration = $description = "";
$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $committee = mysqli_real_escape_string($conn, $_POST['committee']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $organizer_id = $_SESSION['org_id'];

    // Check if venue is already booked for this date and time
    $check_sql = "SELECT * FROM events 
                 WHERE venue = '$venue' 
                 AND event_date = '$date' 
                 AND event_time = '$time'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $error = "Error: The venue is already booked for this date and time.";
    } else {
        // Insert into database
        $sql = "INSERT INTO events (event_name, event_date, event_time, venue, committee, duration, description)
                VALUES ('$name', '$date', '$time', '$venue', '$committee', '$duration', '$description')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to success page or back to dashboard
            header("Location: home-org.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Event | ClubCascade</title>
  <style>
    /* Reset and Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #6C3082, #AC91CE);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  /* Glassmorphism Container */
  .container {
    background: rgba(251, 233, 231, 0.2); /* Hint of Red with transparency */
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 720px;
    animation: fadeIn 1s ease;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.15);
  }
  
  /* Header */
  h1 {
    text-align: center;
    color: #FFD700; /* Luxer Gold */
    margin-bottom: 30px;
    font-size: 2.2rem;
    animation: float 2.5s ease-in-out infinite;
  }
  
  /* Form Styling */
  .form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  /* Form Group */
  .form-group {
    position: relative;
  }
  
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #f5f5f5;
    transition: all 0.3s ease-in-out;
  }
  /* Hide default file input */
input[type="file"] {
    display: none;
  }
  
  /* Custom file upload button */
  .custom-file-label {
    display: inline-block;
    background-color: #0052CC;
    color: #fff;
    padding: 12px 20px;
    font-size: 0.95rem;
    border-radius: 8px; /* Rounded corners */
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    text-align: center;
    font-weight: 500;
    width: fit-content;
  }
  
  .custom-file-label:hover {
    background-color: #0046B3;
    transform: scale(1.03);
  }
  
  
  /* Inputs & Textareas */
  input[type="text"],
  input[type="date"],
  input[type="time"],
  input[type="file"],
  textarea {
    width: 100%;
    padding: 14px 18px;
    font-size: 1rem;
    border: 2px solid transparent;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    transition: all 0.4s ease;
    box-shadow: inset 0 0 0 transparent;
  }
  
  input::placeholder,
  textarea::placeholder {
    color: #ddd;
  }
  
  input:focus,
  textarea:focus {
    border: 2px solid #A67C00;
    outline: none;
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 0 12px #A67C00;
    transform: scale(1.02);
  }
  
  /* Button Styling */
  .submit-btn {
    background: linear-gradient(45deg, #A67C00, #6C3082);
    padding: 14px;
    border: none;
    color: white;
    font-size: 1.2rem;
    border-radius: 14px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.4s ease-in-out;
    letter-spacing: 1px;
    box-shadow: 0 5px 15px rgba(166, 124, 0, 0.4);
  }
  
  .submit-btn:hover {
    background: linear-gradient(45deg, #6C3082, #A67C00);
    box-shadow: 0 10px 25px rgba(172, 145, 206, 0.5);
    transform: translateY(-2px) scale(1.03);
  }
  
  /* Animations */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }
  
  @keyframes float {
    0% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-5px);
    }
    100% {
      transform: translateY(0);
    }
  }
  
  /* Responsive */
  @media (max-width: 600px) {
    .container {
      padding: 30px 20px;
    }
  
    h1 {
      font-size: 1.7rem;
    }
  }
  
  
  </style>
</head>
<body>
  <div class="container">
    <h1>Add New Event</h1>
    <?php if (!empty($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form">
      <div class="form-group">
        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" placeholder="e.g. Tech Conference 2025" value="<?php echo $name; ?>" required />
      </div>
      <div class="form-group">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo $date; ?>" required />
      </div>
      <div class="form-group">
        <label for="time">Time</label>
        <input type="time" id="time" name="time" value="<?php echo $time; ?>" required />
      </div>
      <div class="form-group">
        <label for="venue">Venue</label>
        <input type="text" id="venue" name="venue" placeholder="e.g. Auditorium Hall" value="<?php echo $venue; ?>" required />
      </div>
      <div class="form-group">
        <label for="committee">Organising Committee</label>
        <input type="text" id="committee" name="committee" placeholder="e.g. CSE Department" value="<?php echo $committee; ?>" required />
      </div>
      <div class="form-group">
        <label for="duration">Duration</label>
        <input type="text" id="duration" name="duration" placeholder="e.g. 3 Hours" value="<?php echo $duration; ?>" required />
      </div>
      <div class="form-group">
        <label for="description">Short Description</label>
        <textarea id="description" name="description" rows="4" placeholder="Brief summary of the event..." required><?php echo $description; ?></textarea>
      </div>
      <button type="submit" class="submit-btn">Submit Event</button>
    </form>
  </div>
</body>
</html>