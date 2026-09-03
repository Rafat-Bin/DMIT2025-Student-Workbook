# PHP Basics 

PHP is a programming language that allows you to add back-end functionality to your web applications and create dynamic websites.

It often works alongside other technologies such as:

```text
HTML       → structure
CSS        → appearance
JavaScript → browser-side interaction
PHP        → server-side logic
SQL        → database
```

PHP is a **server-side language**, which means the server runs the PHP code and sends the result to the user's browser.

For example:

```php
<?php

$name = "Rafat";

echo "<h1>Hello $name</h1>";
```

The server processes the PHP and produces something like:

```html
<h1>Hello Rafat</h1>
```

The browser receives the resulting HTML.

The user normally does **not** receive the original PHP source code.

## How PHP Works

```text
PHP file
   ↓
Server runs PHP
   ↓
PHP generates output
   ↓
Browser receives output
   ↓
User sees webpage
```

Because PHP runs on the server, it can be used for things such as:

- Processing forms
- Working with databases
- Handling sessions
- Processing user input
- Generating dynamic HTML
- Working with authentication systems


---

# Getting Started

To use PHP, there are a few important requirements.

## 1. PHP Needs a Server

PHP requires a PHP-enabled server or development environment.

A web browser does not execute PHP source code by itself.

## 2. Use the `.php` File Extension

PHP code should normally be written inside a file ending with:

```text
.php
```

Examples:

```text
index.php
about.php
students.php
profile.php
```

A file ending in:

```text
.html
```

will normally be treated as an HTML file rather than processed as PHP.

## 3. PHP Tags

PHP code begins with:

```php
<?php
```

Example:

```php
<?php

echo "Hello";
```

PHP mode can also be closed with:

```php
?>
```

Example:

```php
<?php

echo "Hello";

?>
```

Think of it like this:

```text
<?php  → enter PHP mode

?>     → leave PHP mode
```

## Common Mistake

Incorrect:

```text
<? php
```

Correct:

```php
<?php
```

There must be **no space** between `?` and `php`.

## Do We Always Need `?>`?

No.

If a file contains only PHP, the closing PHP tag is commonly omitted.

Example:

```php
<?php

echo "Hello";
echo "World";
```

When PHP and HTML are mixed together, the closing tag can be useful.

Example:

```php
<?php

$name = "Rafat";

?>

<h1>Hello!</h1>
```

## Exercise

Find the error:

```text
<? php

echo "Hello";

?>
```

<details>
<summary>Answer</summary>

The opening PHP tag is incorrect.

Correct:

```php
<?php
```

</details>

---

# `echo` — Outputting Information

One of the first things to learn in PHP is:

```php
echo
```

`echo` means:

> Output something.

Example:

```php
<?php

echo "Hello World";
```

Browser output:

```text
Hello World
```

You can also output a number:

```php
echo 25;
```

Output:

```text
25
```

You can output text:

```php
echo "Rafat";
```

Output:

```text
Rafat
```


## Strings Need Quotes

Text values should be written inside quotation marks:

```php
echo "Hello";
```

or:

```php
echo 'Hello';
```

Numbers usually do not need quotation marks:

```php
echo 25;
```

Compare these:

```php
echo 25;
```

`25` is a number.

But:

```php
echo "25";
```

`"25"` is a string containing the characters `2` and `5`.

## Exercise

Predict the output:

```php
echo "I am learning PHP";
```

<details>
<summary>Answer</summary>

```text
I am learning PHP
```

</details>

---

# Semicolons `;`

PHP statements usually end with a semicolon.

Example:

```php
echo "Hello";
```

The semicolon:

```text
;
```

means:

> This PHP statement is finished.

Example:

```php
$name = "Rafat";
$age = 25;

echo $name;
echo $age;
```

It is more accurate to say that a semicolon ends a **statement**, not necessarily a line.

This is technically valid:

```php
$name = "Rafat"; $age = 25; echo $name;
```

But this is much easier to read:

```php
$name = "Rafat";
$age = 25;

echo $name;
```

## Exercise

What is wrong here?

```php
$name = "Rafat"
echo $name;
```

<details>
<summary>Answer</summary>

The first statement is missing a semicolon.

Correct:

```php
$name = "Rafat";
echo $name;
```

</details>

---

# Formatting Output

Consider this code:

