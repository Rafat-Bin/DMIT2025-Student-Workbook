# PHP Basics

PHP is a programming language used to add back-end functionality to websites and web applications.

It often works alongside technologies such as:

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

The server processes the PHP and sends the resulting HTML to the browser:

```html
<h1>Hello Rafat</h1>
```

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
- Authentication

---

# Getting Started

## PHP Needs a Server

A web browser does not execute PHP source code by itself.

PHP requires a PHP-enabled server or development environment.

## Use the `.php` File Extension

PHP files normally end with:

```text
.php
```

Examples:

```text
index.php
about.php
students.php
```

A `.html` file is normally treated as HTML rather than processed as PHP.



# Running PHP Code

We will write PHP code in **Visual Studio Code** and view the result in a web browser.

Because PHP runs on a server, we do not normally open a `.php` file directly in the browser.

For this course, **Docker** provides the local server that runs our PHP code.

## 1. Open the Project in VS Code

Open the project folder in Visual Studio Code.

For example, we may work with a PHP file such as:

```text
demo/basic.php
```

Write some PHP:

```php
<?php

echo "Hello PHP";
```

Save the file.

## 2. Make Sure Docker Is Running

Open **Docker Desktop** and make sure Docker is running in the background.

Docker provides the local server that processes our PHP code.

```text
VS Code
   ↓
PHP file
   ↓
Docker
   ↓
Local server
   ↓
Browser
```

## 3. Open the PHP File in the Browser

Open your browser and go to:

```text
http://localhost:8080/DMIT2025-Student-Workbook-main/demo/basic.php
```

Breaking down the URL:

```text
http://localhost:8080/
        ↓
Local server running on port 8080

DMIT2025-Student-Workbook-main/
        ↓
Project folder

demo/
        ↓
Folder containing our example

basic.php
        ↓
PHP file being run
```

If `basic.php` contains:

```php
<?php

$name = "Rafat";

echo "Hello $name";
```

the browser will display:

```text
Hello Rafat
```

## 4. Edit, Save, and Refresh

The normal workflow is:

```text
Write code in VS Code
        ↓
Save the PHP file
        ↓
Open or refresh the page in the browser
        ↓
See the result
```

After changing your PHP code:

1. Save the file in VS Code.
2. Return to the browser.
3. Refresh the page.
4. View the updated result.




## PHP Tags

PHP code begins with:

```php
<?php
```

Example:

```php
<?php

echo "Hello";
```

PHP mode can be closed with:

```php
?>
```

Think of it like this:

```text
<?php  → enter PHP mode
?>     → leave PHP mode
```



There must be **no space** between `?` and `php`.

## Do We Always Need `?>`?

No.

If a file contains only PHP, the closing tag is commonly omitted.

```php
<?php

echo "Hello";
```

When PHP and HTML are mixed together, the closing tag is useful.

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
```

<details>
<summary>Answer</summary>

The opening PHP tag is incorrect.

```php
<?php

echo "Hello";
```

</details>

---

# `echo` — Outputting Information

`echo` is used to output information.

```php
echo "Hello World";
```

Output:

```text
Hello World
```

You can also output numbers:

```php
echo 25;
```

Output:

```text
25
```

## Strings Need Quotes

Text values are called **strings** and are written inside quotation marks.

```php
echo "Hello";
echo 'Hello';
```

Numbers normally do not need quotation marks.

```php
echo 25;
```

Compare:

```php
echo 25;    // number
echo "25";  // string
```

## Multiple Values With `echo`

`echo` can output multiple values separated by commas.

```php
$name = "Rafat";

echo "Hello ", $name;
```

Output:

```text
Hello Rafat
```

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


# Variables

Variables store values that can be used later.

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

You can output the stored value:

```php
echo $name;
```

Output:

```text
Rafat
```


```

---

# PHP Variable Rules

PHP variables begin with `$`.

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

Variable names are also case-sensitive.

```php
$name = "Rafat";
$Name = "Jack";
```

These are two different variables.

## Camel Case

A common naming style is **camelCase**.

