<?php
// Include session check and DB connection
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $task_title = $_POST['title'];
    $deadline = $_POST['due_time'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    $check_sql = "SELECT id FROM tasks WHERE user_id = ? AND priority = ? AND status = 'Pending'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $priority);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('This priority is already assigned to another pending task. Please choose a different one.'); window.history.back();</script>";
        exit;
    }
    $check_stmt->close();

    $sql = "INSERT INTO tasks (title, due_time, category, priority, user_id, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $task_title, $deadline, $category, $priority, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Task added successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again!');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task</title>
    <!-- Add in <head> section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    /* Add Task Form */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9f9f9;
    }

    .add-task-form {
        background-color: #f9f9f9;
        padding: 40px 20px;
        border-radius: 8px;
        margin: 30px auto;
        max-width: 800px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .add-task-form h2 {
        font-size: 26px;
        color: #2c3e50;
        margin-bottom: 30px;
        text-align: center;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        font-size: 16px;
        color: #2c3e50;
        margin-bottom: 10px;
        display: block;
    }

    .input-group input,
    .input-group select,
    .input-group textarea {
        width: 100%;
        padding: 12px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
        color: #333;
    }

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
        outline: none;
        border-color: #1abc9c;
    }

    .input-group textarea {
        resize: vertical;
    }

    .btn-submit {
        background-color: #1abc9c;
        padding: 12px 20px;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #16a085;
    }
    </style>
</head>

<body>


    <!-- Add Task Form -->
    <section class="add-task-form">
        <div class="container">
            <h2>Add New Task</h2>

            <form action="add-task.php" method="POST">
                <div class="input-group">
                    <label for="task_title">Task Title</label>
                    <input type="text" name="title" id="task_title" placeholder="Enter task title" required>
                </div>

                <div class="input-group">
                    <label for="deadline">Deadline</label>
                    <input type="datetime-local" name="due_time" id="deadline" required>
                </div>

                <div class="input-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="Personal">Personal</option>
                        <option value="Work">Work</option>
                        <option value="Study">Study</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="priority">Priority (1 - 100)</label>
                    <input type="number" name="priority" id="priority" min="1" max="100" required>
                </div>

                <button type="submit" class="btn-submit">Add Task</button>
            </form>
        </div>
    </section>

</body>

</html>