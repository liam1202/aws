<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$requestId = $_POST['requestId'];
$action = $_POST['action'];
$requestorId = $_POST['requestorId']; // Who made the request
$amount = (float) $_POST['amount'];   // Amount requested
$requesteeId = $_SESSION['user_id'];  // The logged-in user (handling the request)


if ($action == 'ACCEPTED') {
    // Step 1: Check current user (requestee) balance
    $balanceStmt = $conn->prepare("SELECT Balance FROM Accounts WHERE UserID = ?");
    $balanceStmt->bind_param("i", $requesteeId);
    $balanceStmt->execute();
    $balanceResult = $balanceStmt->get_result();

    if ($row = $balanceResult->fetch_assoc()) {
        $balance = $row['Balance'];

        if ($balance >= $amount) {
            // Enough funds — update status to ACCEPTED
            $stmt = $conn->prepare("UPDATE Requests SET Status='ACCEPTED' WHERE RequestID=?");
            $stmt->bind_param("i", $requestId);
            if (!$stmt->execute()) {
                echo "Error updating status: " . $stmt->error;
                exit();
            }

            // Redirect to transaction
            $_SESSION['redirect_to_transaction'] = true;
            $_SESSION['transaction_user_id'] = $requestorId;
            header("Location: transaction.html?userId=$requestorId&amount=$amount");
            exit();
        } else {
            // Insufficient funds — keep as PENDING
            header("Location: pending.html?error=insufficient");
            exit();
        }
    }
} elseif ($action == 'DECLINED') {
    // Update the request status to DECLINED
    $stmt = $conn->prepare("UPDATE Requests SET Status='DECLINED' WHERE RequestID=?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();

    header('Location: pending.html'); // Redirect back to the requests page
    exit();
}

$conn->close();
?>
