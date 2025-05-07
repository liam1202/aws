<?php
session_start();
include 'db.php'; // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchUser = $conn->real_escape_string($_POST['searchUser']);

	$stmt ="SELECT a.UserID FROM Accounts a JOIN Users u ON a.UserID = u.UserID WHERE u.Username LIKE '" . $searchUser . "%' OR u.Email LIKE '" . $searchUser . "%'";
$result= mysqli_query($conn, $stmt);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		header("Location: transaction.html?userId=" . $row['UserID']);}
		exit();
	}


else {



header("Location: transaction.html?userId=" . "bye");}
exit();



    $stmt = $conn->prepare("SELECT a.UserID FROM Accounts a JOIN Users u ON a.UserID = u.UserID WHERE u.Username LIKE ? OR u.Email LIKE ?");

    $searchTerm = '%' . $searchUser . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
	error_log("Fetched User ID: " . $user['UserID']);  // Check server logs to see output
        // Redirect to transaction.html with userId parameter
        header("Location: transaction.html?userId=" . $user['UserID']);
        exit();
    } else {
        // No user found, redirect back to search.html with a message
        header("Location: search.html?error=nouser");
        exit();
    }
}
?>
