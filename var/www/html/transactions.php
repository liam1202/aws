<?php
session_start();
require 'db.php'; // Change to require for critical dependency

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login
    exit();
}

// Prevent browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

echo "Action received: " . $_POST['action'] . "<br>";

$user_id = $_SESSION['user_id'];
$userId = $_POST['userId'];
$amount = (double) $_POST['amount']; // Ensure amount is treated as a double
$action = $_POST['action'];



try {
    if ($action == "sendMoney") {
	$conn->autocommit(FALSE); // Start transaction
echo "Transaction started.<br>";
        // Check sender's balance
        $balanceQuery = $conn->prepare("SELECT Balance FROM Accounts WHERE UserID = ?");
        $balanceQuery->bind_param("i", $user_id);
if (!$balanceQuery->execute()) {
        echo "Balance query failed: " . $conn->error;
        exit;
    }
        $balanceResult = $balanceQuery->get_result();
        if ($balanceRow = $balanceResult->fetch_assoc()) {
            if ($balanceRow['Balance'] >= $amount) {
                // Update sender's account
                $updateSender = $conn->prepare("UPDATE Accounts SET Balance = Balance - ? WHERE UserID = ?");
                $updateSender->bind_param("di", $amount, $user_id);
                if (!$updateSender->execute()) {
                echo "Failed to update sender: " . $updateSender->error;
                $conn->rollback();
                exit;
            }

                // Update receiver's account
                $updateReceiver = $conn->prepare("UPDATE Accounts SET Balance = Balance + ? WHERE UserID = ?");
                $updateReceiver->bind_param("di", $amount, $userId);
                if (!$updateReceiver->execute()) {
                echo "Failed to update receiver: " . $updateReceiver->error;
                $conn->rollback();
                exit;
            }

                // Record the transaction
                $recordTrans = $conn->prepare("INSERT INTO Transactions (SenderID, ReceiverID, Amount) VALUES (?, ?, ?)");
                $recordTrans->bind_param("iid", $user_id, $userId, $amount);
                if (!$recordTrans->execute()) {
                echo "Failed to record transaction: " . $recordTrans->error;
                $conn->rollback();
                exit;
            }

                $conn->commit(); // Commit transaction
                header("Location: history.html");
            } else {
                echo "Insufficient funds.";
                $conn->rollback(); // Rollback transaction
            }
        } else {
            echo "Failed to retrieve balance.";
            $conn->rollback();
        }
    } elseif ($action == "requestMoney") {
        $insertRequest = $conn->prepare("INSERT INTO Requests (RequestorID, RequesteeID, Amount, Status) VALUES (?, ?, ?, 'PENDING')");
        $insertRequest->bind_param("iid", $user_id, $userId, $amount);
        $insertRequest->execute();
        $conn->commit();
        header("Location: history.html");
    }
} catch (Exception $e) {
    $conn->rollback(); // Ensure rollback on error
    echo "Transaction failed: " . $e->getMessage();
}

$conn->close();
?>
