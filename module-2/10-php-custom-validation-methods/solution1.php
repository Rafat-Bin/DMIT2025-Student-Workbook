<?php

function is_valid_date($user_date) {

    $date =
        date_parse_from_format(
            "Y-m-d",
            $user_date
        );

    return (
        $date["error_count"] == 0 &&
        $date["warning_count"] == 0
    );
}


$name = "";
$email = "";
$event_date = "";

$message = "";


if (isset($_POST["submit"])) {

    $name = trim($_POST["name"]);

    $email = trim($_POST["email"]);

    $event_date =
        trim($_POST["event_date"]);


    // Validate name

    if (empty($name)) {

        $message .=
            "<p>Name is required.</p>";

    } elseif (strlen($name) > 50) {

        $message .=
            "<p>Name cannot be longer than 50 characters.</p>";
    }


    // Validate email

    if (empty($email)) {

        $message .=
            "<p>Email is required.</p>";

    } elseif (
        filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        ) == FALSE
    ) {

        $message .=
            "<p>Please enter a valid email address.</p>";
    }


    // Validate event date

    if (empty($event_date)) {

        $message .=
            "<p>Please enter an event date.</p>";

    } elseif (
        is_valid_date($event_date) == FALSE
    ) {

        $message .=
            "<p>Please enter a valid date.</p>";

    } else {

        $today =
            strtotime(date("Y-m-d"));

        $minimum_date =
            strtotime("+5 days", $today);

        $converted_event_date =
            strtotime($event_date);

        if (
            $converted_event_date <
            $minimum_date
        ) {

            $message .=
                "<p>Please select a date at least 5 days from today.</p>";
        }
    }


    // Everything passed validation

    if (empty($message)) {

        echo "<p>Registration successful!</p>";

        echo "<p>Name: $name</p>";

        echo "<p>Email: $email</p>";

        echo "<p>Event Date: $event_date</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Event Registration
    </title>

</head>

<body>

    <h1>
        Event Registration
    </h1>


    <?php echo $message; ?>


    <form method="POST">

        <div>

            <label for="name">
                Name:
            </label>

            <input
                type="text"
                id="name"
                name="name"
            >

        </div>


        <div>

            <label for="email">
                Email:
            </label>

            <input
                type="text"
                id="email"
                name="email"
            >

        </div>


        <div>

            <label for="event_date">
                Event Date:
            </label>

            <input
                type="text"
                id="event_date"
                name="event_date"
                placeholder="YYYY-MM-DD"
            >

        </div>


        <button
            type="submit"
            name="submit"
        >
            Register
        </button>

    </form>

</body>

</html>