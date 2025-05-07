<?php 
session_start();
include 'db.php';  // Ensure this points to your actual database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input data and sanitize
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $starting_balance = doubleval($_POST['starting_balance']);  // Convert input to double

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

	// Generate a random 16-digit card number
    function generateCardNumber() {
        $segments = [];
        for ($i = 0; $i < 4; $i++) {
            $segments[] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        return implode('', $segments);
    }

    $cardNumber = generateCardNumber();

// Check for existing username or email
    $checkStmt = $conn->prepare("SELECT UserID FROM Users WHERE Username = ? OR Email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Error: Username or Email already exists. Please try again with different credentials.";
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();


    // Begin transaction to ensure both user and account are created together
    $conn->begin_transaction();

    try {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Users (Username, Email, PasswordHash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);

        // Execute the statement and check for success/failure
        if ($stmt->execute()) {
            // Get the last inserted user ID
            $userId = $stmt->insert_id;

            // Insert a new account for the user with a default balance
            $stmt = $conn->prepare("INSERT INTO Accounts (UserID, CardNumber, Balance) VALUES (?,?, ?)");
            $stmt->bind_param("isd", $userId, $cardNumber, $starting_balance);
            $stmt->execute();

            $conn->commit();  // Commit the transaction
            header("Location: index.html");
        } else {
            echo "Error: " . $stmt->error;
            $conn->rollback();  // Rollback the transaction on error
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();  // Ensure any error leads to a rollback
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>