```php
$firstName = "Rafat";
$lastName = "Bin";
$studentAge = 25;
$courseName = "PHP Basics";
```

---

# Variables Can Change

Variables can store new values.

```php
$age = 20;

$age = 21;

echo $age;
```

Output:

```text
21
```

The newer value replaces the older value.

## Using the Existing Value

A variable's existing value can be used to calculate a new value.

```php
$age = 20;

$age = $age + 1;

echo $age;
```

Output:

```text
21
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

# Data Types in PHP

Different kinds of values have different data types.

The main types in this lesson are:

| Data Type | Meaning | Example |
| --- | --- | --- |
| String | Text | `"Rafat"` |
| Integer | Whole number | `25` |
| Float | Decimal number | `19.99` |
| Boolean | `true` or `false` | `true` |
| Array | Multiple values | `["A", "B"]` |

## Strings

Strings represent text.

```php
$name = "Rafat";
$city = "Edmonton";
```

## Integers

Integers are whole numbers.

```php
$age = 25;
$temperature = -10;
```

## Floats

Floats contain decimal values.

```php
$price = 19.99;
$average = 87.25;
```

## Booleans

A Boolean has two possible values:

```php
true
false
```

Example:

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

## PHP Determines Data Types

PHP normally determines the data type from the value.

```php
$name = "Rafat";     // string
$age = 25;           // integer
$price = 19.99;      // float
$isStudent = true;   // boolean
```

---

# `var_dump()`

`var_dump()` can be used to inspect a value and its data type.

```php
$name = "Rafat";
$age = 25;
$price = 19.99;
$isStudent = true;

var_dump($name);
var_dump($age);
var_dump($price);
var_dump($isStudent);
```

The output will look similar to:

```text
string(5) "Rafat"
int(25)
float(19.99)
bool(true)
```

For strings, `var_dump()` also shows the number of characters.

## Exercise

What type do you expect here?

```php
$price = 15.99;

var_dump($price);
```

<details>
<summary>Answer</summary>

```text
float
```

</details>

---

# Strings and Quotes

PHP supports both double and single quotes.

## Double Quotes

Variables can be interpreted inside double-quoted strings.

```php
$name = "Rafat";

echo "Hello $name";
```

Output:

```text
Hello Rafat
```

## Single Quotes

Variables are generally treated literally inside single quotes.

```php
echo 'Hello $name';
```

Output:

```text
Hello $name
```

For now, remember:

```text
" " → variables can be interpreted
' ' → generally treated literally
```

---

# String Concatenation `.`

Concatenation means joining values together as strings.

PHP uses a period `.`.

```php
$firstName = "Rafat";
$lastName = "Bin";

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

With `echo`, commas can also be used to output multiple values:

```php
echo "My name is ", $name;
```

Output:

```text
My name is Rafat
```

The difference is:

```text
.  → joins values together
,  → gives echo multiple values to output
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

Using concatenation:

```php
echo $food . " costs $" . $price;
```

Or with commas:

```php
echo $food, " costs $", $price;
```

</details>

---

# Formatting Output

`echo` does not automatically create visible line breaks.

```php
echo "Rafat";
echo "Hello";
echo "PHP";
```

Browser output:

```text
RafatHelloPHP
```

## Using `<br>`

Because PHP can generate HTML, `<br>` can create a visible browser line break.

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

You can also use it with multiple values:

```php
$name = "Rafat";
$age = 25;

echo "Name: ", $name, "<br>";
echo "Age: ", $age, "<br>";
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

In normal HTML, `\n` usually does not create a visible browser line break.

## Using HTML Elements

For webpage content, HTML elements are often better than using many `<br>` tags.

```php
echo "<p>Hello</p>";
echo "<p>Welcome to PHP</p>";
```

---

# Comments

Comments are notes inside code that PHP ignores when the program runs.

## Single-Line Comments

Use `//`.

```php
// Store the student's name
$name = "Rafat";
```

Comments can also appear after a statement:

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
Calculate the student's
final score.
*/

