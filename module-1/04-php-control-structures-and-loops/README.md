# Comparison Operators, Control Structures, & Loops

So far, we have learned how to create variables, work with values, perform calculations, and combine PHP with HTML.

Now we are going to make our programs **make decisions** and **repeat tasks**.

In this lesson, we will learn how to:

- compare values
- make decisions with `if`, `elseif`, and `else`
- combine multiple conditions
- use conditions with HTML
- use `switch` statements
- repeat code with loops

---

# Boolean Values

Before we start making decisions, we need to understand boolean values.

A boolean has only two possible values:

```php
true
false
```

We can think of these as **yes/no** or **on/off** values.

When PHP compares two values, the result of that comparison is either `true` or `false`.

For example:

```php
10 > 5; // true

10 < 5; // false
```

These comparisons become useful when we start making decisions with our code.

---

# Comparison Operators

Comparison operators allow us to compare two values.

For example:

```php
$age = 20;

$age >= 18;
```

PHP asks:

> Is `$age` greater than or equal to `18`?

Since `$age` contains `20`, the comparison is `true`.

Here are the comparison operators we will commonly use:

| Operator | Meaning |
| --- | --- |
| `==` | equal value |
| `===` | equal value and data type |
| `!=` | not equal |
| `!==` | not identical |
| `>` | greater than |
| `<` | less than |
| `>=` | greater than or equal to |
| `<=` | less than or equal to |

---

## Equal `==`

The `==` operator checks whether two values are equal.

```php
10 == 10; // true

10 == 5; // false
```


---

## Identical `===`

The `===` operator checks both the **value** and the **data type**.

```php
10 === 10; // true

'10' === 10; // false
```

The second comparison is `false` because:

```text
'10' is a string
10 is an integer
```

Their values look similar, but their data types are different.

---

## Not Equal

We can check if values are different using `!=`.

```php
10 != 5; // true

10 != 10; // false
```


---

## Comparing Numbers

We can also compare the size of numbers.

```php
10 > 5;   // true
10 >= 10; // true
```

These comparisons are commonly used when making decisions.

---

# `if` Statements

An `if` statement allows our program to make a decision.

The code inside the `if` statement runs only when its condition is `true`.

```php
$age = 20;

if ($age >= 18) {
    echo "You are an adult.";
}
```

PHP checks:

```php
$age >= 18
```

Since `20` is greater than or equal to `18`, the condition is `true`.

Therefore, PHP runs:

```php
echo "You are an adult.";
```

If the condition were `false`, nothing inside the `{ }` would run.

---

# `else`

Sometimes we want something different to happen when the condition is `false`.

We can use `else`.

```php
$age = 16;

if ($age >= 18) {
    echo "You are an adult.";
} else {
    echo "You are under 18.";
}
```

PHP first checks:

```php
$age >= 18
```

If it is `true`, the first block runs.

If it is `false`, the `else` block runs.

---

# `elseif`

Sometimes there are more than two possible outcomes.

We can use `elseif` to check another condition.

```php
$grade = 75;

if ($grade >= 80) {
    echo "Great job!";
} elseif ($grade >= 50) {
    echo "You passed.";
} else {
    echo "You did not pass.";
}
```

PHP checks the conditions from top to bottom.

Once PHP finds a condition that is `true`, it runs that block and skips the remaining conditions.

---

# Logical Operators

Sometimes one comparison is not enough.

Logical operators allow us to combine multiple conditions.

The three logical operators we will commonly use are:

| Operator | Meaning |
| --- | --- |
| `&&` | AND |
| `\|\|` | OR |
| `!` | NOT |

---

## AND `&&`

AND means **both conditions must be true**.

```php
$age = 20;
$has_ticket = true;

if ($age >= 18 && $has_ticket === true) {
    echo "You may enter.";
}
```

For this code to run:

- the person must be at least 18
- the person must have a ticket

Both conditions must be `true`.

---

## OR `||`

OR means **at least one condition must be true**.

```php
$is_student = true;
$is_senior = false;

if ($is_student || $is_senior) {
    echo "You receive a discount.";
}
```

Only one of the conditions needs to be `true`.

---

## NOT `!`

The `!` operator means **NOT**.

It reverses a boolean value.

```php
$is_raining = false;

if (!$is_raining) {
    echo "Let's go outside.";
}
```

Here:

```php
!$is_raining
```

means:

> If it is NOT raining.

---

# Nested `if` Statements

An `if` statement can also exist inside another `if` statement.

This is called a **nested `if` statement**.

```php
$age = 20;
$has_ticket = true;

if ($age >= 18) {

    if ($has_ticket) {
        echo "You may enter.";
    }

}
```

The second `if` statement is only checked if the first condition is `true`.

Sometimes the same logic can be written more simply with `&&`:

```php
if ($age >= 18 && $has_ticket) {
    echo "You may enter.";
}
```

Both approaches are valid. Which one you use depends on the problem you are trying to solve.

---

# Using Conditions with HTML

In Lesson 1, we learned that PHP and HTML can exist in the same `.php` file.

Conditions can also control which HTML appears on the page.

For example:

```php
<?php

$logged_in = true;

?>

<?php if ($logged_in): ?>

    <p>Welcome back!</p>

<?php else: ?>

    <p>Please log in.</p>

<?php endif; ?>
```

If `$logged_in` is `true`, the browser receives:

```html
<p>Welcome back!</p>
```

If `$logged_in` is `false`, the browser receives:

```html
<p>Please log in.</p>
```

This syntax can be easier to read when PHP conditions contain larger sections of HTML.

