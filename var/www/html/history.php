<?php
session_start();
include 'db.php'; // Make sure this is the path to your database connection script

if (!isset($_SESSION['user_id'])) {
    echo 'Not logged in.';
    exit();
}

// Prevent browser from caching the page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


$user_id = $_SESSION['user_id'];

// Fetch username and balance
$userStmt = $conn->prepare("SELECT Username FROM Users WHERE UserID = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($userRow = $userResult->fetch_assoc()) {
    echo "<h2>Welcome, <strong>{$userRow['Username']}</strong>!</h2>";
}


// Fetch user's balance
$stmt = $conn->prepare("SELECT Balance FROM Accounts WHERE UserID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$balanceResult = $stmt->get_result();
if ($balanceRow = $balanceResult->fetch_assoc()) {
echo "<h2>Your current balance: \${$balanceRow['Balance']}</h2>";
    $balance = $balanceRow['Balance'];
} else {
    $balance = "Balance not available";
}

// Fetch transactions with associated usernames
$stmt = $conn->prepare("
    SELECT t.TransactionID, t.Amount, t.TransactionDate,
           s.Username AS SenderUsername, r.Username AS ReceiverUsername
    FROM Transactions t
    JOIN Users s ON t.SenderID = s.UserID
JOIN Users r ON t.ReceiverID = r.UserID
    WHERE t.SenderID = ? OR t.ReceiverID = ?
        ORDER BY t.TransactionDate DESC
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$counter = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date("m/d/Y", strtotime($row['TransactionDate']));
        $groupIndex = floor($counter / 10);  // Every 10 transactions, start a new group

        echo "<div class='transaction-group group-{$groupIndex}' style='display: " . ($groupIndex === 0 ? 'block' : 'none') . ";'>
                <p>
                    Transaction ID: {$row['TransactionID']}<br>
                    Sender: {$row['SenderUsername']}<br>
                    Receiver: {$row['ReceiverUsername']}<br>
                    Amount: \${$row['Amount']}<br>
                    Date: {$formattedDate}
                </p>
              </div>";
        $counter++;
    }

    $totalGroups = ceil($counter / 10);
    echo "<script>
    totalGroups = {$totalGroups};
    updateGroupDisplay(); // ensure the display is updated after assigning
</script>";
} else {
    echo "<p>No transactions found.</p>";
}


$stmt->close();
$conn->close();
?>
