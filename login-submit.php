<?php
session_start();
include 'connection.php';

// Sanitize input
$email = trim($_POST['email']);
$password = $_POST['password'];

// Validation
if (empty($email) || empty($password)) {
    echo "<script>alert('Please enter both email and password.'); window.history.back();</script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.history.back();</script>";
    exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        // Login success, start session
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;

        echo "<script>
                alert('Login successful! Welcome, $name.');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "<script>alert('Incorrect password.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('No account found with this email.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
