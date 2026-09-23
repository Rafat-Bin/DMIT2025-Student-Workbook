# Lesson 09 — Custom Functions

So far, we have used built-in PHP functions such as `isset()`, `date()`.

PHP also allows us to create our own functions.

A **function** is a reusable block of code that performs a task.

Instead of writing the same code multiple times, we can define it once and call the function whenever we need it.

By the end of this lesson, you will be able to:

- Create and call a function
- Pass values into a function
- Return values from a function
- Use returned values in your program
- Give parameters default values
- Define accepted parameter types
- Recognize anonymous functions

---

## 1. Creating and Calling a Function

A function is created using the `function` keyword.

The basic syntax is:

```php
function function_name() {

    // Code to run
}
```

For example:

```php
function greet() {

    return "Hello!";
}
```

Creating the function does not automatically run it.

To run the function, we **call** it using its name:

```php
greet();
```

Because `greet()` returns a value, we can display it:

```php
echo greet();
```

The result is:

```text
Hello!
```

### Complete Example — `index.php`

```php
<?php

function greet() {

    return "Hello!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating a Function</title>
</head>

<body>

    <h1>Creating a Function</h1>

    <p>
        <?php echo greet(); ?>
    </p>

</body>

</html>
```

### Key Idea

**A function is defined once and runs when we call it.**

---

## 2. Parameters and Arguments

Functions can receive values.

A **parameter** is a variable listed when the function is created.

For example:

```php
function greet($name) {

    return "Hello " . $name;
}
```

Here:

```php
$name
```

is a parameter.

When we call the function:

```php
echo greet("Sam");
```

`"Sam"` is the **argument** being passed into the function.

```text
"Sam"  →  $name
```

A function can also have more than one parameter:

```php
function add($a, $b) {

    return $a + $b;
}

echo add(5, 3);
```

Here:

```text
5  →  $a
3  →  $b
```

The result is:

```text
8
```

### Complete Example — `index.php`

```php
<?php

function greet($name) {

    return "Hello " . $name;
}

function add($a, $b) {

    return $a + $b;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameters and Arguments</title>
</head>

<body>

    <h1>Parameters and Arguments</h1>

    <p>
        <?php echo greet("Sam"); ?>
    </p>

    <p>
        <?php echo add(5, 3); ?>
    </p>

</body>

</html>
```

### Key Idea

**Parameters receive the arguments that are passed into a function.**

---

## 3. Returning and Using Values

The `return` keyword sends a value back to the code that called the function.

For example:

```php
function add($a, $b) {

    return $a + $b;
}
```

We can store the returned value in a variable:

```php
$total = add(5, 3);

echo $total;
```

The flow is:

```text
5 and 3
   ↓
add(5, 3)
   ↓
return 8
   ↓
$total
```

A function can also return `TRUE` or `FALSE`:

```php
function is_adult($age) {

    if ($age >= 18) {

        return TRUE;

    } else {

        return FALSE;
    }
}
```

We can store the returned value:

```php
$adult = is_adult(20);
```

Then use it in the program:

```php
if ($adult == TRUE) {

    echo "Adult";

} else {

    echo "Not an adult";
}
```

### Complete Example — `index.php`

```php
<?php

function is_adult($age) {

    if ($age >= 18) {

        return TRUE;

    } else {

        return FALSE;
    }
}

$adult = is_adult(20);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returning Values</title>
</head>

<body>

    <h1>Returning Values</h1>

    <?php

    if ($adult == TRUE) {

        echo "<p>Adult</p>";

    } else {

        echo "<p>Not an adult</p>";
    }

    ?>

</body>

</html>
```

### Key Idea

**A function can return a value that the rest of the program can store and use.**

---

## 4. Default Parameter Values

A parameter can have a **default value**.

For example:

```php
function times($a, $b = 2) {

    return $a * $b;
}
```

Here:

```php
$b = 2
```

gives `$b` a default value of `2`.

If we call:

```php
echo times(5);
```

PHP uses:

```text
$a = 5
$b = 2
```

The result is:

```text
10
```

We can also provide a different value:

```php
echo times(5, 3);
```

Now PHP uses:

```text
$a = 5
$b = 3
```

The result is:

```text
15
```

### Complete Example — `index.php`

```php
<?php

function times($a, $b = 2) {

    return $a * $b;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default Parameter Values</title>
</head>

<body>

    <h1>Default Parameter Values</h1>

    <p>
        <?php echo times(5); ?>
    </p>

    <p>
        <?php echo times(5, 3); ?>
    </p>

</body>

</html>
```

### Key Idea

**A default value is used when an argument is not provided for that parameter.**

---

## 5. Union Typing

PHP can specify which types of values a parameter accepts.

For example:

```php
function double(int|float|null $number) {

    return $number * 2;
}
```

The parameter:

```php
int|float|null $number
```

allows `$number` to receive an:

```text
int
float
null
```

The `|` separates the accepted types.

For example:

```php
echo double(5);
echo double(2.5);
```

### Complete Example — `index.php`

```php
<?php

function double(int|float|null $number) {

    return $number * 2;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Union Typing</title>
</head>

<body>

    <h1>Union Typing</h1>

    <p>
        <?php echo double(5); ?>
    </p>

    <p>
        <?php echo double(2.5); ?>
    </p>

</body>

</html>
```

### Key Idea

**Union typing allows a parameter to accept more than one specified type.**

---

## 6. Anonymous Functions

A function does not always need to have a name.

A function without a name is called an **anonymous function**.

For example:

```php
$greeting = function () {

    return "Hello!";
};
```

The function itself does not have a name. It is stored in the `$greeting` variable.

We can run it using:

```php
echo $greeting();
```

The result is:

```text
Hello!
```

### Complete Example — `index.php`

```php
<?php

$greeting = function () {

    return "Hello!";
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anonymous Functions</title>
</head>

<body>

    <h1>Anonymous Functions</h1>

    <p>
        <?php echo $greeting(); ?>
    </p>

</body>

</html>
```

### Key Idea

**An anonymous function is a function without a name.**
