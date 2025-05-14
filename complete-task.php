<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connection.php';

if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Update task status to Completed
    $sql = "UPDATE tasks SET status = 'Completed', complete_at = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Task marked as completed!'); window.location.href = 'completed-task.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid task ID.'); window.history.back();</script>";
}
?>
