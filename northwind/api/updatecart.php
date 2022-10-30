<?php
 session_start();
 include '../db_connect.php';                // .. คือการที่ up file ไปอยู่ในโฟลเดอร์
 $conn->begin_transaction();                 //begin_transaction คือการที่ยังไม่ได้บันทึกลงไปใน DB จริงๆ
 try{
    if(!isset($_SESSION['user']['cartID'])) {
       $sql = "INSERT into cart(email) values('{$_SESSION['user']['email']}')";
       $conn->query($sql);
       $_SESSION['user']['cartID'] = $conn->insert_id;               // insert_id จะนำตัวล่าสุดมาใช้
    }
    $sql = 'INSERT into cart_details(cartID, ProductID, Units) values (?, ?, 1) on duplicate key update Units=Units+1';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii',$_SESSION['user']['cartID'], $_GET['ProductID']);
    $stmt->execute();
    $conn->commit();                         // ไม่ Error ให้มีการ Commit
 }
catch(Exception $e) {
    ECHO "Error: {$e->getMessage()}";
    $conn->rollback();                       // ถ้า Error ให้ทำการ Rollback
 }
 $conn->close();
 header("location: ../product.php?category={$_GET['category']}");
?>