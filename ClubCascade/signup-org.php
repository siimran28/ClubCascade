<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "ClubCascade";

$register_success = false;
$register_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = new mysqli($host, $user, $pass, $db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $org_name = trim($_POST['org_name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if email already exists
  $check = $conn->prepare("SELECT org_id FROM Organizers WHERE org_email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check_result = $check->get_result();

  if ($check_result->num_rows > 0) {
    $register_error = "An organiser with this email already exists.";
  } else {
    $stmt = $conn->prepare("INSERT INTO Organizers (org_name, org_email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $org_name, $email, $hashed_password);

    if ($stmt->execute()) {
      $register_success = true;
      // Redirect to login page after successful registration
      header("Location: login-org.php");
      exit();
    } else {
      $register_error = "Registration failed. Please try again.";
    }
    $stmt->close();
  }

  $check->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Organiser Registration</title>
  <style>
    body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #6C3082, #AC91CE);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0;
  padding: 0;
}

.container {
  background: rgba(251, 233, 231, 0.15);
  backdrop-filter: blur(15px);
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  width: 90%;
  max-width: 480px;
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.15);
  display: flex;
  flex-direction: column;
  gap: 20px;
}

h1 {
  text-align: center;
  color: #FFD700;
  font-size: 2.2rem;
  margin-bottom: 10px;
}

.success, .error {
  text-align: center;
  font-weight: bold;
  margin-top: -10px;
}

.success {
  color: lightgreen;
}

.error {
  color: yellow;
}

form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

label {
  font-weight: 600;
  color: #f5f5f5;
  margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 14px;
  font-size: 1rem;
  border: 2px solid transparent;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.15);
  color: #fff;
  box-sizing: border-box;
}

input:focus {
  border: 2px solid #A67C00;
  background: rgba(255, 255, 255, 0.25);
  outline: none;
}

.submit-btn {
  background: linear-gradient(45deg, #A67C00, #6C3082);
  padding: 14px;
  border: none;
  color: white;
  font-size: 1.1rem;
  border-radius: 14px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s ease-in-out;
  width: 100%;
}

.submit-btn:hover {
  transform: translateY(-2px);
  background: linear-gradient(45deg, #6C3082, #A67C00);
}

  </style>
</head>
<body>
  <div class="container">
    <h1>Organiser Registration</h1>
    <?php if ($register_success): ?>
      <div class="success">Organiser registered successfully!</div>
    <?php elseif ($register_error): ?>
      <div class="error"><?= htmlspecialchars($register_error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label for="org_name">Organisation Name</label>
      <input type="text" id="org_name" name="org_name" required />

      <label for="email">Organisation Email</label>
      <input type="email" id="email" name="email" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required />

      <button type="submit" class="submit-btn">Register</button>
    </form>
  </div>
</body>
</html>