```php
echo "Rafat";
echo "Hello";
echo "PHP";
```

You might expect:

```text
Rafat
Hello
PHP
```

But the browser will display:

```text
RafatHelloPHP
```

`echo` does not automatically add a visible line break.

## Using `<br>`

Because PHP often outputs HTML, you can use:

```html
<br>
```

Example:

```php
echo "Rafat<br>";
echo "Hello<br>";
echo "PHP";
```

Browser output:

```text
Rafat
Hello
PHP
```

## Using `\n`

PHP also supports the newline character:

```php
echo "Hello\n";
echo "World";
```

`\n` is useful for:

- Terminal output
- Plain-text output
- Text files

However, HTML normally collapses whitespace, so `\n` usually does not create a visible line break in the rendered webpage.

For visible browser line breaks, use:

```php
echo "<br>";
```

## Using HTML Elements

Instead of using many `<br>` tags, you can use proper HTML elements.

Example:

```php
echo "<p>Rafat</p>";
echo "<p>Hello</p>";
echo "<p>PHP</p>";
```

---

# Comments

Comments are notes written inside your code.

PHP ignores comments when the program runs.

## Single-Line Comments

Use:

```php
//
```

Example:

```php
// Store the student's name
$name = "Rafat";
```

You can also put a comment after code:

```php
$name = "Rafat"; // Student name
```

## Multi-Line Comments

Use:

```php
/*
...
*/
```

Example:

```php
/*
This program calculates
a student's final score.
*/

$score = 85;
```

## Why Use Comments?

Comments are useful for explaining code.

For example:

```php
// Apply a 5% discount
$discount = 0.05;
```

A good comment usually explains **why**, rather than describing something obvious.

## Exercise

Comment out this line:

```php
echo "This should not appear";
```

<details>
<summary>Answer</summary>

```php
// echo "This should not appear";
```

</details>

---

# Variables

Variables allow us to store values and use them later.

Example:

```php
$name = "Rafat";
```

Think of a variable like a labeled box:

```text
$name
┌──────────┐
│ "Rafat"  │
└──────────┘
```

The variable:

```php
$name
```

refers to the stored value:

```text
Rafat
```

You can then output it:

```php
echo $name;
```

Output:

```text
Rafat
```

## Breaking Down a Variable

```php
$name = "Rafat";
```

can be understood like this:

```text
$name    =    "Rafat";
  ↑      ↑       ↑
variable assign  value
```

The equals sign:

```text
=
```

is called the **assignment operator**.

It assigns the value on the right to the variable on the left.

---

# PHP Variable Rules

PHP variables always begin with:

```text
$
```

Examples:

```php
$name = "Rafat";
$age = 25;
$price = 15.99;
```

Valid examples:

```php
$name
$_name
$student
$student1
$firstName
$first_name
```

Invalid examples:

```text
$1student
$first-name
```

After `$`, the variable name must begin with a letter or underscore.

## Camel Case

A common variable naming style is called **camelCase**.

Examples:

```php
$firstName = "Rafat";
$lastName = "Bin";
$studentAge = 25;
$courseName = "PHP Basics";
```

---

# Variables Can Change

Variables are called variables because their values can change.

Example:

```php
$age = 20;

echo $age;
```

Output:

```text
20
```

Now change the value:

```php
$age = 21;

echo $age;
```

Output:

```text
21
```

The newer value replaces the older value.

## Using the Existing Value

You can calculate a new value using the existing one.

Example:

```php
$age = 20;

$age = $age + 1;

echo $age;
```

PHP evaluates it like this:

```text
$age = $age + 1

$age = 20 + 1

$age = 21
```

## Exercise

Predict the output:

```php
$score = 50;

$score = $score + 10;

echo $score;
```

<details>
<summary>Answer</summary>

```text
60
```

</details>

---

# Strings and Quotes

A string is a collection of characters, usually used to represent text.

Examples:

```php
$name = "Rafat";
$city = "Edmonton";
$message = "Hello World";
```

PHP supports both double and single quotes.

Double quotes:

```php
"Hello"
```

Single quotes:

```php
'Hello'
```

Both can create strings, but they behave differently in some situations.

## Double Quotes

Variables can be interpreted inside double-quoted strings.

Example:

```php
$name = "Rafat";

echo "Hello $name";
```

Output:

```text
Hello Rafat
```

