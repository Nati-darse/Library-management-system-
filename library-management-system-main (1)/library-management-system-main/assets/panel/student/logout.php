<?php
session_start(); // Start the session

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page or home page
header("Location: http://localhost/library-management-system-main%20(1)/library-management-system-main/");
exit(); // Ensure script stops executing after redirection
?>
