<?php
  session_start();
  include 'db_connect.php';
    //if isset($_POST['email']);
    //echo $_POST['email'];
    //echo isset($_POST['submit']) ? $_POST['email'] : ''; // ถ้ามีข้อมูลให้โขว์ ถ้าไม่มีไม่ต้องแสดง
    $domain_error = false;
    if (isset($_POST['submit'])) {
    $domain = substr($_POST['email'], -10);
    $domain_error = strtolower ($domain) != '@dpu.ac.th';
    if (!$domain_error) {
        // $sql = "insert into registration(fname,lname,gender,dob,email,passw) ";
        // $sql .= "values('{$_POST['fname']}', '{$_POST['lname']}', '{$_POST['gender']}', '{$_POST['dob']}', '{$_POST['email']}', ";
        // $sql .= "'".password_hash($_POST['email'], PASSWORD_DEFAULT);
        // $sql .= "')";
        //echo $sql;
        $sql = 'insert into registration(fname,lname,gender,dob,email,passw) values(?,?,?,?,?,?) ';
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
            //$conn->query($sql);
            //echo 'Successful registration';
            //unset($_POST['submit']);
            $_SESSION['email'] = $_POST['email'];
            header('location: welcome.php');
            exit();
            } catch (Exception $e) {
            echo "Error: $sql<br>{$e->getMessage()}";
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
    <script>
        function validate(form) {
            let pass = document.querySelector('#password');
            let repass = document.querySelector('#repassword');
            let correct =  pass.value == repass.value;
            if (!correct) {
                alert('กรุณากรอกข้อมูลให้ตรงกัน');
            }
            return correct;
        }
    </script>
    <style>
        .error {
            color : red;
        }
    </style>
</head>
<body>
<h1> Register </h1>
    <form action="register.php" onsubmit="return validate(this);" method="post">
        <p>
            <label for="fname">First Name : </label>
            <input type="text" name="fname" id="fname" required>
        </p>
        <p>
            <label for="lname">Last Name : </label>
            <input type="text" name="lname" id="lname" required>
        </p>
        <p>
            <fieldset>
            <legend>Gender : </legend>
                <input type="radio" name="gender" id="male" value="M">
                <label for="male ">Male</label>       
                <input type="radio" name="gender" id="female" value="F">
                <label for="female ">Female</label> 
                <input type="radio" name="gender" id="other" checked value="O" >
                <label for="other ">Others</label> 
            </fieldset>
        </p>
        <p>
            <label for="dob">Date of birth : </label> 
            <input type="date" name="dob" id="dob" required>
        </p>
        <p>
            <label for="email">E-mail : </label> 
            <input type="email" name="email" id="email" required>
            <?= $domain_error ? '<h3 class="error"> email must be @dpu.ac.th </h3>' : '' ?>
        </p>
        <p>
            <label for="password">Password : </label> 
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="repassword">Re-Type Password : </label> 
            <input type="password" id="repassword" >
        </p>
        <p>
           <input type="submit" value="Register" name="submit">
        </p>
    </form>
    <script>
    <?php
        if (isset($_POST['submit'])) {
    ?>
    document.querySelector('#fname').value = '<?= $_POST['fname'] ?>';
    document.querySelector('#lname').value = '<?= $_POST['lname'] ?>';
    document.querySelector('#dob').value = '<?= $_POST['dob'] ?>';
    document.querySelector('#password').value = '<?= $_POST['password'] ?>';
    document.querySelector('#email').value = '<?= $_POST['email'] ?>';
    <?php
        }
    ?>
    </script>
    
</body>
</html>