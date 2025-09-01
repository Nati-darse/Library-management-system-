<?php
session_start(); // Start session for user login

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "librarydb");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle the book issue when the user clicks the button
if (isset($_POST['issueBook'])) {
    if (isset($_SESSION['stdloggedin'])) {
        // Get user details from session
        $user_email = $_SESSION['user_email'];  // Assuming user's email is stored in the session
        $name = $_SESSION['user_name'];         // Assuming user's name is stored in the session
        $user_type = "student";                 // Assuming the user is a student (this can be adjusted)

        // Fetch max book limit from the session or database (if necessary)
        $max_books_limit = 3; // Assuming max limit is 3 books for students. Change as needed.

        // Fetch book data (isbn and title should come from the user's request or database)
        $isbn = "9780470583609"; // Example ISBN
        $title = "Web Design with HTML and CSS"; // Example title
        
        // Set current date as the issued date
        $issue_date = date("m/d/Y");
        
        // Calculate the return date (5 days after the issue date)
        $return_date = date('m/d/Y', strtotime('+5 days', strtotime($issue_date)));

        // Set due date (optional, based on your logic)
        $due_date = date('m/d/Y', strtotime('+7 days', strtotime($issue_date)));

        // Prepare the SQL query to insert the issued book record into the database
        $insertIssue = "INSERT INTO issuebook (isbn, title, user_email, name, issuedate, duedate, status, return_date, user_type, max_books_limit, fine) 
                        VALUES ('$isbn', '$title', '$user_email', '$name', '$issue_date', '$due_date', 'Not Returned', '$return_date', '$user_type', '$max_books_limit', 0)";

        // Execute the query
        $insertResult = mysqli_query($con, $insertIssue);

        // Check if the query was successful
        if ($insertResult) {
            echo "<script>alert('Book issued successfully!');</script>";
        } else {
            echo "<script>alert('Failed to issue the book. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('You need to log in to issue a book.');</script>";
    }
}

// Handle book return and calculate fine if overdue
if (isset($_POST['returnBook'])) {
    $isbn = $_POST['isbn']; // Get ISBN from the form
    $return_date = date("m/d/Y"); // Get current return date

    // Fetch the issue date and due date for the book from the database
    $getBookDetails = "SELECT issuedate, duedate FROM issuebook WHERE isbn = '$isbn' AND status = 'Not Returned'";
    $result = mysqli_query($con, $getBookDetails);
    
    if ($result) {
        $book = mysqli_fetch_assoc($result);
        $issuedate = $book['issuedate'];
        $duedate = $book['duedate'];

        // Calculate the fine if the book is returned after the due date
        $issuedateTimestamp = strtotime($issuedate);
        $duedateTimestamp = strtotime($duedate);
        $returnTimestamp = strtotime($return_date);

        if ($returnTimestamp > $duedateTimestamp) {
            // Calculate overdue days
            $overdue_days = ceil(($returnTimestamp - $duedateTimestamp) / (60 * 60 * 24));

            // Fine calculation (e.g., $1 per overdue day)
            $fine = $overdue_days * 1; // $1 fine per day

            // Update fine in the database
            $updateFineQuery = "UPDATE issuebook SET fine = '$fine', return_date = '$return_date', status = 'Returned' WHERE isbn = '$isbn' AND status = 'Not Returned'";
            mysqli_query($con, $updateFineQuery);

            echo "<script>alert('Book returned. Fine: $$fine');</script>";
        } else {
            // No fine, update the return date and status
            $updateReturnQuery = "UPDATE issuebook SET return_date = '$return_date', status = 'Returned' WHERE isbn = '$isbn' AND status = 'Not Returned'";
            mysqli_query($con, $updateReturnQuery);

            echo "<script>alert('Book returned without fine.');</script>";
        }
    } else {
        echo "<script>alert('Book not found or already returned.');</script>";
    }
}

mysqli_close($con); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue and Return Book</title>
</head>
<body>
    <!-- User's book issue form -->
    <form method="POST">
        <button type="submit" name="issueBook">Issue Book</button>
    </form>

    <!-- User's book return form -->
    <form method="POST">
        <input type="text" name="isbn" placeholder="Enter ISBN" required>
        <button type="submit" name="returnBook">Return Book</button>
    </form>
</body>
</html>