---

# `switch` Statements

Sometimes we want to compare one variable against several possible values.

A `switch` statement can help organize this.

```php
$day = "Monday";

switch ($day) {

    case "Monday":
        echo "Start of the week.";
        break;

    case "Friday":
        echo "Almost the weekend!";
        break;

    default:
        echo "Another day.";
}
```

PHP compares `$day` against each `case`.

In this example:

```php
$day = "Monday";
```

matches:

```php
case "Monday":
```

so PHP displays:

```text
Start of the week.
```

---

## `case`

Each `case` represents a possible value.

```php
case "Monday":
```

PHP checks whether the value used by the `switch` matches that case.

---

## `break`

The `break` statement tells PHP to leave the `switch`.

```php
case "Monday":
    echo "Start of the week.";
    break;
```

Without `break`, PHP can continue into the next case.

For now, we will normally use `break` after each case.

---

## `default`

The `default` block runs when none of the cases match.

```php
default:
    echo "Another day.";
```

You can think of `default` as being similar to the final `else` in an `if` statement.

---

# Loops

Loops allow us to repeat code.

Instead of writing:

```php
echo "Hello<br>";
echo "Hello<br>";
echo "Hello<br>";
echo "Hello<br>";
echo "Hello<br>";
```

we can use a loop.

```php
for ($i = 1; $i <= 5; $i++) {
    echo "Hello<br>";
}
```

Both examples display `"Hello"` five times.

Loops are useful whenever we need to repeat the same task.

---

# `for` Loops

A `for` loop is useful when we know how many times we want something to repeat.

```php
for ($i = 1; $i <= 5; $i++) {
    echo $i . "<br>";
}
```

This displays:

```text
1
2
3
4
5
```

A `for` loop has three important parts:

```php
for ($i = 1; $i <= 5; $i++)
```

We can break it down like this:

```text
$i = 1       Start at 1

$i <= 5      Continue while this is true

$i++         Add 1 after each loop
```

---

## Incrementing with `++`

The `++` operator increases a number by `1`.

```php
$i++;
```

This is a shorter way of writing:

```php
$i = $i + 1;
```

For example:

```php
$i = 1;

$i++;

echo $i;
```

This displays:

```text
2
```

---

## Infinite Loops

A loop needs a way to eventually stop.

For example:

```php
for ($i = 1; $i <= 5; $i++) {
    echo $i;
}
```

`$i` increases after every loop.

Eventually:

```php
$i <= 5
```

becomes `false`, and the loop stops.

If a loop never reaches a stopping point, it can become an **infinite loop**.

Always make sure your loop can eventually end.

---

# `foreach` Loops

A `foreach` loop is designed for working with arrays.

Suppose we have an array:

```php
$colours = ["Red", "Green", "Blue"];
```

We can go through every item using `foreach`.

```php
foreach ($colours as $colour) {
    echo $colour . "<br>";
}
```

This displays:

```text
Red
Green
Blue
```

Each time the loop runs, `$colour` contains the next value from the array.

You can think of:

```php
foreach ($colours as $colour)
```

as:

> For each item in `$colours`, temporarily call that item `$colour`.

---

## Associative Arrays with `foreach`

We can also use `foreach` with associative arrays.

```php
$student = [
    "name" => "John",
    "program" => "DMIT",
    "year" => 1
];
```

We can access both the key and value:

```php
foreach ($student as $key => $value) {
    echo $key . ": " . $value . "<br>";
}
```

This would display:

```text
name: John
program: DMIT
year: 1
```

---

# `do...while` Loops

Another type of loop is a `do...while` loop.

```php
$i = 1;

do {

    echo $i . "<br>";

    $i++;

} while ($i <= 5);
```

This displays:

```text
1
2
3
4
5
```

A `do...while` loop checks its condition **after** running the code.

This means the code inside the loop will always run at least once.

For example:

```php
$i = 10;

do {

    echo $i;

} while ($i < 5);
```

Even though:

```php
$i < 5
```

is `false`, the loop still runs once because the condition is checked at the end.

---

# A Few Useful PHP Functions

PHP includes many built-in functions that can help us solve common problems.

Here are a few that we will use later.

---

## `random_int()`

`random_int()` can generate a random integer.

For example:

```php
$roll = random_int(1, 6);

echo $roll;
```

This generates a random number from `1` through `6`.

That means `$roll` could contain:

```text
1, 2, 3, 4, 5, or 6
```

We can change the range:

```php
$roll = random_int(1, 20);
```

Now PHP generates a random number from `1` through `20`.

---

## `max()`

`max()` finds the largest value.

```php
$highest = max(4, 6);

echo $highest;
```

This displays:

```text
6
```

---

## `min()`

`min()` finds the smallest value.

```php
$lowest = min(4, 6);

echo $lowest;
```

This displays:

```text
4
```

---

# Putting It Together

We can combine the concepts from this lesson.

```php
<?php

$roll = random_int(1, 6);

?>

<h1>Dice Roll</h1>

<p>You rolled: <?= $roll ?></p>

<?php if ($roll === 6): ?>

    <p>Great roll!</p>

<?php elseif ($roll === 1): ?>

    <p>Bad luck!</p>

<?php else: ?>

    <p>Keep rolling!</p>

<?php endif; ?>
```

In this example:

1. `random_int()` generates a number.
2. The value is stored in `$roll`.
3. PHP displays the value inside the HTML.
4. `if`, `elseif`, and `else` decide which message to display.

This combines PHP variables, HTML, comparisons, and control structures together.

---

