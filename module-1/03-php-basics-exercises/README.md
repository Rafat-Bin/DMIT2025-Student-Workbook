# PHP Basics: Exercises

Now that we have the basics tucked away, let's walk through a few exercises together. 

These exercises will be algorithmic (i.e. they will follow a series of logical steps) and will help us solve specific problems through the lense of this new language. 

---

## Problem 1

Write a script that begins with two variables. Each variable should be a number.

Echo out these variables. 

Next, figure out a way to assign the value of the first variable to the second variable and the value of the second variable to the first variable; however, you are not allowed to use numbers at this point, only variable names. 

Hint: Try using a third variable.

Echo out the final output.

---

## Problem 2

A right triangle has three sides: `a`, `b`, and `c`.

The longest side, `c`, is called the **hypotenuse**.

To find `c`, we use the **Pythagorean theorem**:

**a² + b² = c²**

For example, if:

```text
a = 3
b = 4
```

then:

```text
3² + 4² = 25

√25 = 5
```

So:

```text
c = 5
```

### Your Task

Write a PHP script that:

1. Creates a variable for `a`.
2. Creates a variable for `b`.
3. Calculates `c`.
4. Echoes the value of `c`.

### Hint

Use `**` to square a number:

```php
$a ** 2
```

Use `sqrt()` to find the square root:

```php
sqrt(25);
```

Try to combine these to calculate `c`.

---

## Problem 3

## Problem 3

Start with a variable containing a **four-digit number**.

For example:

```php
$number = 1234;
```

Your goal is to add each digit together.

For `1234`:

```text
1 + 2 + 3 + 4 = 10
```

### Your Task

Write a PHP script that:

1. Stores a four-digit number in a variable.
2. Gets each digit from the number.
3. Adds the four digits together.
4. Echoes the final total.

### Hint

The modulus operator `%` gives you the **remainder after division**.

For example:

```php
1234 % 10
```

gives:

```text
4
```

This can help you get the last digit of a number.

---

## Using `include()`

Today's lesson follows the same format as yesterday's lesson; however, this is a bit cumbersome because we need to deal with so much HTML before moving onto the actual PHP and solving our problems.

Fortunately, there's a better way! We can save snippets of code that we want to use over and over again as its own separate file. So, for example, let's say we want to use the `<head>` and the first few lines of the `<body>` over and over again. It might look like this:

```HTML
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP Basics: Exercises</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    </head>
    <body class="container text-center">
        <section class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-8">
```

We could save this as a file called `header.html` and import it into our PHP by using one of PHP's language-defined functions.

```PHP
    include('header.html');
```

Of course, in HTML, everything that opens must also close, so we can include a footer as well.

```HTML
            </div>
        </section>
    </body>
    </html>
```

```PHP
    include('footer.html');
```

## Including Other File Types

When including an HTML file, the data inside that file is imported as-is (i.e. the data is immutable). However, if you would like to use variables, such as something to store a unique `<title>` for each page, you can include a `.php` file instead.

Here's an example of what that might look like inside of a `header.php`:

```PHP
     <title>PHP Basics: <?php echo $title; ></title>
```

Because this file now echoes out the value of a variable, we need to initialise or define that variable before our `include` statement. 

```PHP
    $title = "Table of Contents";
    include('includes/header.php');
```
