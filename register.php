<?php
//connection info
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS,
$DATABASE_NAME);
// If there is an error with the connection, terminate scrip and display error
if (mysqli_connect_errno()) {
  die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check if user entered data
if (!isset($_POST['username'], $POST['password'], $_POST['email'])) {
  // Unable to retrieve data
  die ('Please complete the registration form.');
}
// Check for empty values
if (empty($_POST['username']) || empty($_POST['password']) ||
empty($_POST['email'])) {
  // One or more empty values detected
  die ('Please complete the registration form!');
}

// Check if account with username already exists
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
  // Bind parameters and hash password
  $stmt->bind_param('s', $_POST['username']);
  $stmt->execute();
  $stmt->store_result();
  // Result stored, check if account exists in database
  if ($stmt->num_rows > 0) {
    // Username already exists
    echo 'Username taken, please create a different username.';
  } else {
    // Username doesn't exist, insert new account
    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
      // Hash password
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt->bind_param(' sss', $_POST['username'], $password, $_POST['email']);
      $stmt->execute();
      echo 'You have successfully registered, you can now login!';
    } else {
      // Errors in sql statement, check if account tables exist for all fields
      echo 'Could not prepare statement';
    }
  }
  $stmt->close();
} else {
  // Errors in sql statement, check if account tables exist for all fields
  echo 'Could not prepare statement';
}
$con->close();
?>
