<?php
include 'connection.php';
// Sanitize input
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];

// Basic validation
if (empty($name) || empty($email) || empty($password)) {
    echo "<script>alert('All fields are required.'); window.history.back();</script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.history.back();</script>";
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>
            alert('This email is already registered. Please login OR try another email.');
            window.location.href = 'signup.html';
          </script>";
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    echo "<script>
            alert('Signup successful! Please login.');
            window.location.href = 'login.html';
          </script>";
} else {
    echo "<script>alert('Something went wrong. Please try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>