PHP replaces `$name` with its value.

## Single Quotes

With single quotes:

```php
$name = "Rafat";

echo 'Hello $name';
```

Output:

```text
Hello $name
```

PHP generally treats the contents literally.

For now, remember:

```text
" " → variables can be interpreted

' ' → generally treated literally
```

## Exercise

Predict the output:

```php
$food = "Pizza";

echo "My favourite food is $food";
```

<details>
<summary>Answer</summary>

```text
My favourite food is Pizza
```

</details>

---

# String Concatenation `.`

Concatenation means joining strings together.

PHP uses a period:

```text
.
```

Example:

```php
$firstName = "Rafat";
$lastName = "Bin";

echo $firstName . $lastName;
```

Output:

```text
RafatBin
```

There is no space because PHP joins the strings exactly as written.

To add a space:

```php
echo $firstName . " " . $lastName;
```

Output:

```text
Rafat Bin
```

Another example:

```php
$name = "Rafat";

echo "My name is " . $name;
```

Output:

```text
My name is Rafat
```

## Exercise

Given:

```php
$food = "Pizza";
$price = 15;
```

Make PHP output:

```text
Pizza costs $15
```

<details>
<summary>Answer</summary>

```php
echo $food . " costs $" . $price;
```

</details>

---

# Constants

A variable can change:

```php
$age = 25;
$age = 26;
```

A **constant** represents a value that should remain fixed while the program runs.

One way to create a constant is:

```php
define("AGE", 42);
```

You can output it with:

```php
echo AGE;
```

Notice the difference:

```text
Variable:
$age

Constant:
AGE
```

Constants do not use `$`.

## Example

```php
define("TAX_RATE", 0.05);

$price = 100;

$tax = $price * TAX_RATE;

echo $tax;
```

Output:

```text
5
```

Constant names are commonly written in uppercase:

```php
define("SITE_NAME", "My Website");
define("TAX_RATE", 0.05);
define("MAX_USERS", 100);
```

Uppercase is a naming convention that makes constants easy to recognize.

## Exercise

What is wrong here?

```php
define("PRICE", 50);

echo $PRICE;
```

<details>
<summary>Answer</summary>

`PRICE` is a constant, so it does not use `$`.

Correct:

```php
echo PRICE;
```

</details>

---

# Reserved Words

PHP contains words that already have special meaning in the language.

Examples include:

```text
if
else
function
class
return
```

You will learn many of these later.

You should not use reserved words where PHP expects you to create your own identifier.

For now, remember:

> Some words already belong to the PHP language and have special purposes.

---

# Data Types in PHP

Different kinds of values have different data types.

The main types in this lesson are:

| Data Type | Meaning |
| --- | --- |
| String | Text or a collection of characters |
| Integer | Whole numbers |
| Float | Decimal numbers |
| Boolean | `true` or `false` |
| Array | A collection of multiple values |

---

# Strings

Strings represent text.

Example:

```php
$name = "Rafat";
```

Other examples:

```php
$city = "Edmonton";
$course = "PHP Basics";
$message = "Hello World";
```

---

# Integers

Integers are whole numbers.

Examples:

```php
$age = 25;
$students = 30;
$temperature = -10;
```

These are all integers because they have no decimal part.

---

# Floats

Floats are numbers containing decimals.

Examples:

```php
$price = 19.99;
$temperature = 21.5;
$average = 87.25;
```

---

# Booleans

A Boolean can have only two possible values:

```php
true
false
```

Examples:

```php
$isStudent = true;
$isAdmin = false;
```

Do not confuse:

```php
$isStudent = true;
```

with:

```php
$isStudent = "true";
```

The first is a Boolean.

The second is a string.

---

# Arrays

An array allows one variable to store multiple values.

Example:

```php
$students = ["Rafat", "Jack", "Sarah"];
```

The values have indexes:

```text
0 → Rafat
1 → Jack
2 → Sarah
```

Array indexing starts at:

```text
0
```

So:

```php
echo $students[0];
```

outputs:

```text
Rafat
```

And:

```php
echo $students[2];
```

outputs:

```text
Sarah
```

## Exercise

Given:

```php
$foods = ["Pizza", "Burger", "Pasta"];
```

What does this output?

```php
echo $foods[1];
```

<details>
<summary>Answer</summary>

```text
Burger
```

</details>

---

