
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Your Profile</h1>

    <?php if (isset($_SESSION['username'], $_SESSION['colour'])): ?>

        <p>
            Hello,
            <?= htmlspecialchars($_SESSION['username']) ?>!
        </p>

        <p>
            Your favourite colour is
            <strong><?= htmlspecialchars($_SESSION['colour']) ?></strong>.
        </p>

        <p>
            Both values were retrieved from your PHP session.
        </p>

    <?php else: ?>

        <p>No information has been saved.</p>

    <?php endif; ?>

    <a href="index.php" class="btn btn-primary">
        Back to Home
    </a>

</main>
</body>
</html>
