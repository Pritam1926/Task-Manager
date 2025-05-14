<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Header</title>
    <link rel="stylesheet" href="user-style.css" />
</head>

<body>

    <header>
        <div class="navbar">
            <div class="logo">TaskMaster</div>
            <div class="nav-buttons">
                <a href="dashboard.php" class="<?= ($currentPage == 'dashboard') ? 'active' : '' ?>">Dashboard</a>
                <a href="pending-task.php" class="<?= ($currentPage == 'pending') ? 'active' : '' ?>">Pending Tasks</a>
                <a href="completed-task.php" class="<?= ($currentPage == 'completed') ? 'active' : '' ?>">Completed
                    Tasks</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>


</body>

</html>