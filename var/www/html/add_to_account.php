<?php
session_start();
include 'db.php';  // Ensure your database connection file is correctly linked

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$amountToAdd = $_POST['amount'];

// Check if the amount is positive
if ($amountToAdd > 0) {
    $stmt = $conn->prepare("UPDATE Accounts SET Balance = Balance + ? WHERE UserID = ?");
    $stmt->bind_param("di", $amountToAdd, $user_id);
    if ($stmt->execute()) {
        header("Location: history.html");
    } else {
        echo "Error updating account: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Please enter a valid amount.";
}

$conn->close();
?>
