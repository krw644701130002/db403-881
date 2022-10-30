<?php
session_start();
include 'db_connect.php';
$domain_error = false;
if (isset($_POST['submit'])) {
  $domain = substr($_POST['email'], -10);
  $domain_error = strtolower($domain) != '@dpu.ac.th';
  if (!$domain_error) {
    $sql = "insert into registration";
    $sql .= '(fname,lname,gender,dob,email,passw) ';
    $sql .= "values(?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss',
      $_POST['fname'],
      $_POST['lname'],
      $_POST['gender'],
      $_POST['dob'],
      $_POST['email'],
      $password
    );
    $password = password_hash(
      $_POST['password'],
      PASSWORD_DEFAULT
    );
    try {
      $stmt->execute();
      $_SESSION['user'] = ['email'=>$_POST['email'], 'name'=>"{$_POST['fname']} {$_POST['lname']}"];
      header('location: index.php');
      exit();
    }
    catch (Exception $e) {
      echo "Error: {$e->getMessage()}";
    }
    $stmt->close();
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="css/style.css">
  <script>
    function validate() {
      let pass = document.querySelector('#password');
      let repass = document.querySelector('#repass');
      let correct = pass.value == repass.value;
      if (!correct) {
        alert('Password and Re-type Password are not identcal.');
      }
      return correct;
    }
  </script>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
  <h1 class="title">Registration</h1>
  <form action="register.php" onsubmit="return validate();" method="post">
    <table>
      <tr>
        <td>
          <label for="fname">First name : </label>
        </td>
        <td>
          <input type="text" name="fname" id="fname" required>
        </td>
      </tr>
      <tr>
        <td>
          <label for="lname">Last name : </label>
        </td>
        <td>
          <input type="text" name="lname" id="lname" required>
        </td>
      </tr>
      <tr>
        <td>
          Gender :
        </td>
        <td>
          <fieldset >
            <input type="radio" name="gender" id="male" value="M">
            <label for="male">Male</label><br>
            <input type="radio" name="gender" id="female" value="F">
            <label for="female">Female</label><br>
            <input type="radio" name="gender" id="others" checked value="O">
            <label for="others">Others</label>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <label for="dob">Date of birth : </label>
        </td>
        <td>
          <input type="date" name="dob" id="dob" required>
        </td>
      </tr>
      <tr>
        <td>
          <label for="email">E-mail : </label>
        </td>
        <td>
          <input type="email" name="email" id="email" required>
          <?= $domain_error ? '<h3 class="error">email must be @dpu.ac.th</h3>' : '' ?>
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
        <td>
          <label for="repass">Re-type Password : </label>
        </td>
        <td>
          <input type="password" id="repass">
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input id="action" type="submit" value="Register" name="submit">
        </td>
      </tr>
    </table>
  </form>
  <script>
<?php
if (isset($_POST['submit'])) {
?>
    document.querySelector('#fname').value = '<?= $_POST['fname'] ?>';
    document.querySelector('#lname').value = '<?= str_replace("'","\'",$_POST['lname']) ?>';
    document.querySelector('#dob').value = '<?= $_POST['dob'] ?>';
    document.querySelector('#password').value = '<?= $_POST['password'] ?>';
    document.querySelector('#email').value = '<?= $_POST['email'] ?>';
    document.querySelector('#gender[value=<?= $_POST['gender'] ?>]').checked = true;
<?php
}
?>
  </script>
</body>
</html>