$score = 85;
```


---

# Basic Arithmetic

PHP supports standard arithmetic operators.

| Operation | Operator |
| --- | --- |
| Addition | `+` |
| Subtraction | `-` |
| Multiplication | `*` |
| Division | `/` |
| Modulus | `%` |
| Exponentiation | `**` |

## Arithmetic With Variables

```php
$a = 10;
$b = 5;

echo $a + $b;  // 15
echo "<br>";

echo $a - $b;  // 5
echo "<br>";

echo $a * $b;  // 50
echo "<br>";

echo $a / $b;  // 2
```

A result can also be stored in another variable:

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

# Modulus `%`

The modulus operator gives the **remainder after division**.

```php
echo 10 % 3;
```

Think:

```text
10 ÷ 3 = 3 remainder 1
```

Output:

```text
1
```



---

# Exponentiation `**`

Exponentiation means "to the power of."

```php
echo 5 ** 2;
```

This means:

```text
5 × 5 = 25
```

Output:

```text
25
```



---

# Order of Operations

PHP follows the normal mathematical order of operations.

```text
1. Parentheses
2. Exponents
3. Multiplication and Division
4. Addition and Subtraction
```

For example:

```php
echo 5 + 2 * 3;
```

Multiplication happens first:

```text
5 + 6
= 11
```

Output:

```text
11
```

Compare:

```php
echo (5 + 2) * 3;
```

Parentheses happen first:

```text
7 × 3
= 21
```

Output:

```text
21
```


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
define("TAX_RATE", 0.05);
```

Constants do not use `$`.

```php
echo TAX_RATE;
```

Example:

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

## Exercise

What is wrong here?

```php
define("PRICE", 50);

echo $PRICE;
```

<details>
<summary>Answer</summary>

Constants do not use `$`.

Correct:

```php
echo PRICE;
```

</details>

---


# Arrays

An array allows one variable to store multiple values.

```php
$students = ["Rafat", "Jack", "Sarah"];
```

Each value has an index:

```text
0 → Rafat
1 → Jack
2 → Sarah
```

Array indexing begins at `0`.

```php
echo $students[0];
```

Output:

```text
Rafat
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

Associative arrays use named keys.

```php
$student = [
    "name" => "Rafat",
    "age" => 25,
    "program" => "DMIT"
];
```

Access a value using its key:

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

Arrays can be explored in more detail later.

---

# Writing PHP and HTML Together

PHP can be mixed with HTML.

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

PHP runs on the server and the browser receives the resulting HTML.

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

When outputting a PHP value inside HTML, PHP provides a shorter syntax.

Instead of:

```php
<?php echo $name; ?>
```

you can write:

```php
<?= $name ?>
```

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

Think of:

```php
<?= $name ?>
```

as:

> Output the value of `$name` here.

---

# `phpinfo()`

PHP includes a function called:

```php
phpinfo();
```

Example:

```php
<?php

phpinfo();
```

It displays information about the PHP environment, including:

- PHP version
- Server configuration
- Loaded extensions
- PHP settings

It is useful for development and troubleshooting.

Because `phpinfo()` can reveal server information, it should not normally be left publicly accessible on a production website.

---

# Putting Everything Together

Here is a small example using several concepts from this lesson:

```php
<?php

$name = "Rafat";
$program = "DMIT";

$assignment1 = 80;
$assignment2 = 90;

$average = ($assignment1 + $assignment2) / 2;

?>

<h1>Student Report</h1>

<p>Name: <?= $name ?></p>
<p>Program: <?= $program ?></p>

<p>Assignment 1: <?= $assignment1 ?></p>
<p>Assignment 2: <?= $assignment2 ?></p>

<p>Average: <?= $average ?></p>
```

The calculation is:

```text
80 + 90 = 170

170 / 2 = 85
```

So the output includes:

```text
Average: 85
```

---

# Final Practice Exercise

Create a PHP page for a student's textbook purchase.

Store:

```text
Student name
Course name
Textbook price
Quantity
Tax rate
```

Calculate:

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