# Associative Arrays

PHP arrays can also use named keys.

Example:

```php
$student = [
    "name" => "Rafat",
    "age" => 25,
    "program" => "DMIT"
];
```

You can access a value using its key:

```php
echo $student["name"];
```

Output:

```text
Rafat
```

Another example:

```php
echo $student["program"];
```

Output:

```text
DMIT
```

You may learn arrays in greater detail later.

---

# PHP Determines Data Types

For basic variables, PHP usually determines the data type from the value.

Example:

```php
$name = "Rafat";
$age = 25;
$price = 19.99;
$isStudent = true;
```

PHP understands that:

```text
"Rafat" → String
25      → Integer
19.99   → Float
true    → Boolean
```

This is related to PHP being a dynamically and weakly typed language.

---

# `var_dump()`

A useful function for inspecting a value and its type is:

```php
var_dump();
```

Example:

```php
$age = 25;

var_dump($age);
```

Output will look similar to:

```text
int(25)
```

For a string:

```php
$name = "Rafat";

var_dump($name);
```

Output will look similar to:

```text
string(5) "Rafat"
```

This tells us that the value is a string containing 5 characters.

## Exercise

What type do you expect here?

```php
$price = 15.99;

var_dump($price);
```

<details>
<summary>Answer</summary>

It will be a float.

</details>

---

# Basic Arithmetic

PHP supports the standard arithmetic operators.

| Operation | Operator |
| --- | --- |
| Addition | `+` |
| Subtraction | `-` |
| Multiplication | `*` |
| Division | `/` |
| Modulus | `%` |
| Exponentiation | `**` |

---

# Addition

Example:

```php
echo 10 + 5;
```

Output:

```text
15
```

Using variables:

```php
$num1 = 10;
$num2 = 5;

$result = $num1 + $num2;

echo $result;
```

Output:

```text
15
```

---

# Subtraction

Example:

```php
echo 10 - 5;
```

Output:

```text
5
```

Using variables:

```php
$money = 100;
$cost = 30;

$remaining = $money - $cost;

echo $remaining;
```

Output:

```text
70
```

---

# Multiplication

Example:

```php
echo 10 * 5;
```

Output:

```text
50
```

A more realistic example:

```php
$price = 20;
$quantity = 3;

$total = $price * $quantity;

echo $total;
```

Output:

```text
60
```

---

# Division

Example:

```php
echo 20 / 4;
```

Output:

```text
5
```

Another example:

```php
$total = 100;
$people = 4;

$amountPerPerson = $total / $people;

echo $amountPerPerson;
```

Output:

```text
25
```

---

# Arithmetic With Variables

Arithmetic becomes more useful when variables are involved.

Example:

```php
$num1 = 10;
$num2 = 5;

$result = $num1 + $num2;

echo $result;
```

PHP evaluates:

```text
$result = $num1 + $num2

$result = 10 + 5

$result = 15
```

This basic pattern appears constantly in programming:

```text
INPUT VALUES
     ↓
CALCULATION
     ↓
STORE RESULT
     ↓
OUTPUT RESULT
```

---

# Modulus `%`

The modulus operator gives the **remainder after division**.

Example:

```php
echo 10 % 3;
```

Think:

```text
10 ÷ 3 = 3 remainder 1
```

So the output is:

```text
1
```

Another example:

```php
echo 20 % 5;
```

Since:

```text
20 ÷ 5 = 4 remainder 0
```

the output is:

```text
0
```

## Exercise

Predict the output:

```php
echo 17 % 5;
```

<details>
<summary>Answer</summary>

```text
2
```

Because:

```text
5 × 3 = 15

17 - 15 = 2
```

</details>

---

# Exponentiation `**`

The exponentiation operator means "to the power of."

Example:

```php
echo 5 ** 2;
```

This means:

```text
5²
5 × 5
25
```

Output:

```text
25
```

Another example:

```php
echo 2 ** 3;
```

This means:

```text
2 × 2 × 2
8
```

Output:

```text
8
```

---

# Order of Operations

PHP follows the normal mathematical order of operations.

You may know this as **BEDMAS** or **PEMDAS**.

General order:

```text
1. Parentheses
2. Exponents
3. Multiplication and Division
4. Addition and Subtraction
```

## Example 1

```php
echo 5 + 2 * 3;
```

Multiplication happens first:

