<?php
// signup-user.php

// Include your database connection here
require_once 'db.php'; // This file should contain your MySQL connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Validate the inputs
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Check if the email or phone already exists
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? OR phone = ?');
        $stmt->bind_param('ss', $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email or phone already registered.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare('INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $name, $email, $phone, $hashed_password);
            
            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header('Location: login-user.php');
                exit();
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Registration</title>
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
      max-width: 500px;
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

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    label {
      font-weight: 600;
      color: #f5f5f5;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
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
      font-size: 1.1rem;
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

    .error-message {
      color: #ff6b6b;
      background: rgba(0, 0, 0, 0.2);
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 20px;
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
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-5px);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>User Registration</h1>
    <?php if (isset($error_message)): ?>
      <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <form method="POST" action="signup-user.php">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required />

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required />

      <label for="password">Password</label>  
      <input type="password" id="password" name="password" placeholder="Enter your password" required />

      <br><br>

      <button type="submit" class="submit-btn">Register</button>
    </form>
  </div>
</body>
</html>