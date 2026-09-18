# PHP Form Handling — GET Method & Query Strings

We already learned how to handle forms using **POST**.

GET works very similarly.

The main difference is **where the submitted data is sent**:

```text
POST → request body → $_POST

GET  → URL          → $_GET
```

For example, if a user enters:

```text
John
```

POST sends the value in the request body:

```text
index.php
```

and PHP accesses it with:

```php
$_POST["username"]
```

GET puts the value in the URL:

```text
index.php?username=John
```

and PHP accesses it with:

```php
$_GET["username"]
```

---

# Part 1 — Using GET in a Form

We already know that `method` controls **how form data is sent**.

For GET, use:

```html
<form method="GET">
```

Example:

```html
<form method="GET">

    <label>Name:</label>

    <input
        type="text"
        name="username"
    >

    <button type="submit">
        Submit
    </button>

</form>
```

If the user enters:

```text
John
```

and submits the form, the URL becomes:

```text
index.php?username=John
```

The browser uses the input's:

```text
name="username"
```

and the entered value:

```text
John
```

to create:

```text
username=John
```

Then GET places it in the URL:

```text
index.php?username=John
```

---

# Part 2 — Query Strings

The information after `?` in a URL is called a **query string**.

Example:

```text
index.php?username=John
```

Breakdown:

```text
index.php ? username = John
   ↓      ↓     ↓       ↓
  page   start  name    value
```

A query string can contain multiple parameters:

```text
index.php?name=John&city=Edmonton
```

This contains:

```text
name → John

city → Edmonton
```

The important symbols are:

```text
? → starts the query string

= → connects a name and value

& → separates multiple parameters
```

---

## URL Encoding

Some characters must be encoded when placed inside a URL.

For example, a space may appear as:

```text
%20 or +
```

So:

```text
puppy dogs
```

may appear as:

```text
puppy%20dogs or puppy+dogs
```

Example:

```text
page.php?searchterm=puppy%20dogs
```

For now, you mainly need to recognize that `%20` or `+` represents an encoded space.

---

# Part 3 — Accessing GET Data with `$_GET`

Just like POST gives us:

```php
$_POST
```

GET gives us:

```php
$_GET
```

For this URL:

```text
index.php?username=John
```

PHP can access the value with:

```php
$_GET["username"]
```

which gives:

```text
John
```

Before using a GET value, we can check whether it exists:

```php
if (isset($_GET["username"])) {

    $username = $_GET["username"];

}
```

The basic flow is:

```text
name="username"
       ↓
User enters John
       ↓
GET
       ↓
?username=John
       ↓
$_GET["username"]
       ↓
John
```

---

# Part 4 — Named Submit Buttons

Just like with POST, a submit button can have a `name`.

Example:

```html
<button
    type="submit"
    name="search"
    value="submit"
>
    Search
</button>
```

Suppose the form also contains:

```html
<input
    type="text"
    name="searchterm"
>
```

If the user searches for:

```text
puppy dogs
```

the URL may become:

```text
index.php?searchterm=puppy%20dogs&search=submit
```

PHP can check whether the submit button exists:

```php
if (isset($_GET["search"])) {

    // Process the search

}
```

This works the same way as:

```php
isset($_POST["something"])
```

from POST forms.

The difference is that we're now checking the `$_GET` array.

---

# Part 5 — GET URLs Can Be Changed and Shared

A major difference between GET and POST is that GET values are visible in the URL.

For example:

```text
page.php?city=Edmonton
```

A user can manually change it to:

```text
page.php?city=Calgary
```

PHP would then receive:

```php
$_GET["city"]
```

as:

```text
Calgary
```

This also means GET URLs can be:

- Copied
- Shared
- Bookmarked
- Changed

We can even create GET URLs ourselves using links:

```html
<a href="page.php?city=Edmonton">
    Edmonton
</a>

<a href="page.php?city=Calgary">
    Calgary
</a>
```

Then `page.php` could contain:

```php
<?php

if (isset($_GET["city"])) {

    $city = $_GET["city"];

    echo "You selected $city";
}

?>
```

Clicking:

```text
Edmonton
```

opens:

```text
page.php?city=Edmonton
```

and PHP receives:

```php
$_GET["city"]
```

with the value:

```text
Edmonton
```

So GET parameters can come from:

```text
Forms

Links

Manually edited URLs
```

```text
index.php

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Choose a City</title>
</head>

<body>

    <h1>Choose a City</h1>

    <a href="page.php?city=Edmonton&population=1000000">
        Edmonton
    </a>

    <br>

    <a href="page.php?city=Calgary&population=900000">
        Calgary
    </a>

    <br>

    <a href="page.php?city=Montreal&population=4000000">
        Montreal
    </a>

    <br>

    <a href="page.php?city=Toronto&population=7000000">
        Toronto
    </a>

</body>

</html>
```

