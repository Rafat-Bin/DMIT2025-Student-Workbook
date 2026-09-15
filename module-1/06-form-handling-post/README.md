# PHP Form Handling — POST Method

This lesson covers the basics of creating an HTML form, sending data using POST, and processing that data with PHP.

---

# Part 1 — What is a Form?

A form is used to **collect information from the user**.

For example:

- Name
- Email
- Password
- Message

## Example

```html
<!DOCTYPE html>
<html>
<body>

<form>

    <label>Name:</label>
    <input type="text">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```



---

# Part 2 — `action`

The `action` decides **WHERE the form goes** when the user clicks Submit.

We can use two PHP files:

```text
index.php
process.php
```

## `index.php`

```html
<!DOCTYPE html>
<html>
<body>

<form action="process.php">

    <button type="submit">Go</button>

</form>

</body>
</html>
```

## `process.php`

```html
<!DOCTYPE html>
<html>
<body>

<h1>You reached process.php!</h1>

</body>
</html>
```



The flow is:

```text
index.php
    ↓
User clicks Submit
    ↓
action="process.php"
    ↓
process.php
```

## Remember

**`action` = WHERE the form goes.**

---

# Part 3 — `method="POST"`

The `method` decides **HOW the form data is sent**.

There are two main methods:

```text
GET
POST
```

For now, we are learning **POST**.

## Example — `index.php`

```html
<!DOCTYPE html>
<html>
<body>

<form action="process.php" method="POST">

    <label>Name:</label>
    <input type="text">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

The important new part is:

```html
method="POST"
```

When the form is submitted:

```text
index.php
    ↓
Data sent using POST
    ↓
process.php
```

POST sends the form data in the **request body**, so the submitted data is not directly displayed in the URL.

## Remember

```text
action = WHERE the form goes

method = HOW the data is sent
```

---

# Part 4 — Input `name`

PHP needs a way to identify each input.

We do this using the `name` attribute.

## Example — `index.php`

```html
<!DOCTYPE html>
<html>
<body>

<form action="process.php" method="POST">

    <label>Name:</label>

    <input type="text" name="username">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

Suppose the user types:

```text
John
```

You can think of the submitted information as:

```text
username → John
```




## Remember

**`name` gives the input a key PHP can use to identify its submitted value.**

---

# Part 5 — `$_POST`

When a form uses POST, PHP gives us the submitted data through:

```php
$_POST
```

`$_POST` is a special PHP associative array.

Let's use two files.

## `index.php`

```html
<!DOCTYPE html>
<html>
<body>

<form action="process.php" method="POST">

    <label>Name:</label>

    <input type="text" name="username">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

## `process.php`

```php
<?php

$username = $_POST["username"];

echo "Hello $username";

?>
```

## Try It

Enter:

```text
John
```

Click Submit.

You should see:

```text
Hello John
```

## How Does It Work?


```text
name="username"
       ↓
User enters John
       ↓
$_POST["username"]
       ↓
John
```

Then:

```php
$username = $_POST["username"];
```

stores `John` inside our `$username` variable.

## Remember

**`$_POST` contains data submitted using POST.**

---

# Part 6 — Checking if POST Happened

Before processing the form, we can check whether the page actually received a POST request.

We use:

```php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

}
```

## Complete Example

### `index.php`

```html
<!DOCTYPE html>
<html>
<body>

<form action="process.php" method="POST">

    <label>Name:</label>

    <input type="text" name="username">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

### `process.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];

    echo "Hello $username";
}

?>
```

Enter:

```text
John
```

and submit.

Output:

```text
Hello John
```

## What Is the `if` Checking?

It asks:

```text
Was this page requested using POST?
            ↓
           YES
            ↓
Process the form data
```

If someone opens `process.php` without submitting the form:

```text
Open process.php directly
          ↓
Was it POST?
          ↓
         NO
          ↓
Don't process the form data
```

This check **does NOT check whether the user typed something**.

It only checks whether a POST request happened.

## Remember

**`REQUEST_METHOD == "POST"` checks whether the page received a POST request.**

---

# Part 7 — `$_SERVER`

`$_SERVER` is another special associative array PHP automatically provides.

It contains information about the **server and current request**.

For this lesson, an important part is:

```php
$_SERVER["REQUEST_METHOD"]
```

## Example

Create `index.php`:

```php
<?php

echo $_SERVER["REQUEST_METHOD"];

?>
```

Normally opening the page should display:

```text
GET
```

Now change it to:

```php
<?php

echo $_SERVER["REQUEST_METHOD"];

?>

<form method="POST">

    <button type="submit">Submit</button>

</form>
```

When you first open the page:

```text
GET

[Submit]
```

Click Submit.

Now you should see:

```text
POST

[Submit]
```

That means:

```text
$_SERVER["REQUEST_METHOD"]
            ↓
How was this page requested?
```


## Superglobal

`$_SERVER` and `$_POST` are called **superglobals**.



## Remember

**`$_SERVER` contains information about the server/current request.**

---

# Part 8 — Same Page vs Separate Processing Page

There are two ways we can process a form.

---

## Option 1 — Separate PHP Files

We can have:

```text
index.php
process.php
```

`index.php` shows the form.

`process.php` processes the form.

The flow is:

```text
index.php
    ↓
Show form
    ↓
User submits
    ↓
