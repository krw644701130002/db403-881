<?php
session_start();
include 'db_connect.php';
if (isset($_POST['submit'])) {
  $sql = 'select email, fname, lname, passw from registration where email=?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $_POST['email']);
  try {
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
      if(password_verify($_POST['password'], $row['passw'])) {
        $_SESSION['user'] = ['email'=>$row['email'], 'name'=>"{$row['fname']} {$row['lname']}"];
        header('location: index.php');
        exit();
      }
    }
  }
  catch (Exception $e) {
    echo "Error: $sql<br>{$e->getMessage()}";
  }
  $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .error {
      color: red;
    }
    #signin {
      display: none;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
  <h1 class="title">Sign in</h1>
  <form action="signin.php" method="post">
    <?= isset($_POST['submit']) ? '<p class="error">Invalid email or password</p>' : '' ?>
    <table>
      <tr>
        <td>
          <label for="email">E-mail : </label>
        </td>
        <td>
          <input type="email" name="email" id="email" required>
        </td>
      </tr>
      <tr>
        <td>
          <label for="password">Password : </label>
        </td>
        <td>
          <input type="password" name="password" id="password" required>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input id="action" type="submit" value="sign in" name="submit">
        </td>
      </tr>
    </table>
    <div id="summary">
      <a href="register.php">New user please Register.</a>
    </div>
  </form>
  <script>
<?php
if(isset($_POST['submit'])) {
?>
    document.querySelector('#email').value = '<?= $_POST['email'] ?>';
    document.querySelector('#password').value = '<?= $_POST['password'] ?>';
<?php
}
?>
  </script>
</body>
</html>