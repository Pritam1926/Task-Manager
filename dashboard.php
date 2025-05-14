<?php
$currentPage = 'dashboard';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'connection.php';
$user_id = $_SESSION['user_id'];

// Total Tasks
$total_sql = "SELECT COUNT(*) FROM tasks WHERE user_id = ?";
$stmt_total = $conn->prepare($total_sql);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$stmt_total->bind_result($total_tasks);
$stmt_total->fetch();
$stmt_total->close();

// Pending Tasks
$pending_sql = "SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Pending'";
$stmt_pending = $conn->prepare($pending_sql);
$stmt_pending->bind_param("i", $user_id);
$stmt_pending->execute();
$stmt_pending->bind_result($pending_tasks);
$stmt_pending->fetch();
$stmt_pending->close();

// Completed Tasks
$completed_sql = "SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Completed'";
$stmt_completed = $conn->prepare($completed_sql);
$stmt_completed->bind_param("i", $user_id);
$stmt_completed->execute();
$stmt_completed->bind_result($completed_tasks);
$stmt_completed->fetch();
$stmt_completed->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>User Dashboard</title>
</head>

<body>
    <?php include 'user-header.php';?>
    <main>
        <section class="welcome-section">
            <div class="container">
                <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
                <p>Let's get things done today!</p>
                <a href="add-task.php" class="btn-add-task">Add New Task</a>
            </div>
        </section>

        <!-- Summary Cards Section -->
        <section class="summary-cards">
            <div class="card-container">
                <div class="card">
                    <div class="card-icon">
                        <i class="fa fa-tasks"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Tasks</h3>
                        <p><?php echo $total_tasks; ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <i class="fa fa-clock"></i>
                    </div>
                    <div class="card-content">
                        <h3>Pending Tasks</h3>
                        <p><?php echo $pending_tasks; ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Completed Tasks</h3>
                        <p><?php echo $completed_tasks; ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pending Tasks Section -->
        <section class="pending-tasks">
            <div class="container">
                <h2>Top 10 Pending Tasks</h2>
                <?php

                $sql = "SELECT * FROM tasks WHERE user_id = ? AND status = 'Pending' ORDER BY priority ASC LIMIT 10";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0): 
                while ($row = $result->fetch_assoc()): ?>
                <div class="task-card">
                    <div class="task-info">
                        <h4><?php echo $row['title']; ?></h4>
                        <p><strong>Priority:</strong> <?php echo $row['priority']; ?></p>
                        <p><strong>Deadline:</strong> <?php echo $row['due_time']; ?></p>
                        <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                    </div>
                    <div class="task-actions">
                        <a href="complete-task.php?id=<?php echo $row['id']; ?>" class="btn-complete">Mark as
                            Complete</a>
                        <a href="delete-task.php?id=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p>No pending tasks found.</p>
                <?php endif; ?>

            </div>
        </section>

        <?php
            $stmt->close();
            $conn->close();
        ?>

    </main>


</body>

</html>