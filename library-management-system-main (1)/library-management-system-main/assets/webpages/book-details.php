<?php
session_start();
error_reporting(0);

include 'db-connect.php';
$id = $_GET['id'];
$fetchbook = "SELECT * FROM book WHERE id='$id'";
$result = mysqli_query($con, $fetchbook);
$bookrow = mysqli_num_rows($result);
$bookdata = mysqli_fetch_assoc($result);

// Handle the book issue when the user clicks the button
if (isset($_POST['issueBook'])) {
    if (isset($_SESSION['stdloggedin'])) {
        // Get the user information
// Set default values if session keys are missing
$name = isset($_SESSION['std-name']) ? $_SESSION['std-name'] : "Guest";
$user_email = isset($_SESSION['std-email']) ? $_SESSION['std-email'] : "Not Available";
$user_type = $_SESSION['user_type'] ?? "student"; // Default to 'student

        // Fetch max book limit from the session or database (if necessary)
        $max_books_limit = 3; // Assuming max limit is 3 books for students. Change as needed.
        
        // Set current date and due date (assuming 7 days from today as due date)
        $issue_date = date("m/d/Y");
        $due_date = date('m/d/Y', strtotime('+7 days', strtotime($issue_date)));
        
        // Prepare and execute insert query to issue the book
        $insertIssue = "INSERT INTO issuebook (isbn, title, user_email, name, issuedate, duedate, status, return_date, user_type, max_books_limit) 
                        VALUES ('" . $bookdata['isbn'] . "', '" . $bookdata['title'] . "', '$user_email', '$name', '$issue_date', '$due_date', 'Not Returned', '$return_date', '$user_type', '$max_books_limit')";

        $insertResult = mysqli_query($con, $insertIssue);

        if ($insertResult) {
            echo "<script>alert('Book issued successfully!');</script>";
            // Optionally, you can redirect the user to another page after issuing the book
            // header("Location: user-dashboard.php"); // Redirect to dashboard or other page
        } else {
            echo "<script>alert('Failed to issue the book. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('You need to log in to issue a book.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System || Book Details</title>
    <link rel="stylesheet" href="../css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--- google font link-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
</head>

<body onload="preloader()">
    <?php include '../loader/loader.php' ?>
    <header>
        <nav class="navbar">
            <div class="logo">
                <div class="icon">
                    <img src="../images/lib.png" alt="Management System Logo">
                </div>
                <div class="logo-details">
                    <h5>L.M.S</h5>
                </div>
            </div>
            <ul class="nav-list">
                <div class="logo">
                    <div class="title">
                        <div class="icon">
                            <img src="../images/lib.png" alt="Management System Logo">
                        </div>
                        <div class="logo-header">
                            <h4>L.M.S</h4>
                            <small>Library System</small>
                        </div>
                    </div>
                    <button class="close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <li><a href="">Home</a></li>
                <li><a href="#book">Books</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">contact</a></li>
                <div class="login">
                    <?php
                    if (isset($_SESSION['loggedin'])) {
                    ?>
                        <a href="../panel/admin/dashboard.php" type="button" class="loginbtn">Dashboard</a>
                    <?php
                    } else if (isset($_SESSION['stdloggedin'])) {
                    ?>
                        <a href="../panel/student/std-dashboard.php">Dashboard</a>

                    <?php
                    } else {
                    ?>
                        <a href="login-type.php" type="button" class="loginbtn">Log In</a>
                    <?php
                    }
                    ?>
                </div>
            </ul>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </nav>
    </header>

    <section class="book-overview">
        <div class="img">
            <img src="../panel/img-store/book-images/<?php echo $bookdata['cover'] ?>" alt="" />
        </div>
        <div class="book-content">
            <h4><?php echo $bookdata['title'] ?></h4>
            <p>
                <?php echo $bookdata['description'] ?>
            </p>

            <div class="footer">
                <div class="author-detail">
                    <div class="author">
                        <small>Author</small>
                        <strong><?php echo $bookdata['author'] ?></strong>
                    </div>
                    <div class="publisher">
                        <small>Publisher</small>
                        <strong><?php echo $bookdata['publisher'] ?></strong>
                    </div>
                </div>
                <div class="badge">
                    <?php
                    if ($bookdata['quantity'] > 0) {
                        echo '<span style="background-color: #dbf5e5;
                        color: #56c156;">Available</span>';
                    } else {
                        echo '<span style="background-color: #FF8989;
                        color: #D71313;">Not Available</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="book-price">
                <div class="input-group">
                    <form method="POST" action="book-details.php?id=<?php echo $bookdata['id'] ?>">
                        <button class="cartbtn" type="submit" name="issueBook">Issue Book</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bookdata-recentbook">
        <div class="main">
            <table>
                <tr>
                    <th>Title</th>
                    <td><?php echo $bookdata['title'] ?></td>
                </tr>
                <tr>
                    <th>Author</th>
                    <td><?php echo $bookdata['author'] ?></td>
                </tr>
                <tr>
                    <th>Publisher</th>
                    <td><?php echo $bookdata['publisher'] ?></td>
                </tr>
                <tr>
                    <th>Language</th>
                    <td>English</td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td><?php echo $bookdata['isbn'] ?></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?php echo $bookdata['category'] ?></td>
                </tr>
            </table>
            <div class="recent-book">
                <h4>Recent Book</h4>
                <div class="book-container">
                    <?php
                    $book = "SELECT * FROM book WHERE NOT id='$id' ORDER BY id DESC LIMIT 4";
                    $bookresult = mysqli_query($con, $book);
                    if (mysqli_num_rows($bookresult) > 0) {
                        while ($row = mysqli_fetch_assoc($bookresult)) {
                    ?>
                            <div class="book">
                                <div class="img">
                                    <img src="../panel/img-store/book-images/<?php echo $row['cover'] ?>" alt="Book Cover Image">
                                </div>
                                <div class="content">
                                    <h5><?php echo $row['title'] ?></h5>
                                    <div class="badge">
                                        <span><?php echo mb_strimwidth($row['author'], 0, 30, '...'); ?></span>
                                    </div>

                                    <div class="btn">
                                        <button><a href="book-details.php?id=<?php echo $row['id'] ?>" style="text-decoration: none;color:#fff;">Issue Book</a></button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
    </section>

    <footer>
        <div class="container">
            <div class="logo-description">
                <div class="logo">
                    <div class="img">
                        <i class='bx bx-book-reader'></i>
                    </div>
                    <div class="title">
                        <h4>L.M.S</h4>
                    </div>
                </div>
                <div class="logo-body">
                    <p>
                        Library Management System is carefully developed for easy management of any type of library. It’s actually a virtual version of a real library.
                    </p>
                </div>
                <div class="social-links">
                    <h4>Follow Us</h4>
                    <ul class="links">
                        <li>
                            <a href=""><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-youtube"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script>
        let hamburgerbtn = document.querySelector(".hamburger");
        let nav_list = document.querySelector(".nav-list");
        let closebtn = document.querySelector(".close");
        hamburgerbtn.addEventListener("click", () => {
            nav_list.classList.add("active");
        });
        closebtn.addEventListener("click", () => {
            nav_list.classList.remove("active");
        });
    </script>
</body>

</html>
