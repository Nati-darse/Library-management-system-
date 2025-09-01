<?php
include 'db-connect.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['student-login'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $emailquery = "SELECT * FROM users WHERE User_email = '$email'";
  $check_email = mysqli_query($con, $emailquery);
  
  // Check if query failed
  if (!$check_email) {
      die("Query Failed: " . mysqli_error($con)); // Debugging
  }
  
  $emailcount = mysqli_num_rows($check_email);
  
  if ($email == "") {
    $error['email'] = "Email should not be empty";
  }
  if ($password == "") {
    $error['pass'] = "Password should not be empty";
  } else {
    if (!isset($error)) {
      if ($emailcount > 0) {
        $fetch = mysqli_fetch_assoc($check_email);
        $fetch_code = $fetch['password'];
        if ($password === $fetch_code) {
          $_SESSION['std-name'] = isset($fetch['name']) ? $fetch['name'] : 'Unknown User';
          $_SESSION['std-email'] = isset($fetch['user_email']) ? $fetch['user_email'] : 'Not Available';
          $_SESSION['user_type'] = isset($fetch['user_type']) ? $fetch['user_type'] : 'student'; // If applicable
          $_SESSION['stdloggedin'] = true;
          
          $_SESSION['stdloggedin'] = true;
      
          header('location: ../panel/student/std-dashboard.php');
          exit();  // ✅ Important to stop further execution
      }
       else {
          $error['pass'] = 'Please Enter Valid Code';
          $_SESSION['stdloggedin'] = false;
?>
          <script>
            setTimeout(() => {
              location.replace("student-login.php");
            }, 2000)
          </script>
        <?php
        }
      } else {
        $error['email'] = 'Enter Registered Email';
        $_SESSION['stdloggedin'] = false;
        ?>
        <script>
          setTimeout(() => {
            location.replace("student-login.php");
          }, 2000)
        </script>
<?php
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System || Student Login Form</title>
  <link rel="stylesheet" href="../css/index.css">
  <!--- google font link-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

</head>

<body onload="preloader()">
  <style>
    .input-field .error {
      color: #FF3333;
      font-size: 14px;
    }
  </style>
  <?php include '../loader/loader.php' ?>

  <section class="login">
    <form class="login-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
      <h4>User Login</h4>
      <?php
      if (isset($_SESSION['code'])) {
      ?>
        <p class="error">
          <?php echo $_SESSION['code']; ?>
        </p>
      <?php
      }
      ?>

      <div class="input-form">
        <div class="input-field">
          <label for="email">Email *</label>
          <input type="email" name="email" id="email" placeholder="Your Email">
          <?php
          if (isset($error['email'])) {
          ?>
            <p class="error">
              <?php echo $error['email']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <div class="input-field">
          <label for="password">Password *</label>
          <input type="password" maxlength="6" name="password" id="password" placeholder="Password">
          <?php
          if (isset($error['pass'])) {
          ?>
            <p class="error">
              <?php echo $error['pass']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <p>Forgot Password? <a href="forgot-password.php">click here</a></p>
        <input type="submit" name="student-login" value="Login">

      </div>
    </form>
  </section>
</body>

</html>