<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Fundamental 1</title>
</head>
<body>
<?php
$a = 10;
$b = 2.5;
$c = 'Hello';
$d = 'World';
$words = 'apple banana orange';
$space1 = strpos($words, ' ');
$space2 = strpos($words, ' ', $space1+1);
?>
<h3>ผลการทำงานใน PHP</h3>
<pre>
$a = <?= $a ?>;
$b = <?= $b ?>; 
$c = '<?= $c ?>'; 
$d = '<?= $d ?>'; 
##########
$a + $b จะมีค่าเป็น <?= $a + $b ?> 
$c.' '.$d จะมีค่าเป็น <?= $c.'  '. $d ?> 
##########
$words คำที่ 1 คือ  <?= substr($words, 0, $space1) ?>   
$words คำที่ 2 คือ  <?= substr($words, ++$space1, $space2 - $space1) ?>  
$words คำที่ 3 คือ  <?= substr($words, ++$space2)?> 
ตัวอักษรที่สุ่มได้จาก $words คือ "<?=substr($words, rand(0, strlen($words)-1), 1) ?> "    
</body>
</html>