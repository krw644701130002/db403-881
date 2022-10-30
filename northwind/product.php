<?php
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Northwind - Products</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php'; 
$sql = 'select CategoryName from categories where CategoryID=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_GET['category']); // i คือตัวเลข
try {
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
}
catch(Exception $e) {
  echo "Error: {$e->getMessage()}";
}
$stmt->close();
?>
  <h1 class="title">Product of <?=$row['CategoryName']?></h1>
  <div id="categories">
    <table id="product">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Unit Price</th>
        </tr>
      </thead>
      <tbody>
    <?php
    $sql = 'SELECT ProductID, ProductName , UnitPrice FROM products WHERE CategoryID=? AND Discontinued=0';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['category']);
    try {
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
     ?>
        <tr class="product" data-id="<?=$row['ProductID']?>">
        <td><?=$row['ProductName']?></td>
        <td><?=$row['UnitPrice']?></td>     
      </tr>
     <?php 
      }

    }
      catch(Exception $e)     {
        echo "Error: {$e->getMessage()}";
      }
    ?>
      </tbody>
    </table>
    <div id="summary">
    <?php
    if(isset($_SESSION['user'])) {
      $sql = "SELECT cartID, slip from cart where email='{$_SESSION['user']['email']}' ORDER BY created DESC limit 1";
      try{
        $result = $conn->query($sql);
        $row = $result->fetch_array();
        if($row) {
          if($row[1]) {
          echo '<h1>Slip</h1>';
          echo '<img src="images/slips/'.$row[1].'">';
        }
        else {
        $_SESSION['user']['cartID'] = $row[0];
        }
      }
    } 
      catch(Exception $e) {

      }
    }  
    ?>
      <h1 class="title">Cart</h1>
      <table id="cart">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Units</th>
          </tr>
        </thead>
        <tbody id="cart_details">
      <?php
      if(isset($_SESSION['user']) && isset($_SESSION['user']['cartID'])) {
      $sql = "SELECT ProductName, Units from cart_details c join products p on c.ProductID=p.ProductID WHERE cartID=".$_SESSION['user']['cartID'];
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc()) {
        ?>
              <tr>
                <td><?=$row['ProductName']?></td>
                <td><?=$row['Units']?></td>
             </tr>
        <?php
        }
      }
        ?>
        </tbody>
      </table>

    </div>
    <form action="api/payment.php" method="post" enctype="multipart/form-data">
      Select image to upload:
      <input type="file" name="slip">
      <input type="submit" value="Upload Image" name="submit">
    </form>
    <script> 
    <?php
    if(isset($_SESSION['user'])) {
     ?> 
    let cart_details = document.querySelector('#cart_details');
    for(let prod of document.querySelectorAll('.product')) {
      prod.addEventListener('click', function() {
        location.href = 'api/updatecart.php?category=<?=$_GET['category']?>&ProductID='+this.dataset.id;
      });
    }
    <?php
    }
    ?>
  </script>
</body>
</html>