<?php
require 'auth.php';
require 'config/db.php';

$action = $_POST['action'];
$user_id = $_SESSION['user_id'];

if ($action === 'create') {
    $title = $_POST['title'];
    $due_date = $_POST['due_date'] ?? NULL;
    $category = $_POST['category'] ?? NULL;
    $priority = $_POST['priority'] ?? 'Medium';
    $stmt = $pdo->prepare("INSERT INTO tasks (title, user_id, completed, due_date, category, priority) VALUES (?, ?, 0, ?, ?, ?)");
    $stmt->execute([$title, $user_id, $due_date, $category, $priority]);
} elseif ($action === 'update') {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $due_date = $_POST['due_date'] ?? NULL;
    $category = $_POST['category'] ?? NULL;
    $priority = $_POST['priority'] ?? 'Medium';
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, due_date = ?, category = ?, priority = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $due_date, $category, $priority, $task_id, $user_id]);
} elseif ($action === 'toggle') {
    $task_id = $_POST['task_id'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = NOT completed WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
} elseif ($action === 'delete') {
    $task_id = $_POST['task_id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
}

header('Location: dashboard.php');
exit();
