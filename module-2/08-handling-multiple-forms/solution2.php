<?php

$language = "";

if (isset($_GET["language"])) {

    $language = $_GET["language"];

} elseif (isset($_POST["language"])) {

    $language = $_POST["language"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $reason = $_POST["reason"];

    echo "<h2>Submitted Information</h2>";
    echo "<p>Language: {$language}</p>";
    echo "<p>Reason: {$reason}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exercise 2</title>
</head>

<body>

    <h1>Choose a Programming Language</h1>

    <form method="GET">

        <label for="language">
            Programming Language:
        </label>

        <select
            id="language"
            name="language"
        >

            <option value="PHP">
                PHP
            </option>

            <option value="JavaScript">
                JavaScript
            </option>

            <option value="Python">
                Python
            </option>

        </select>

        <button type="submit">
            Continue
        </button>

    </form>


    <?php if ($language != "") : ?>

        <h2>
            Why do you like <?php echo $language; ?>?
        </h2>

        <form method="POST">

            <input
                type="hidden"
                name="language"
                value="<?php echo $language; ?>"
            >

            <label for="reason">
                Reason:
            </label>

            <input
                type="text"
                id="reason"
                name="reason"
                required
            >

            <button type="submit">
                Submit
            </button>

        </form>

    <?php endif; ?>

</body>

</html>