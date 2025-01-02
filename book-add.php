<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ulms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['title'])) {
    header("Location: index.php");
    exit();
}

// Get POST data
$title = $_POST['title'];
$author = $_POST['author']; 
$publisher = $_POST['publisher'];
$image = $_POST['image'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO books (title, author, publisher, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $author, $publisher, $image);

// Execute
if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>