```text
page.php

<?php

if (
    isset($_GET["city"]) &&
    isset($_GET["population"])
) {

    $city = $_GET["city"];
    $population = $_GET["population"];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>City Information</title>
</head>

<body>

    <?php

    if (
        isset($_GET["city"]) &&
        isset($_GET["population"])
    ) {

        echo "<h1>$city</h1>";
        echo "<p>Population: $population</p>";

    }

    ?>

    <a href="index.php">
        Back
    </a>

</body>

</html>
```
---

## Important — GET Values Can Be Manipulated

Because GET values appear in the URL, users can change them.

For example:

```text
page.php?city=Edmonton
```

can easily become:

```text
page.php?city=Calgary
```

Therefore, never assume that a GET value is trustworthy just because it originally came from your form.

Sensitive information should also not be placed in a GET query string because it is visible in the URL.

POST values are also user input and must be validated.

---

# Part 6 — Initializing GET Variables

We learned with POST that variables should be initialized before we use them.

The same idea applies to GET.

For example:

```php
if (isset($_GET["job"])) {

    $job = $_GET["job"];

} else {

    $job = "Placeholder";
}
```

This means:

```text
Does $_GET["job"] exist?

YES → use the GET value

NO  → use "Placeholder"
```

For example:

```text
index.php
```

gives:

```text
$job = "Placeholder"
```

But:

```text
index.php?job=Developer
```

gives:

```text
$job = "Developer"
```

---

# Part 7 — Ternary Operator

The previous code works, but it takes several lines:

```php
if (isset($_GET["job"])) {

    $job = $_GET["job"];

} else {

    $job = "Placeholder";
}
```

A **ternary operator** is a shorter way to write this type of conditional assignment:

```php
$job = isset($_GET["job"])
    ? $_GET["job"]
    : "Placeholder";
```

The pattern is:

```text
condition ? value if true : value if false
```

So:

```php
$job = isset($_GET["job"])
    ? $_GET["job"]
    : "Placeholder";
```

means:

```text
Does job exist?

YES → $_GET["job"]

NO  → "Placeholder"
```

This becomes useful when initializing several variables:

```php
$name = isset($_GET["name"])
    ? $_GET["name"]
    : "";

$city = isset($_GET["city"])
    ? $_GET["city"]
    : "";
```

---

# Part 8 — GET Examples

Now that we understand the GET method, let's look at some examples that combine what we learned.

---

## Example 1 — Basic GET Form

This example gets a user's first and last name.

Create:

```text
index.php
```

Add the following code:

```php
<?php

if (isset($_GET["register_submit"])) {

    $first_name = $_GET["first_name"];
    $last_name = $_GET["last_name"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GET Form Example</title>
</head>

<body>

    <h1>Registration Form</h1>

    <form method="GET">

        <label>First Name:</label>

        <input
            type="text"
            name="first_name"
        >

        <br><br>

        <label>Last Name:</label>

        <input
            type="text"
            name="last_name"
        >

        <br><br>

        <input
            type="submit"
            name="register_submit"
            value="Register Me!"
        >

    </form>

    <?php

    if (isset($_GET["register_submit"])) {

        echo "<h2>Welcome $first_name $last_name</h2>";
    }

    ?>

</body>

</html>
```

If the user enters:

```text
First Name: John
Last Name: Smith
```

and clicks **Register Me!**, the URL will look similar to:

```text
index.php?first_name=John&last_name=Smith&register_submit=Register+Me%21
```

PHP receives:

```text
$_GET["first_name"] → John
$_GET["last_name"]  → Smith
```

The variables become:

```text
$first_name → John
$last_name  → Smith
```

and the page displays:

```text
Welcome John Smith
```

The flow is:

```text
User enters values
        ↓
Clicks Register Me!
        ↓
GET creates query string
        ↓
?first_name=John&last_name=Smith...
        ↓
$_GET
        ↓
$first_name = "John"
$last_name = "Smith"
        ↓
Welcome John Smith
```

---

## Example 2 — Keeping GET Form Values

The same techniques we learned for retaining form values can also be used with GET.

Create:

```text
index.php
```

Add the following code:

