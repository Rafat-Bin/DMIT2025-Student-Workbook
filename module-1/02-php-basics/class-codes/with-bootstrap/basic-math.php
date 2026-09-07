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
            <h1 class="display-5 mb-4">Basic Arithmetic</h1>

            <?php 
                // We'll set up our variables here, but we can reassign the values over and over again.

                $num1 = 3;
                $num2 = 6;

                // Addition
                $num3 = $num1 + $num2;
                echo "<p>The sum of $num1 and $num2 is $num3.</p>";

                // Subtraction
                $num3 = $num1 - $num2;
                echo "<p>$num2 taken away from $num1 is $num3.</p>";

                // Multiplication
                $num3 = $num1 * $num2;
                echo "<p>$num1 multiplied by $num2 equals $num3.</p>";

                // Division
                $num3 = $num1 / $num2;
                echo "<p>$num1 divided by $num2 equals $num3.</p>";

                // Exponentiation
                $exponent = 4 ** 2; 
                echo "<p>4 raised to the power of 2 is $exponent.</p>";

                // Modulus (Remainder)
                $dividend = 5;
                $divisor = 2;
                $quotient = $dividend % $divisor;
                echo "<p>$dividend divided by $divisor has a remainder of $quotient.</p>";
            ?>
            
            <a href="index.php" class="btn btn-outline-primary mt-5">Return to Table of Contents</a>
        </div>
    </section>
  </body>
</html>

