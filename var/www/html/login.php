<?php
session_start();
include 'db.php'; // Your database connection file

// Retrieve username and password from form submission
$username = $_POST['username'];
$password = $_POST['password'];

// Your SQL and password verification logic here
$stmt = $conn->prepare("SELECT UserID, PasswordHash FROM Users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['PasswordHash'])) {
        $_SESSION['user_id'] = $user['UserID']; // Assuming you want to keep the user logged in
        header("Location: history.html"); // Redirect to search.html page upon successful login
    } else {
        echo 'Invalid password.'; // Inform user of invalid password
    }
} else {
    echo 'No user found with that username.'; // Inform user no such username exists
}
$stmt->close();
$conn->close();
?>
