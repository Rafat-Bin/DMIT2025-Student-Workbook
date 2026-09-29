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

---

# Practice Exercises

These exercises practice the main ideas from this lesson.

Try to complete each exercise before looking back at the examples.

---

## Exercise 1 — Calculate a Final Price

Create a function named:

```php
calculate_total()
```

The function should receive two parameters:

```text
$price
$quantity
```

Multiply `$price` by `$quantity` to calculate the total.

If the total is **100 or more**, apply a **10% discount**.

The function should return the final total.

For example:

```php
$total = calculate_total(25, 2);
```

The result should be:

```text
50
```

If we call:

```php
$total = calculate_total(50, 3);
```

The original total is:

```text
150
```

Because the total is `100` or more, the function should apply a 10% discount.

The returned result should be:

```text
135
```

Display the returned value on the page.

### Requirements

Your program should:

- Create a function named `calculate_total()`
- Use `$price` and `$quantity` as parameters
- Multiply the price by the quantity
- Use an `if` statement to check the total
- Apply a 10% discount when the total is `100` or more
- Return the final total
- Call the function and display the returned value

---

## Exercise 2 — Student Grade

Create a function named:

```php
get_grade()
```

The function should receive a student's mark as a parameter.

Use conditions to determine the student's grade.

Use the following grading system:

```text
80 or higher  →  A
70 to 79      →  B
60 to 69      →  C
50 to 59      →  D
Below 50      →  F
```

For example:

```php
$grade = get_grade(76);
```

The function should return:

```text
B
```

Call the function using three different marks.

For example:

```php
get_grade(85);
get_grade(72);
get_grade(45);
```

Display each returned grade on the page.

### Requirements

Your program should:

- Create a function named `get_grade()`
- Pass the student's mark into the function
- Use `if`, `elseif`, and `else`
- Return the correct letter grade
- Call the function with at least three different marks
- Display each returned value

---

## Exercise 3 — Shipping Calculator

Create a function named:

```php
calculate_shipping()
```

The function should receive two parameters:

```text
$weight
$rate
```

Give `$rate` a default value of:

```php
2
```

Calculate the shipping cost using:

```text
weight × rate
```

If the weight is `10` or greater, add an additional `$5` to the shipping cost.

For example:

```php
$shipping = calculate_shipping(4);
```

Because no rate was provided, PHP should use the default rate of `2`.

The calculation is:

```text
4 × 2 = 8
```

The function should return:

```text
8
```

Now call:

```php
$shipping = calculate_shipping(12, 3);
```

The calculation is:

```text
12 × 3 = 36
```

Because the weight is `10` or greater, add `$5`:

```text
36 + 5 = 41
```

The function should return:

```text
41
```

Display both returned shipping costs on the page.

### Requirements

Your program should:

- Create a function named `calculate_shipping()`
- Use `$weight` and `$rate` as parameters
- Give `$rate` a default value of `2`
- Calculate the shipping cost
- Use an `if` statement to check the weight
- Add `$5` when the weight is `10` or greater
- Return the final shipping cost
- Call the function once using the default rate
- Call the function again using a different rate
