<?php
// Include the database connection from the includes folder
require '../includes/db.php';

// Process the contact form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $firstName   = htmlspecialchars($_POST['first_name']);
    $lastName    = htmlspecialchars($_POST['last_name']);
    $email       = htmlspecialchars($_POST['email']);
    $phoneNumber = htmlspecialchars($_POST['phone_number']);
    $address     = htmlspecialchars($_POST['address']);
    $title       = htmlspecialchars($_POST['title']);
    $message     = htmlspecialchars($_POST['message']);

    // Prepare the SQL query to insert the contact form data
    $query = "INSERT INTO contact_form (first_name, last_name, email, phone_number, address, title, message) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phoneNumber, $address, $title, $message);

    // Execute the query and redirect upon success
    if ($stmt->execute()) {
        header("Location: contact_confirmation.php");
    } else {
        echo "Failed to send your message. Please try again later.";
    }

    $stmt->close();
}
?>
