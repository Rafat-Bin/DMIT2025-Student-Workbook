<?php

session_start();

if (isset($_POST['username'], $_POST['colour'])) {
    $username = trim($_POST['username']);
    $colour = $_POST['colour'];

    $colours = ['Red', 'Blue', 'Green'];

    if ($username !== '' && in_array($colour, $colours, true)) {
        $_SESSION['username'] = $username;
        $_SESSION['colour'] = $colour;

        header('Location: index.php');
        exit();
    }
}

if(isset($_POST['forget'])){

    session_unset();
    session_destroy();

    header('Location: index.php');
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remembaring States</title>
<link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


</head>
<body>

<main class = "container py-5">
    <?php if(isset($_SESSION['username'], $_SESSION['colour'])): ?>
        <h1>Hello,  <?= htmlspecialchars($_SESSION['username']) ?> !!!! </h1>
        <p> Your name hass been saved using php session</p>
        <p>
            Your favourite colour is
            <strong><?= htmlspecialchars($_SESSION['colour']) ?></strong>.
        </p>


        <a href="profile.php" class= "btn btn-primary"> Vieew Profile</a>

    <form action="index.php" method= "post" class= "mt-3">

            <button
            type = "submit" name = "forget" value= "1" class = "btn btn-danger">
                Forget Me
            </button>
    </form>

    <?php else: ?>

        <h1>WHats your name?</h1>

        <p> Enter your name so we can remember you!!!</p>

        <form action="index.php" method = "post"> 
            <div class ="mb-3">
                <label for="username" class = "form-label"> Your Name </label>

                <input type="text" name= "username" id = "username" class = "form-control" required>

            </div>

            <div class="mb-3">
                <label for="colour" class="form-label">
                    Favourite Colour
                </label>

                <select
                    name="colour"
                    id="colour"
                    class="form-select"
                    required
                >
                    <option value="">Choose a colour</option>
                    <option value="Red">Red</option>
                    <option value="Blue">Blue</option>
                    <option value="Green">Green</option>
                </select>
            </div>

            <button type= "submit" class = "btn btn-primary"> Remember Me</button>

        </form>

        <?php endif;?>

</main>        
   
</body>
</html>
