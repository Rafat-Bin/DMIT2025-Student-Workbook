<?php

$num1 = 10;
$num2 = 5;

$sum = $num1 + $num2;
$difference = $num1 - $num2;
$product = $num1 * $num2;
$quotient = $num1 / $num2;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Math</title>
</head>

<body>

    <h1>Basic Math</h1>

    <p>Addition: <?= $sum ?></p>
    <p>Subtraction: <?= $difference ?></p>
    <p>Multiplication: <?= $product ?></p>
    <p>Division: <?= $quotient ?></p>

    <a href="index.php">Back to Home</a>

</body>

</html>