```php
<?php

$initial = isset($_GET["initial"])
    ? $_GET["initial"]
    : "";

$type = isset($_GET["type"])
    ? $_GET["type"]
    : "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GET Sticky Form</title>
</head>

<body>

    <h1>Temperature Converter</h1>

    <form method="GET">

        <label>Temperature:</label>

        <input
            type="text"
            name="initial"
            value="<?= $initial; ?>"
        >

        <br><br>

        <label>Conversion:</label>

        <select name="type">

            <option value="">
                Select an option
            </option>

            <option
                value="c_to_f"
                <?php if ($type == "c_to_f") echo "selected"; ?>
            >
                Celsius to Fahrenheit
            </option>

            <option
                value="f_to_c"
                <?php if ($type == "f_to_c") echo "selected"; ?>
            >
                Fahrenheit to Celsius
            </option>

        </select>

        <br><br>

        <button
            type="submit"
            name="convert"
        >
            Convert
        </button>

    </form>

    <?php

    if (isset($_GET["convert"])) {

        echo "<h2>You entered: $initial</h2>";
        echo "<p>Conversion selected: $type</p>";
    }

    ?>

</body>

</html>
```

For example, enter:

```text
Temperature: 25

Conversion:
Celsius to Fahrenheit
```

After submitting, the URL becomes something like:

```text
index.php?initial=25&type=c_to_f&convert=
```

PHP receives:

```text
$_GET["initial"] → 25
$_GET["type"]    → c_to_f
```

The variables become:

```text
$initial → 25
$type    → c_to_f
```

The important part is that these values are placed **back into the form**.

For the text input:

```php
value="<?= $initial; ?>"
```

If:

```text
$initial = 25
```

then the input effectively becomes:

```html
<input
    type="text"
    name="initial"
    value="25"
>
```

So `25` stays inside the text box.

For the select:

```php
<?php if ($type == "c_to_f") echo "selected"; ?>
```

If:

```text
$type = c_to_f
```

PHP outputs:

```html
selected
```

So **Celsius to Fahrenheit** stays selected.

The flow is:

```text
User enters 25
      +
Selects Celsius to Fahrenheit
              ↓
           Submit
              ↓
?initial=25&type=c_to_f
              ↓
           $_GET
              ↓
$initial = "25"
$type = "c_to_f"
              ↓
Values are placed back into form
              ↓
25 stays in text input
Celsius to Fahrenheit stays selected
```

This example focuses on **keeping GET form values**.

It does not perform the actual temperature conversion yet.

---

## Example 3 — Using POST and GET Together

A page can use both `POST` and `GET`.

In this example:

- `GET` gets the page category from the URL.
- `POST` gets the username from a form.

Create:

```text
index.php
```

Add the following code:

```php
<?php

$category = isset($_GET["category"])
    ? $_GET["category"]
    : "General";

$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>POST and GET Example</title>
</head>

<body>

    <h1><?= $category; ?> Page</h1>

    <a href="index.php?category=Games">
        Games
    </a>

    <br>

    <a href="index.php?category=Movies">
        Movies
    </a>

    <br><br>

    <form method="POST">

        <label>Username:</label>

        <input
            type="text"
            name="username"
        >

        <button type="submit">
            Submit
        </button>

    </form>

    <?php

    if ($username != "") {

        echo "<p>Hello $username!</p>";
    }

    ?>

</body>

</html>
```

If the user clicks:

```text
Games
```

the URL becomes:

```text
index.php?category=Games
```

PHP gets the category using:

```php
$_GET["category"]
```

So:

```text
$category → Games
```

If the user then enters:

```text
John
```

into the form and submits it, PHP gets the username using:

```php
$_POST["username"]
```

So:

```text
$username → John
```

The main idea is:

```text
URL
?category=Games
      ↓
    $_GET
      ↓
$category = "Games"


Form
username = John
      ↓
     POST
      ↓
   $_POST
      ↓
$username = "John"
```

So the same PHP page can work with both:

```text
GET  → $_GET  → values from the URL

POST → $_POST → values from the request body
```

They are separate:

```php
$_GET["category"]
$_POST["username"]
```

`$_GET` does not read the POST value, and `$_POST` does not read the GET value.
---

# Today's Problems

### Problem 1: Age Calculator

Ask the user for their birth year.

Use GET to calculate and display their approximate age.

Example:

```text
Birth Year: 2000

You are approximately 26 years old.
```

---

### Problem 2: Tip Calculator

Ask the user for:

- Bill amount
- Tip percentage

Use GET to calculate the tip and total bill.

Example:

```text
Bill: $50
Tip: 15%

Tip Amount: $7.50
Total: $57.50
```

---

### Problem 3: Username Creator

Ask the user for their first name and favourite number.

Combine them to create a username.

Example:

```text
First Name: Alex
Favourite Number: 24

Username: Alex24
```

---

### Bonus Question: Simple Calculator

Create a GET form that asks for:

- First number
- Second number
- Operation (`+`, `-`, `*`, `/`)

Calculate and display the result.

Example:

```text
10 × 5 = 50
```