```text
5 + 2 × 3
    ↓
5 + 6
  ↓
11
```

Output:

```text
11
```

## Example 2

```php
echo (5 + 2) * 3;
```

Parentheses happen first:

```text
(5 + 2) × 3
    ↓
7 × 3
 ↓
21
```

Output:

```text
21
```

## Example From the Lesson

```php
echo 5 * 6 + 3 - 1;
```

Output:

```text
32
```

Because:

```text
5 × 6 = 30

30 + 3 - 1 = 32
```

Another:

```php
echo 5 * (6 + 3) - 1;
```

Output:

```text
44
```

Another:

```php
echo (5 * 6) + 3 - 1;
```

Output:

```text
32
```

Another:

```php
echo 5 ** 2 * 6 + 3 - 1;
```

Output:

```text
152
```

Because:

```text
5² = 25

25 × 6 = 150

150 + 3 - 1 = 152
```

Another:

```php
echo 5 ** 2 * (6 + 3) - 1;
```

Output:

```text
224
```

## Exercise

Predict the output:

```php
echo 10 + 5 * 2;
```

<details>
<summary>Answer</summary>

```text
20
```

</details>

Now predict:

```php
echo (10 + 5) * 2;
```

<details>
<summary>Answer</summary>

```text
30
```

</details>

---

# Writing PHP and HTML Together

PHP is especially useful because it can be mixed with HTML.

Example:

```php
<?php

$name = "Rafat";
$age = 25;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
</head>

<body>

    <h1>Student Profile</h1>

    <p>Name: <?php echo $name; ?></p>
    <p>Age: <?php echo $age; ?></p>

</body>
</html>
```

PHP runs on the server.

The browser receives the resulting HTML.

Think:

```text
HTML
  ↓
<?php
  ↓
PHP
  ↓
?>
  ↓
HTML
```

You can move back and forth between PHP and HTML.

---

# Short Echo Syntax `<?= ?>`

When you want to output a PHP value inside HTML, PHP provides a shorter syntax.

Instead of:

```php
<?php echo $name; ?>
```

you can write:

```php
<?= $name ?>
```

These are used for the same basic purpose.

Example:

```php
<?php

$name = "Rafat";
$age = 25;

?>

<h1>Student Profile</h1>

<p>Name: <?= $name ?></p>
<p>Age: <?= $age ?></p>
```

Think:

```text
<?= something ?>
```

as:

> Output this value here.

## Example

```php
<?php

$course = "PHP Basics";
$score = 90;

?>

<h1><?= $course ?></h1>

<p>Score: <?= $score ?></p>
```

Browser output:

```text
PHP Basics

Score: 90
```

---

# `phpinfo()`

PHP includes a useful function:

```php
phpinfo();
```

Example:

```php
<?php

phpinfo();
```

It displays information about the PHP environment, including things such as:

- PHP version
- Server configuration
- Loaded PHP extensions
- PHP configuration settings
- Environment information

This is useful when developing or troubleshooting PHP.

Because `phpinfo()` can reveal detailed information about the server, it should generally not be left publicly accessible on a production website.

---

# Putting Everything Together

Here is a small example using several concepts from this lesson:

```php
<?php

// Student information
$name = "Rafat";
$program = "DMIT";

// Assignment grades
$assignment1 = 80;
$assignment2 = 90;

// Calculate the average
$average = ($assignment1 + $assignment2) / 2;

?>

<h1>Student Report</h1>

<p>Name: <?= $name ?></p>
<p>Program: <?= $program ?></p>

<p>Assignment 1: <?= $assignment1 ?></p>
<p>Assignment 2: <?= $assignment2 ?></p>

<p>Average: <?= $average ?></p>
```

The average calculation is:

```text
80 + 90
   ↓
170
   ↓
170 / 2
   ↓
85
```

So:

```php
$average
```

contains:

```text
85
```

---

# Final Practice Exercise

Create a PHP page for a student's textbook purchase.

Store the following values:

```text
Student name
Course name
Textbook price
Quantity
Tax rate
```

Then calculate:

```text
subtotal = price × quantity

tax = subtotal × tax rate

total = subtotal + tax
```

The page should display something similar to:

```text
Student Purchase

Name: Rafat
Course: PHP Basics
Price: $50
Quantity: 2
Subtotal: $100
Tax: $5
Total: $105
```

