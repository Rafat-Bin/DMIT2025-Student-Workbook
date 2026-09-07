<?php
    // You can open a PHP block before the document even begins!
    // Let's start with a simple variable. 
    $name = "Your Name";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Basics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body class="container text-center">
    <section class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-8">
            <h1 class="display-5 mb-4">
                <?php 
                    echo "Hello, world!";
                ?>
            </h1>

            <?php 
                // We can write simple HTML tags within PHP echo statements, but what about attributes? The `"` might make our parser think that we're done with our echo statement. To avoid confusion, we use the escape character, `\`, to let our parser know that we are not done and want the `"` character to be included literally. 

                // Now, if we want to echo out the value of a variable, we can do it through concatenation. In PHP, we use a `.` to combine strings. 
                echo "<p >It's good to see you, " . $name . "!</p>";
                echo "<p>Did you know that you can hop back and forth between PHP and HTML within a .php file? Neat!</p>"
            ?>
            
            <a href="index.php" class="btn btn-outline-primary">Return to Table of Contents</a>
        </div>
    </section>
  </body>
</html>