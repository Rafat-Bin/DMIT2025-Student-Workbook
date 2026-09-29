<?php

function get_grade($mark) {

    if ($mark >= 80) {

        return "A";

    } elseif ($mark >= 70) {

        return "B";

    } elseif ($mark >= 60) {

        return "C";

    } elseif ($mark >= 50) {

        return "D";

    } else {

        return "F";
    }
}

$grade1 = get_grade(85);
$grade2 = get_grade(72);
$grade3 = get_grade(45);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grade</title>
</head>

<body>

    <h1>Student Grades</h1>

    <p>
        Mark: 85 — Grade: <?php echo $grade1; ?>
    </p>

    <p>
        Mark: 72 — Grade: <?php echo $grade2; ?>
    </p>

    <p>
        Mark: 45 — Grade: <?php echo $grade3; ?>
    </p>

</body>

</html>