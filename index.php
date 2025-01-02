<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ULMS</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <p>Useless Library Management System</p>
    </header>
    <main>
        <div class="book-adder">
            <form class="book-form" action="index.php" method="POST">
                <input type="text" name="title" placeholder="Book Title" required>
                <input type="text" name="author" placeholder="Author Name" required>
                <input type="text" name="publisher" placeholder="Publisher Name" required>
                <input type="text" name="image" placeholder="Image URL" required>
                <input type="text" name="isbn" placeholder="ISBN" required>
                <button type="submit" class="book-add">Add Book</button>
            </form>
        </div>
        <div class="book-list">
            <?php
            
            $servername = "localhost";
            $username = "ulms-user";
            $password = "ulms-user-password";
            $dbname = "ulms";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            // Check connection
            if ($conn->connect_error) {
                echo("Connection failed: " . $conn->connect_error);
            } 
            
            // Add book
            if (isset($_POST['title'])) {
                $title = $_POST['title'];
                $author = $_POST['author']; 
                $publisher = $_POST['publisher'];
                $image = $_POST['image'];
                $isbn = $_POST['isbn'];

                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO books (title, author, publisher, image, isbn) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $author, $publisher, $image, $isbn);
            
                $stmt->execute();
            }

            // Delete book
            else if (isset($_POST['delete'])) {
                $isbn = $_POST['isbn'];
                $stmt = $conn->prepare("DELETE FROM books WHERE isbn = ?");
                $stmt->bind_param("s", $isbn);
                $stmt->execute();
            }

            // Get books
            $stmt = $conn->prepare("SELECT * FROM books");
            $stmt->execute();
            $result = $stmt->get_result();
            $books = $result->fetch_all(MYSQLI_ASSOC);
        
            // Display books
            foreach ($books as $book) {
                echo "<div class='book'>";
                echo "<img src='" . $book['image'] . "' alt='book cover'>";
                echo "<div class='book-info'>";
                echo "<p class='title'>" . $book['title'] . "</p>";
                echo "<p class='author'>" . $book['author'] . "</p>";
                echo "<p class='publisher'>Published by " . $book['publisher'] . "</p>";
                echo "<p class='isbn'>ISBN " . $book['isbn'] . "</p>";
                echo "<spacer></spacer>";
                echo "<form action='index.php' method='POST'>";
                echo "<input type='hidden' name='delete' value='1'>";
                echo "<input type='hidden' name='isbn' value='" . $book['isbn'] . "'>";
                echo "<button type='submit' class='book-delete'>Delete</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }   
            
            $stmt->close();
            $conn->close();

            ?>
        </div>
    </main>
</body>

</html>