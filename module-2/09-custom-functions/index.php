<?php

function can_you_enter($age, $province) {

    switch ($province) {

        case "AB":
        case "MB":
        case "QC":
            $age_of_majority = 18;
            break;

        default:
            $age_of_majority = 19;
            break;
    }


    if ($age >= $age_of_majority) {

        return "You can enter.";

    } else {

        $years_left = $age_of_majority - $age;

        return "You cannot enter. You need to wait {$years_left} more year(s).";
    }
}


if (isset($_GET["submit"])) {

    $age = $_GET["age"];
    $province = $_GET["province"];

    $result = can_you_enter($age, $province);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Can You Enter?</title>
</head>

<body>

    <h1>Can You Enter?</h1>

    <form method="GET">

        <label for="age">
            Age:
        </label>

        <input
            type="number"
            id="age"
            name="age"
            min="0"
            required
        >


        <label for="province">
            Province:
        </label>

        <select
            id="province"
            name="province"
            required
        >

            <option value="">
                Choose a Province
            </option>

            <option value="AB">
                Alberta
            </option>

            <option value="BC">
                British Columbia
            </option>

            <option value="MB">
                Manitoba
            </option>

            <option value="ON">
                Ontario
            </option>

            <option value="QC">
                Quebec
            </option>

        </select>


        <button
            type="submit"
            name="submit"
        >
            Can I Enter?
        </button>

    </form>


    <?php if (isset($result)) : ?>

        <h2>Result</h2>

        <p>
            Age: <?php echo $age; ?>
        </p>

        <p>
            Province: <?php echo $province; ?>
        </p>

        <p>
            <?php echo $result; ?>
        </p>

    <?php endif; ?>

</body>

</html>