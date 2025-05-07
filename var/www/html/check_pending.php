<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['hasPending' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM Requests WHERE RequesteeID = ? AND Status = 'PENDING'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['hasPending' => $row['total'] > 0]);

$stmt->close();
$conn->close();
?>
