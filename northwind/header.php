<script src="js/sign.js"></script>
<header>
  <img src="images/northwind.png" alt="" id="logo">
  <span id="company">Northwind Traders</span>
<?php
if(isset($_SESSION['user'])) {
$user = $_SESSION['user'];
?>
  <div id="user">
    <p>
      Welcome <?=$user['name']?>
    </p>
    <button id="signout" onclick="signout()">sign out</button>
  </div>
<?php
} else {
?>
  <button id="signin" onclick="signin()">sign in</button>
<?php
}
?>
</header>
