<?php

function calculate_total($price, $quantity) {

    $total = $price * $quantity;

    if ($total >= 100) {

        $discount = $total * 0.10;

        $total = $total - $discount;
    }

    return $total;
}

$total1 = calculate_total(25, 2);
$total2 = calculate_total(50, 3);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Final Price</title>
</head>

<body>

    <h1>Calculate Final Price</h1>

    <p>
        Total 1: $<?php echo $total1; ?>
    </p>

    <p>
        Total 2: $<?php echo $total2; ?>
    </p>

</body>

</html>