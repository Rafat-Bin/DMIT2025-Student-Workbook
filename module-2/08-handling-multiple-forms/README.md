
# Lesson 08 — Handling Multiple Forms

In previous lessons, we worked with forms that send data using `GET` and `POST`.

Now we are going to take that further and work with **multiple forms, retaining values, and passing information between form submissions**.

By the end of this lesson, you will be able to:

- Work with more than one form on a page
- Determine which form was submitted
- Use hidden form inputs
- Carry information from one form to another
- Retain submitted form values
- Generate form fields using PHP
- Collect dynamically generated values

---

# 1. Multiple Forms on One Page

An HTML page can contain more than one `<form>`.

Each form is independent.

For example, a page might contain:

```text
Form 1 → Update a username

Form 2 → Send a message
```

Submitting the first form does not automatically submit the second form.

## Complete Example — `index.php`

```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Multiple Forms</title>
</head>

<body>

    <h1>Account</h1>

    <h2>Update Username</h2>

    <form action="process_username.php" method="POST">

        <label for="username">
            Username:
        </label>

        <input
            type="text"
            id="username"
            name="username"
            required
        >

        <button type="submit">
            Update Username
        </button>

    </form>


    <h2>Send Message</h2>

    <form action="process_message.php" method="POST">

        <label for="message">
            Message:
        </label>

        <input
            type="text"
            id="message"
            name="message"
            required
        >

        <button type="submit">
            Send Message
        </button>

    </form>

</body>

</html>
```

Create `process_username.php`:

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];

    echo "Username: {$username}";
}

?>
```

Create `process_message.php`:

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = $_POST["message"];

    echo "Message: {$message}";
}

?>
```

Because the forms have different `action` values, each form can be sent to a different processing file.

## Key Idea

**A page can contain multiple forms, and each form is submitted independently.**

---

# 2. Identifying Which Form Was Submitted

Sometimes multiple forms submit to the **same PHP page**.

For example:

```text
Form 1 ─┐
        ├──→ index.php
Form 2 ─┘
```

PHP may then need to determine which form was submitted.

One way to do this is with a **hidden identifier**.

