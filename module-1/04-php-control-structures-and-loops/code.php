<?php

$username = 'bin2';
$firstname = 'Rafat';

if ($username == 'bin2') {
    echo "<h1>Welcome $firstname</h1>";
}

if ($username != 'admin') {
    echo '<p>You do not have admin access</p>';
}



// $lastname = 'bin';
if (isset($lastname)){
    echo "<p>Last name is set</p>";
} else {
    echo "<p>last name is not set</p>";
}

$quantity = 3;

if ($quantity > 0) {
    echo "<p>we have stock</p>";
} else {
    echo '<p>Sorry that item is out of stock</p>';
}

if ($quantity >= 10 && $quantity <= 20) {
    echo '<p>no need to reorder, we have enough stock</p>';
} elseif ($quantity > 21) {
    echo '<p>who wasted money ordering so many products</p>';
} else {
    echo '<p>Rush order needed. We don\'t have enought stock</p>';
}



$province = 'MB';

switch ($province) {
    case 'AB':
        $taxrate = 5;
        $province_name = 'Alberta';
        break;
    case 'BC':
        $taxrate = 12;
        $province_name = "British Columbia";
        break;
    case 'SK':
        $taxrate = 11;
        $province_name = "Saskatchewan";
        break;
    default:
        $taxrate = 'unknown';
        $province_name = 'tbd';
    break;
}

echo "<p>The user selected $province_name with a tax rate of $taxrate%</p>";

$number = '-50';
// $number++;

echo "<p>Number is $number</p>";

if ($number === '5') {
    echo "<p>It is equal</p>";
} else {
    echo '<p>no equal due to either different value or different data type</p>';
}

if ($number > 0 || $number < 10) {
    echo '<p>number is greater than 0 or less than 10</p>';
}

$a = 0;
while ($a <= 10) {
    echo "<p>The value of a is $a</p>";
    $a++;
}


$a = 5;
do {
    echo "<p>The value of a is $a</p>";
    $a+=2;
} while ($a <= 10);

for ($index=10; $index <= 30 ; $index+=5) { 
    echo "<p>The value of index is $index</p>";
}

?>