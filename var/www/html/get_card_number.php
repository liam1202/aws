<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT CardNumber FROM Accounts WHERE UserID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'cardNumber' => $row['CardNumber']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Card not found']);
}

$stmt->close();
$conn->close();
?>
