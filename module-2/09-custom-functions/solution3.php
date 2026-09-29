<?php

function calculate_shipping($weight, $rate = 2) {

    $shipping = $weight * $rate;

    if ($weight >= 10) {

        $shipping = $shipping + 5;
    }

    return $shipping;
}

$shipping1 = calculate_shipping(4);
$shipping2 = calculate_shipping(12, 3);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Calculator</title>
</head>

<body>

    <h1>Shipping Calculator</h1>

    <p>
        Shipping 1: $<?php echo $shipping1; ?>
    </p>

    <p>
        Shipping 2: $<?php echo $shipping2; ?>
    </p>

</body>

</html>