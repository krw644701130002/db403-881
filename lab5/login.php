<?php
 session_start();
 include 'db_connect.php';
 $sql = 'select email, passw from registration where email=?';
 $stmt = $conn->prepare($sql);
 $stmt->bind_param('s', $_POST['email']);
 try {
    $stmt->execute();
    $result = $stmt->get_result();     // นำผลลัพธ์ที่ได้มาใช้งาน
    if($row = $result->fetch_assoc()) {
        if(password_verify($_POST['password'], $row['passw'])) {
            $_SESSION['email'] = $row['email'];
            header('location: welcome.php');
            exit();
        }
       }  
     }
     catch (Exception $e) {
    echo "Error: $sql<br>{$e->getMessage()}";
  }
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="login.php" method="post">
    <?= isset($_POST['submit']) ? '<p>Invalid email or password</p>' : '' ?>

    <p>
    <label for="email">E-mail : </label>
    <input type="email" name="email" id="email" required>
    </p>
    <p>
    <label for="password">Password : </label>
    <input type="password" name="password" id="password" required>
    </p>
    <p>
    <input type="submit" value="Login" name="submit">
    </p>
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