process.php
    ↓
Process submitted data
```

So:

```text
index.php
    ↓
Collect data


process.php
    ↓
Process data
```

---

## Option 2 — Same PHP File

One PHP file can **show the form AND process the form**.

## Complete Example — `index.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];

    echo "Hello $username";
}

?>

<!DOCTYPE html>
<html>
<body>

<form action="index.php" method="POST">

    <label>Name:</label>

    <input type="text" name="username">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

When you first open `index.php`:

```text
Name: [___________] [Submit]
```

Enter:

```text
John
```

and submit.

You should see:

```text
Hello John

Name: [___________] [Submit]
```

The flow is:

```text
index.php
    ↓
Shows form
    ↓
User enters John
    ↓
Submit
    ↓
POST back to index.php
    ↓
Check REQUEST_METHOD
    ↓
Get username
    ↓
Print Hello John
```

## Remember

```text
Two files
    ↓
Form page + processing page


One file
    ↓
Form + processing together
```

---

# Part 9 — `PHP_SELF`

When processing everything on the same page, we can tell the form:

> Submit back to the current PHP page.

PHP gives us:

```php
$_SERVER["PHP_SELF"]
```

`PHP_SELF` refers to the current PHP page.

## Complete Example — `index.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];

    echo "Hello $username";
}

?>

<!DOCTYPE html>
<html>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <label>Name:</label>

    <input type="text" name="username">

    <button type="submit">Submit</button>

</form>

</body>
</html>
```

If the current page is:

```text
index.php
```

then `PHP_SELF` refers to that current page.



---

# Part 10 — `isset()`

`isset()` checks whether something **exists**.

For example:

```php
isset($_POST["username"])
```

asks:

> Does `username` exist in the POST data?

We can also give our submit button a `name` and check whether it exists.

# Part 11 — Retaining Form Values

After submitting a form, the form values normally disappear.

We can keep the user's values after submitting.

This is called a **sticky form**.

---

## Text Input

First, store the submitted value:

```php
$username = isset($_POST["username"])
    ? $_POST["username"]
    : "";
```

This means:

```text
If username exists → use its value
Otherwise          → use ""
```

Then put the value back into the input:

```php
<input
    type="text"
    name="username"
    value="<?= $username ?>"
>
```

If the user enters:

```text
John
```

After submitting:

```text
Name: [John]
```

The value stays in the input.

---

## Complete Text Input Example

```php
<?php

$username = isset($_POST["username"])
    ? $_POST["username"]
    : "";

if (isset($_POST["submit"])) {
    echo "Hello $username";
}

?>

<!DOCTYPE html>
<html>
<body>

<form method="POST">

    <label>Name:</label>

    <input
        type="text"
        name="username"
        value="<?= $username ?>"
    >

    <button type="submit" name="submit">
        Submit
    </button>

</form>

</body>
</html>
```

After entering `John` and clicking Submit:

```text
Hello John

Name: [John] [Submit]
```

---

## Select

For a `<select>`, use `selected` to keep an option selected.

```php
$weather = isset($_POST["weather"])
    ? $_POST["weather"]
    : "";
```

Then:

```php
<select name="weather">

    <option
        value="sunny"
        <?php if ($weather == "sunny") echo "selected"; ?>
    >
        Sunny
    </option>

    <option
        value="rainy"
        <?php if ($weather == "rainy") echo "selected"; ?>
    >
        Rainy
    </option>

</select>
```

---

## Radio Buttons

Radio buttons use `checked`.

```php
$role = isset($_POST["role"])
    ? $_POST["role"]
    : "";
```

Then:

```php
<input
    type="radio"
    name="role"
    value="hero"
    <?php if ($role == "hero") echo "checked"; ?>
>
Hero

<input
    type="radio"
    name="role"
    value="villain"
    <?php if ($role == "villain") echo "checked"; ?>
>
Villain
```

---

## Checkboxes

Checkboxes also use `checked`.

```php
$items = isset($_POST["items"])
    ? $_POST["items"]
    : [];
```

Then:

```php
<input
    type="checkbox"
    name="items[]"
    value="map"
    <?php if (in_array("map", $items)) echo "checked"; ?>
>
Map

<input
    type="checkbox"
    name="items[]"
    value="sword"
    <?php if (in_array("sword", $items)) echo "checked"; ?>
>
Sword
```

---

## Remember

```text
Text Input → value
Select     → selected
Radio      → checked
Checkbox   → checked
```

---


## Today's Problems

Just like our last round of exercises, these will be algorithmic (i.e. they will follow a series of logical steps); however, instead of 'hard-coding' our values, we will write these problems to take form data from the user and do something with it. 

---

### Problem 1: Even or Odd

Write a program that takes a numerical value from the user and determines whether the number is even or odd.

---

### Problem 2: Temperature Converter

Write a program that converts a temperature from Celsius to Fahrenheit or Fahrenheit to Celsius based on user input.

---

### Problem 3: Vowel Counter

Write a program that takes a string input from the user and counts the number of vowels (a, e, i, o, u) in it.


#### Bonus Question

Some English words do not have any of the standard vowels (a, e, i, o, u), but do have a 'y' (ex. any, why, my, sky ...).

Extend your program so that it looks for each word that contains no standard vowel, counts every 'y' in that word, and prints the total number vowels with 'y' words added. 