## Complete Example — `index.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] == "profile"
    ) {

        $name = $_POST["name"];

        echo "<p>Profile form submitted.</p>";
        echo "<p>Name: {$name}</p>";
    }


    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] == "message"
    ) {

        $message = $_POST["message"];

        echo "<p>Message form submitted.</p>";
        echo "<p>Message: {$message}</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Identify Forms</title>
</head>

<body>

    <h1>Multiple Forms</h1>

    <h2>Profile</h2>

    <form method="POST">

        <input
            type="hidden"
            name="form_id"
            value="profile"
        >

        <label for="name">
            Name:
        </label>

        <input
            type="text"
            id="name"
            name="name"
            required
        >

        <button type="submit">
            Save Profile
        </button>

    </form>


    <h2>Message</h2>

    <form method="POST">

        <input
            type="hidden"
            name="form_id"
            value="message"
        >

        <label for="message">
            Message:
        </label>

        <input
            type="text"
            id="message"
            name="message"
            required
        >

        <button type="submit">
            Send Message
        </button>

    </form>

</body>

</html>
```

Both forms contain:

```html
<input type="hidden" name="form_id">
```

but their values are different.

The first form sends:

```text
form_id = profile
```

The second form sends:

```text
form_id = message
```

PHP can check:

```php
$_POST["form_id"]
```

to determine which form was submitted.

### Important

`form_id` is **not a special PHP keyword**.

We created that name ourselves.

We could have called it something else:

```html
<input
    type="hidden"
    name="form_name"
    value="profile"
>
```

The important idea is that each form sends something that identifies it.

## Key Idea

**When multiple forms submit to the same page, a hidden identifier can help PHP determine which form was submitted.**

---

# 3. Hidden Inputs and Carrying Values

Sometimes one form collects information that another form needs later.

For example:

```text
Form 1
   ↓
User enters a value
   ↓
PHP receives the value
   ↓
Form 2 needs the same value
```

A new form submission only sends the values belonging to the form being submitted.

If Form 2 still needs information from Form 1, we need to **carry that information forward**.

One way to do this is with a **hidden input**.

## Hidden Inputs

A hidden input uses:

```html
<input type="hidden">
```

It is part of the form, but the user does not see an editable field for it.

For example:

```html
<input
    type="hidden"
    name="user_id"
    value="25"
>
```

When the form is submitted using POST, PHP can access:

```php
$_POST["user_id"]
```

and receive:

```text
25
```

The user did not have to enter `25`.

The hidden input sends the value along with the form.

### Important

A hidden input should **not** be treated as secret or secure storage.

Its purpose here is to carry information with a form submission.

## Complete Example — `index.php`

Suppose the first form asks the user to select a category.

After the category is selected, a second form asks the user to enter an item belonging to that category.

The workflow is:

```text
FORM 1
Choose category
      ↓
GET
      ↓
PHP receives category
      ↓
FORM 2 appears
      ↓
Hidden input carries category
      ↓
POST
      ↓
PHP receives category + item
```

```php
<?php

$category = "";

if (isset($_GET["category"])) {

    $category = $_GET["category"];

} elseif (isset($_POST["category"])) {

    $category = $_POST["category"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item = $_POST["item"];

    echo "<h2>Submitted Information</h2>";
    echo "<p>Category: {$category}</p>";
    echo "<p>Item: {$item}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Carrying Values</title>
</head>

<body>

    <h1>Choose an Item</h1>

    <form method="GET">

        <label for="category">
            Category:
        </label>

        <select
            id="category"
            name="category"
        >

            <option value="Books">
                Books
            </option>

            <option value="Games">
                Games
            </option>

            <option value="Movies">
                Movies
            </option>

        </select>

        <button type="submit">
            Continue
        </button>

    </form>


    <?php if ($category != "") : ?>

        <h2>
            Enter a <?php echo $category; ?> Item
        </h2>

        <form method="POST">

            <input
                type="hidden"
                name="category"
                value="<?php echo $category; ?>"
            >

            <label for="item">
                Item:
            </label>

            <input
                type="text"
                id="item"
                name="item"
                required
            >

            <button type="submit">
                Submit
            </button>

        </form>

    <?php endif; ?>

</body>

</html>
```

The important part is:

```php
<input
    type="hidden"
    name="category"
    value="<?php echo $category; ?>"
>
```

The hidden input carries the category into the second form submission.

## Key Idea

**A hidden input can carry information from an earlier step into a later form submission.**

---

# 4. Retaining Form Values

When a form is submitted, we may want the values entered by the user to remain in the form.

For example, if a user enters:

```text
Alex
```

and the form is displayed again, we may want the input to still display:

```text
Alex
```

PHP can retrieve the submitted value and place it back into the form.

## Complete Example — `index.php`

```php
<?php

$name = "";

if (isset($_POST["name"])) {

    $name = htmlspecialchars($_POST["name"]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Retaining Form Values</title>
</head>

<body>

    <h1>Enter Your Name</h1>

    <form method="POST">

        <label for="name">
            Name:
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="<?php echo $name; ?>"
            required
        >

        <button type="submit">
            Submit
        </button>

    </form>

</body>

</html>
```

PHP first checks whether the value was submitted:

```php
if (isset($_POST["name"])) {

    $name = htmlspecialchars($_POST["name"]);
}
```

Then the value is placed back into the input:

```php
value="<?php echo $name; ?>"
```

The process is:

```text
User enters Alex
      ↓
POST
      ↓
$_POST["name"]
      ↓
$name
      ↓
value="<?php echo $name; ?>"
      ↓
Alex remains in the input
```

This is often called a **sticky form value**.

### `htmlspecialchars()`

`htmlspecialchars()` prevents HTML entered by the user from being treated as actual HTML when the value is displayed again.

For now, think of it as:

> **Show this value as text, not as HTML code.**

## Key Idea

**To retain a form value, PHP can read the submitted value and place it back into the form field.**

---

# 5. Dynamically Generating and Collecting Form Fields

Sometimes we do not know ahead of time how many fields a form needs.

For example, a program might need:

```text
2 fields
```

or:

```text
5 fields
```

Writing a different form for every possibility would not make sense.

Instead, PHP can use a loop to generate the required number of inputs.

After the form is submitted, another loop can collect those values.

## Complete Runnable Example — `index.php`

```php
<?php

$field_count = 0;

// Get number of fields
if (isset($_GET["field_count"])) {
    $field_count = (int) $_GET["field_count"];
}

// When item form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $field_count = (int) $_POST["field_count"];

    $items = array();

    for ($i = 1; $i <= $field_count; $i++) {
        $items[] = $_POST["item{$i}"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dynamic Fields</title>
</head>

<body>

    <h1>Choose Number of Items</h1>

    <!-- First form -->
    <form method="GET">

        <label for="field_count">
            How many items?
        </label>

        <input
            type="number"
            id="field_count"
            name="field_count"
            min="1"
            required
        >

        <button type="submit">
            Continue
        </button>

    </form>


    <?php if ($field_count > 0) : ?>

        <h2>Enter Items</h2>

        <!-- Second form -->
        <form method="POST">

            <!-- Carry field_count from GET to POST -->
            <input
                type="hidden"
                name="field_count"
                value="<?php echo $field_count; ?>"
            >

            <?php

            for ($i = 1; $i <= $field_count; $i++) {

                echo "
                    <p>
                        <label for='item{$i}'>
                            Item {$i}:
                        </label>

                        <input
                            type='text'
                            id='item{$i}'
                            name='item{$i}'
                            required
                        >
                    </p>
                ";
            }

            ?>

            <button type="submit">
                Submit
            </button>

        </form>

    <?php endif; ?>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        echo "<h2>Submitted Items</h2>";

        foreach ($items as $item) {
            echo "<p>{$item}</p>";
        }
    }

    ?>

</body>

</html>
```

## Generating the Fields

This loop generates the inputs:

```php
for ($i = 1; $i <= $field_count; $i++) {

    echo "
        <input
            type='text'
            name='item{$i}'
        >
    ";
}
```

If `$field_count` is:

```php
$field_count = 3;
```

PHP generates fields named:

```text
item1
item2
item3
```

Each input has a unique name because `$i` changes each time through the loop.

## Collecting the Values

After the form is submitted, the values are available through:

```php
$_POST["item1"]
$_POST["item2"]
$_POST["item3"]
```

Because the names follow the same pattern, another loop can retrieve them:

```php
$items = array();

for ($i = 1; $i <= $field_count; $i++) {

    $items[] = $_POST["item{$i}"];
}
```

`$items[]` adds each submitted value to the `$items` array.

---

# Exercises

These exercises practice the main ideas from this lesson.

Try to complete each exercise before looking back at the examples.

---

## Exercise 1 — Identifying Which Form Was Submitted

Create a PHP page that contains **two forms**.

Both forms should submit to the **same page** using `POST`.

### Form 1 — Student

The first form should ask the user to enter a student name.

```text
Student Name:
[________________]

[Submit Student]
```

Add a hidden input to this form.

The hidden input should use:

```text
name = form_id
value = student
```

### Form 2 — Course

The second form should ask the user to enter a course name.

```text
Course Name:
[________________]

[Submit Course]
```

Add a hidden input to this form.

The hidden input should use:

```text
name = form_id
value = course
```

### Your PHP Code

Use:

```php
$_POST["form_id"]
```

to determine which form was submitted.

If the student form was submitted, display:

```text
Student submitted: Alex
```

If the course form was submitted, display:

```text
Course submitted: Web Development
```

### Requirements

Your program should:

- Include two forms on the same page
- Use `POST` for both forms
- Use a hidden input in each form
- Give each form a different `form_id` value
- Use `isset()` before checking `form_id`
- Display the value from the form that was submitted

---

## Exercise 2 — Carrying a Value Between Forms

Create a PHP page that uses **two forms**.

The first form will collect a programming language.

The second form will collect a reason for choosing that language.

### Form 1 — Choose a Language

Create a form that allows the user to select:

```text
PHP
JavaScript
Python
```

For example:

```text
Programming Language:

[ PHP ▼ ]

[Continue]
```

Submit this form using `GET`.

PHP should retrieve the selected language using:

```php
$_GET["language"]
```

### Form 2 — Enter a Reason

After the user selects a language, display a second form.

For example, if the user selected PHP:

```text
Why do you like PHP?

[________________________]

[Submit]
```

Submit this form using `POST`.

The second form must contain a hidden input that carries the selected language.

For example:

```html
<input
    type="hidden"
    name="language"
    value="<?php echo $language; ?>"
>
```

PHP should then receive both:

```text
language
reason
```

After the second form is submitted, display the submitted information.

For example:

```text
Language: PHP
Reason: I like working with forms.
```

### Requirements

Your program should:

- Use `GET` for the first form
- Use `POST` for the second form
- Display the second form only after a language is selected
- Use a hidden input to carry the language into the second form
- Retrieve the language and reason after the second form is submitted
- Display both values


