<?php
require 'auth.php';
require 'config/db.php';

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM tasks WHERE user_id = ? AND title LIKE ? ORDER BY due_date ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id, "%$search%"]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="toggle.js" defer></script>
</head>
<body>
  <h2>Your Tasks 
    <button id="darkToggle" class="dark-toggle" title="Toggle Dark Mode">
      <i class="fas fa-moon"></i>
    </button>
  </h2>
  <form method="GET">
    <input type="text" name="search" placeholder="Search tasks..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
  </form>
  <table class="task-table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Due Date</th>
        <th>Category</th>
        <th>Priority</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tasks as $task): ?>
        <tr>
          <form action="task_actions.php" method="POST">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <td><input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>"></td>
            <td><input type="date" name="due_date" value="<?= $task['due_date'] ?>"></td>
            <td><input type="text" name="category" value="<?= htmlspecialchars($task['category']) ?>"></td>
            <td>
              <select name="priority">
                <option value="Low" <?= $task['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                <option value="Medium" <?= $task['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="High" <?= $task['priority'] == 'High' ? 'selected' : '' ?>>High</option>
              </select>
            </td>
            <td>
              <button type="submit" name="action" value="update"><i class="fas fa-pen"></i> Update</button>
              <button type="submit" name="action" value="toggle"><i class="fas fa-check"></i> Complete</button>
              <button type="submit" name="action" value="delete" onclick="return confirm('Delete task?')"><i class="fas fa-trash"></i> Delete</button>
            </td>
          </form>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Add Task Button -->
  <button id="showAddForm" style="margin-top: 20px;">
    <i class="fas fa-plus"></i> Add Task
  </button>

  <!-- Hidden Add Task Form -->
  <div id="add-task-container" style="display: none;">
    <form id="add-task-form" action="task_actions.php" method="POST">
      <input type="text" name="title" required placeholder="New Task">
      <input type="date" name="due_date" placeholder="Due Date">
      <input type="text" name="category" placeholder="Category">
      <select name="priority">
        <option value="Low">Low</option>
        <option value="Medium" selected>Medium</option>
        <option value="High">High</option>
      </select>
      <button type="submit" name="action" value="create">
        <i class="fas fa-check-circle"></i> Save Task
      </button>
    </form>
  </div>

  <a href="logout.php">Logout</a>

  <script>
    document.getElementById("showAddForm").addEventListener("click", function () {
      const form = document.getElementById("add-task-container");
      form.style.display = (form.style.display === "none") ? "block" : "none";
    });
  </script>
</body>
</html>
