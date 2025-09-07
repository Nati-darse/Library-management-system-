-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 12:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `librarydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(255) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `author` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `pdf` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `isbn`, `author`, `title`, `category`, `publisher`, `price`, `quantity`, `description`, `cover`, `pdf`) VALUES
(1, '9781118531648', 'Jack Moore & Jon Duckett', 'JavaScript and Jquery', 'Frontend', 'Wiley', '2600', '4', 'Learn JavaScript and jQuery a nicer way This full-color book adopts a visual approach to teaching JavaScript And jQuery, showing you how to make web pages more interactive and interfaces more intuitive through the use of inspiring code examples, infograph', 'book-4.jpg', 'JavaScript and JQuery - Interactive Front-End Web Development.1118531647.pdf'),
(2, '1430259833', 'Vishal Layka', 'Learn Java for Web Development', 'Programming', 'Apress', '4000', '5', 'The book concludes by exploring the web application that you\'ve built and examining industry best practices and how these might fit with your application, as well as covering alternative Java Web frameworks like Groovy/Grails and Scala/Play 2. You also ca', 'book-1.jpg', '[JAVA][Learn Java for Web Development].pdf'),
(3, '9388991214', 'John Shovic & Alan Simpson', 'Python All-in-One for Dummies', 'Basic Programming', 'Wiley', '762', '4', 'Python All-in-One for Dummies offers a starting point for those new to coding by explaining the basics of Python. Experienced coders looking for more than the basics can also find how Python can be applied to projects in the enterprise, including data ana', 'book-3.jpg', 'Python All-In-One for Dummies.pdf'),
(4, '9780470583609', ' AGI Creative Team , Jennifer Smith & Jeremy Osborn', 'Web Design with HTML and CSS', 'Web Design', 'John Wiley And Sons', '4200', '2', 'An invaluable full–color training package for Web design\r\nWeb design consists of using multiple software tools and codes–such as Dreamweaver, Flash, Silverlight, Illustrator, Photoshop, HTML, and CSS, among others–to craft a unique, robust, and interactiv', 'book-2.jpg', 'web-design-with-html-and-css-digital-classroom-booksfree.org_.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `book_request`
--

CREATE TABLE `book_request` (
  `id` int(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `std_id` int(255) NOT NULL,
  `std_name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `catid` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`catid`, `category`) VALUES
(1, 'Web Development'),
(2, 'Backend');

-- --------------------------------------------------------

--
-- Table structure for table `contacttable`
--

CREATE TABLE `contacttable` (
  `id` int(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `message` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issuebook`
--

CREATE TABLE `issuebook` (
  `isbn` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `issuedate` varchar(100) NOT NULL,
  `duedate` varchar(100) NOT NULL,
  `status` varchar(255) NOT NULL,
  `return_date` varchar(100) NOT NULL,
  `user_type` enum('student','faculty') NOT NULL DEFAULT 'student',
  `max_books_limit` int(11) NOT NULL DEFAULT 3,
  `fine` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuebook`
--

INSERT INTO `issuebook` (`isbn`, `title`, `user_email`, `name`, `issuedate`, `duedate`, `status`, `return_date`, `user_type`, `max_books_limit`, `fine`) VALUES
('9780470583609', 'Web Design with HTML and CSS', 'belachew@gmail.com', 'belachew', '1/28/2025', '10/28/2025', 'student', '5/28/2025', 'student', 3, 0.00),
('	9780470583609', 'Web Design with HTML and CSS', 'belachew@gmail.com', 'belachew', '1/28/2025', '1/30/2025', 'Returned', '1/29/2025', 'student', 3, 0.00),
('9388991214', 'Python All-in-One for Dummies', 'group5@gmail.com', 'group5', '1/28/2025', '1/30/2025', 'Returned', '2025-03-31', 'faculty', 3, 0.00),
('1430259833', 'Learn Java for Web Development', '', '', '02/28/2025', '03/07/2025', 'Returned', '02/28/2025', 'student', 3, 0.00),
('9781118531648', 'JavaScript and Jquery', 'group5@gmail.com', '', '02/28/2025', '03/07/2025', 'Not Returned', '', 'student', 3, 0.00),
('9780470583609', 'Web Design with HTML and CSS', 'belachew@gmail.com', 'belachew', '02/28/2025', '03/07/2025', 'Not Returned', '', 'student', 3, 0.00),
('9780470583609', 'Web Design with HTML and CSS', '', '', '02/28/2025', '03/07/2025', 'Not Returned', '03/05/2025', 'student', 3, 0.00),
('9388991214', 'Python All-in-One for Dummies', 'belachew@gmail.com', 'belachew', '2/25/2025', '2/28/2025', 'Returned', '3/1/2025', 'student', 3, 0.00),
('9780470583609', 'Web Design with HTML and CSS', 'group5@gmail.com', 'group5', '2/22/2025', '2/27/2025', 'Returned', '3/1/2025', 'faculty', 5, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `id` int(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cpassword` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`id`, `name`, `email`, `password`, `cpassword`, `mobile`) VALUES
(1001, 'admin', 'librarymanagementwebsite@gmail.com', 'librarian', 'librarian', '8377746663');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `std_img` varchar(350) NOT NULL,
  `code` varchar(6) NOT NULL,
  `admission_date` date NOT NULL,
  `id_card` varchar(100) NOT NULL,
  `dob` varchar(100) NOT NULL,
  `user_type` enum('student','faculty') NOT NULL DEFAULT 'student',
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_email`, `course`, `year`, `mobile`, `address`, `city`, `state`, `zipcode`, `role`, `std_img`, `code`, `admission_date`, `id_card`, `dob`, `user_type`, `password`) VALUES
(42, 'belachew', 'belachew@gmail.com', 'computer science', '2000', '093423413', 'gondar', 'gonadar', 'ethiopia', '76894', 'student', '', '', '0000-00-00', '', '', 'student', '123456'),
(111, 'Group5', 'group5@gmail.com', 'basic programming ', '2025', '0987654321', 'Fassil', 'Gondar', 'Ethiopia', '1000', 'Faculty', '', '', '0000-00-00', '', '', 'student', '000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_request`
--
ALTER TABLE `book_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `contacttable`
--
ALTER TABLE `contacttable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `book_request`
--
ALTER TABLE `book_request`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacttable`
--
ALTER TABLE `contacttable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
