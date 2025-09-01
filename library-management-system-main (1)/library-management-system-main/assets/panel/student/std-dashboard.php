<?php
include '../../webpages/db-connect.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['std-name'])) {
    header("location: ../../webpages/student-login.php");
    exit();
}

// Set default values if session keys are missing
$user_name = isset($_SESSION['std-name']) ? $_SESSION['std-name'] : "Guest";
$user_email = isset($_SESSION['std-email']) ? $_SESSION['std-email'] : "Not Available";
$user_type = $_SESSION['user_type'] ?? "student"; // Default to 'student'

// Define issue limits
$max_issue_limit = ($user_type == 'faculty') ? 5 : 3;

// Ensure database connection is successful
if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Fetch books issued by the user
$issuequery = "SELECT COUNT(*) as issued_count FROM issuebook WHERE user_email='$user_email' AND status='Not Returned'";
$iquery = mysqli_query($con, $issuequery) or die("Issue Query Failed: " . mysqli_error($con));
$issuedData = mysqli_fetch_assoc($iquery);
$issuedBooks = $issuedData['issued_count']; // Fetch count directly

// Fetch returned books
$returnquery = "SELECT COUNT(*) as returned_count FROM issuebook WHERE user_email='$user_email' AND status='Returned'";
$returnedQuery = mysqli_query($con, $returnquery) or die("Return Query Failed: " . mysqli_error($con));
$returnedData = mysqli_fetch_assoc($returnedQuery);
$returnedBooks = $returnedData['returned_count'];

// Total books available
$bookquery = "SELECT COUNT(*) as total_books FROM book";
$query = mysqli_query($con, $bookquery) or die("Book Query Failed: " . mysqli_error($con));
$bookData = mysqli_fetch_assoc($query);
$totalBooks = $bookData['total_books'];

// Calculate remaining books that can be issued
$remainingBooks = max(0, $max_issue_limit - $issuedBooks);

// Fetch fine details for the user
// Query to fetch the relevant fine details
$fineQuery = "SELECT isbn, fine, return_date, duedate FROM issuebook WHERE user_email='$user_email' AND fine > 0 AND status='Returned'";
$fineResult = mysqli_query($con, $fineQuery) or die("Fine Query Failed: " . mysqli_error($con));

// Store fine details and update fine amounts
$fines = []; // To store fine details to display later
while ($row = mysqli_fetch_assoc($fineResult)) {
    $return_date = new DateTime($row['return_date']);
    $due_date = new DateTime($row['duedate']);  // Use 'duedate' instead of 'due_date'
    
    // Calculate the difference in days
    $interval = $return_date->diff($due_date);
    $days_diff = $interval->days;

    // If the return date is later than the due date, calculate fine
    if ($return_date > $due_date) {
        // Fine is calculated as 5 birr per day of difference
        $new_fine = $days_diff * 5;

        // Update the fine in the database
        $updateQuery = "UPDATE issuebook SET fine = $new_fine WHERE isbn = '" . $row['isbn'] . "' AND user_email = '$user_email'";
        $updateResult = mysqli_query($con, $updateQuery) or die("Update Query Failed: " . mysqli_error($con));

        // Store the fine in the $fines array for display
        $fines[] = [
            'isbn' => $row['isbn'],
            'new_fine' => $new_fine,  // Store the calculated fine
            'return_date' => $row['return_date'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Library Management System</title>
    <link rel="stylesheet" href="../css/main.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
</head>

<body onload="preloader()">
    <style>
        .home-section {
            height: 100vh;
        }
    </style>
    
    <?php include '../../loader/loader.php' ?>

    <div class="sidebar close">
        <ul class="nav-links">
            <li>
                <a href="user-dashboard.php">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="user-dashboard.php">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <a href="user-pages/view-books.php">
                    <i class='bx bx-book-alt'></i>
                    <span class="link_name">Available Books</span>
                </a>
            </li>
            <li>
                <a href="user-pages/issued-books.php">
                    <i class='bx bx-folder-open'></i>
                    <span class="link_name">Issued Books</span>
                </a>
            </li>
            <li>
                <a href="user-pages/fine-details.php">
                    <i class='bx bx-dollar'></i>
                    <span class="link_name">Fine Details</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class="fa-solid fa-bars"></i>
            <div class="logout">
                <button><a href="./logout.php">Log Out</a></button>
            </div>
        </div>
        <div class="control-panel">
            <h4>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h4>
            <h5>You are logged in as a <strong><?php echo ucfirst($user_type); ?></strong></h5>
            <div class="panel-container">
                <div class="issued-books panel">
                    <div class="data">
                        <span><?php echo $issuedBooks; ?></span>
                        <label>Issued Books</label>
                    </div>
                    <div class="icon">
                        <i class='bx bx-book'></i>
                    </div>
                </div>
                <div class="return-books panel">
                    <div class="data">
                        <span><?php echo $returnedBooks; ?></span>
                        <label>Returned Books</label>
                    </div>
                    <div class="icon">
                        <i class='bx bx-book'></i>
                    </div>
                </div>
                <div class="all-books panel">
                    <div class="data">
                        <span><?php echo $totalBooks; ?></span>
                        <label>Total Books</label>
                    </div>
                    <div class="icon">
                        <i class='bx bx-book'></i>
                    </div>
                </div>
                <div class="remaining-books panel">
                    <div class="data">
                        <span><?php echo ($remainingBooks > 0) ? $remainingBooks : 0; ?></span>
                        <label>Books You Can Still Issue</label>
                    </div>
                    <div class="icon">
                        <i class='bx bx-bookmark'></i>
                    </div>
                </div>
            </div>
            <p><strong>Note:</strong> Students can issue up to 3 books, while faculty can issue up to 5 books.</p>
        </div>

        <div class="fine-details">
            <h3>Your Fine Details</h3>
            <?php if (count($fines) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Fine (Birr)</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fines as $fine): ?>
                            <tr>
                                <td><?php echo $fine['isbn']; ?></td>
                                <td><?php echo $fine['new_fine']; ?> Birr</td>
                                <td><?php echo $fine['return_date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No fines found.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
