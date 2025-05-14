<?php
$currentPage = 'pending';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connection.php';

$user_id = $_SESSION['user_id'];

// Handle filter
$category = $_GET['category'] ?? '';
$deadline = $_GET['deadline'] ?? '';

$sql = "SELECT * FROM tasks WHERE user_id = ? AND status = 'Pending'";
$params = [$user_id];
$types = "i";

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

if (!empty($deadline)) {
    $sql .= " AND due_time <= ?";
    $params[] = $deadline;
    $types .= "s";
}

$sql .= " ORDER BY priority ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pending Tasks</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .filter-section {
            max-width: 1000px;
            margin: 30px auto;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-section select,
        .filter-section input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filter-section button {
            padding: 10px 20px;
            background-color: #1abc9c;
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .filter-section button:hover {
            background-color: #16a085;
        }

        .task-container {
            max-width: 1000px;
            margin: 20px auto;
        }

        .task-card {
            background-color: #fff;
            border-left: 6px solid #1abc9c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .task-card h3 {
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .task-details {
            font-size: 15px;
            color: #555;
        }
    </style>
</head>

<body>
    <?php include 'user-header.php'; ?>

    <div class="container">
        <h2 style="text-align:center; margin: 30px 0;">Pending Tasks</h2>

        <!-- Filter Form -->
        <form method="GET" class="filter-section">
            <select name="category">
                <option value="">All Categories</option>
                <option value="Personal" <?= $category == "Personal" ? "selected" : "" ?>>Personal</option>
                <option value="Work" <?= $category == "Work" ? "selected" : "" ?>>Work</option>
                <option value="Study" <?= $category == "Study" ? "selected" : "" ?>>Study</option>
                <option value="Other" <?= $category == "Other" ? "selected" : "" ?>>Other</option>
            </select>

            <input type="date" name="deadline" value="<?= $deadline ?>">

            <button type="submit">Apply Filter</button>
        </form>

        <!-- Tasks List -->
        <div class="task-container">
            <?php
            if ($result->num_rows > 0) {
                while ($task = $result->fetch_assoc()) {
                    echo "
                    <div class='task-card'>
                        <h3>{$task['title']}</h3>
                        <div class='task-details'>
                            <p><strong>Deadline:</strong> {$task['due_time']}</p>
                            <p><strong>Category:</strong> {$task['category']}</p>
                            <p><strong>Priority:</strong> {$task['priority']}</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='text-align:center;'>No tasks found.</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>
