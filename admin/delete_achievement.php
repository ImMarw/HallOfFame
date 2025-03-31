<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->query("DELETE FROM achievements WHERE id = $id");
}

header("Location: dashboard.php");
exit;
?>