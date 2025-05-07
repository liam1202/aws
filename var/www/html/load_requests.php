<?php
session_start();
include 'db.php'; // Database connection file

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch pending requests
$stmt = $conn->prepare("SELECT * FROM Requests WHERE RequesteeID = ? AND Status = 'PENDING'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>Request from User ID: {$row['RequestorID']} for \${$row['Amount']}<br>";
        echo "<form action='handle_requests.php' method='post'>";
        echo "<input type='hidden' name='requestId' value='{$row['RequestID']}'>";
        echo "<input type='hidden' name='requestorId' value='{$row['RequestorID']}'>";
	echo "<input type='hidden' name='amount' value='{$row['Amount']}'>";
	echo "<input type='hidden' name='action' value='ACCEPTED'>";  // Ensure this input sends the action
        echo "<button type='submit'>Accept</button>";  // Changed to a submit type
        echo "<button type='submit' name='action' value='DECLINED'>Decline</button>";
        echo "</form></div>";
    }
} else {
    echo "No pending requests.";
}
$stmt->close();
$conn->close();
?>
