<?php
session_start();
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Sanitize user input
    $identifier = trim($_POST['identifier']);
    $password = trim($_POST['password']);

    // Prepare SQL to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, email, phone, password FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            
            // Redirect to dashboard or home page
            header("Location: home-user.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login-User</title>
  <style>
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
    .container {
      background: rgba(251, 233, 231, 0.2);
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
    h1 {
      text-align: center;
      color: #FFD700;
      margin-bottom: 30px;
      font-size: 2.2rem;
      animation: float 2.5s ease-in-out infinite;
    }
    .form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .form-group {
      position: relative;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #f5f5f5;
    }
    input {
      width: 100%;
      padding: 14px 18px;
      font-size: 1rem;
      border: 2px solid transparent;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      transition: all 0.4s ease;
    }
    input::placeholder {
      color: #ddd;
    }
    input:focus {
      border: 2px solid #A67C00;
      outline: none;
      background: rgba(255, 255, 255, 0.25);
      box-shadow: 0 0 12px #A67C00;
      transform: scale(1.02);
    }
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
    .error {
      color: #ff6b6b;
      text-align: center;
      margin-top: -15px;
      margin-bottom: 10px;
      font-weight: 600;
    }
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
      0% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
      100% { transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <?php if (!empty($error)): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form class="form" method="POST">
      <div class="form-group">
        <label for="identifier">Email or Phone Number</label>
        <input type="text" id="identifier" name="identifier" placeholder="Enter email or phone" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required />
      </div>
      <button type="submit" class="submit-btn">Login</button>
    </form>
  </div>
</body>
</html>