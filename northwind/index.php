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
  <title>Northwind - Catalog</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php  include 'header.php'; ?>
  <h1 class="title">Product Categories</h1>
  <div id="categories">
<?php
$sql = 'select CategoryID, CategoryName , Picture from categories';
try {
 $result = $conn->query($sql);
 while($row = $result->fetch_assoc()) {
?>
  <div class="category" data-id="<?=$row['CategoryID']?>">
  <img src="data:image/png;base64,<?=base64_encode($row['Picture'])?>" >
  <div><?=$row['CategoryName']?></div>
  </div>

<?php
 }
}
catch(Exception $e) {
  echo "Error: {$e->getMessage()}";
}
$conn->close();
?>
  </div>
  <script>
    for(let cat of document.querySelectorAll('.category')) {
      cat.addEventListener('click', function() {
        location.href ='product.php?category='+this.dataset.id; //
      });
    }
  </script>
</body>
</html>