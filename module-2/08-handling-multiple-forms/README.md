# Lesson 08 — Handling Multiple Forms

In previous lessons, we worked with forms that send data using `GET` and `POST`.

Now we are going to take that further and work with **multiple forms and multi-step form workflows**.

A page may contain more than one form. Sometimes those forms perform completely different tasks. Other times, one form collects information that is needed to build or process another form.

This introduces some new questions:

```text
Which form was submitted?

How can we keep information that another form needs?

How can one form lead to another form?

What if we do not know how many input fields we need?

How can PHP process dynamically generated fields?
```

In this lesson, we will learn how to manage these situations.

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

```php
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

    $username = htmlspecialchars($_POST["username"]);

    echo "Username: {$username}";
}

?>
```

Create `process_message.php`:

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = htmlspecialchars($_POST["message"]);

    echo "Message: {$message}";
}

?>
```

We now have:

```text
Username Form
      ↓
process_username.php


Message Form
      ↓
process_message.php
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
        ├──→ process.php
Form 2 ─┘
```

Now PHP may need to determine which form was submitted.

One way to do this is with a **hidden identifier**.

## Complete Example — `index.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] === "profile"
    ) {

        $name = htmlspecialchars($_POST["name"]);

        echo "<p>Profile form submitted.</p>";
        echo "<p>Name: {$name}</p>";
    }


    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] === "message"
    ) {

        $message = htmlspecialchars($_POST["message"]);

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

Notice that both forms contain:

```html
<input type="hidden" name="form_id">
```

But their values are different.

The first form sends:

```text
form_id = profile
```

The second sends:

```text
form_id = message
```

PHP can therefore check:

```php
$_POST["form_id"]
```

to determine which form was submitted.

### Important

`form_id` is **not a special PHP keyword**.

We created that name ourselves.

We could have called it something else:

```html
<input type="hidden" name="form_name" value="profile">
```

The important idea is that each form sends something that identifies it.

## Key Idea

There are different ways to distinguish forms.

For example:

```text
Different action values

OR

A hidden identifier
```

---

# 3. Retaining Information Between Form Submissions

Suppose one form collects some information.

Later, another form needs that same information.

The workflow might look like this:

```text
Form 1
   ↓
Submit
   ↓
PHP receives a value
   ↓
Form 2 needs that value
```

The important question is:

> How does Form 2 get information that originally came from Form 1?

A new form submission sends the values belonging to the form being submitted.

If another value is still required, we need to **intentionally include it in the next form**.

For example:

```text
Form 1

user_id = 25

      ↓

PHP receives 25

      ↓

Form 2 also needs user_id

      ↓

Carry 25 into Form 2
```

One way to do that is with a **hidden input**.

---

# 4. Hidden Form Inputs

A hidden input is created using:

```html
<input type="hidden">
```

Unlike a normal text or number input, a hidden input is not displayed as a field the user fills out.

However, it is still part of the form.

## Example

```html
<input
    type="hidden"
    name="user_id"
    value="25"
>
```

The user does not see an editable field containing `25`.

But when the form is submitted using POST, PHP can access:

```php
$_POST["user_id"]
```

and its value will be:

```text
25
```

## Complete Example — `index.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $message = htmlspecialchars($_POST["message"]);

    echo "<p>User ID: {$user_id}</p>";
    echo "<p>Message: {$message}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hidden Input</title>
</head>
<body>

    <h1>Send Message</h1>

    <form method="POST">

        <input
            type="hidden"
            name="user_id"
            value="25"
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
            Send
        </button>

    </form>

</body>
</html>
```

If the user enters:

```text
Hello
```

the form submits both:

```text
user_id = 25
message = Hello
```

even though the user only entered the message.

The relationship is:

```text
<input
    type="hidden"
    name="user_id"
    value="25"
>
       ↓
Form submitted
       ↓
$_POST["user_id"]
       ↓
25
```

## Why Use Hidden Inputs?

Hidden inputs can be useful when information needs to be submitted without asking the user to enter it again.

For example:

```text
Identify a form
       ↓
form_id


Carry an ID
       ↓
user_id


Carry information to another step
       ↓
number_of_items
```

### Important

A hidden input is **hidden from the page layout**, but it should not be treated as secret or secure storage.

Its purpose here is to carry information with a form submission.

## Key Idea

**Hidden inputs allow values to be submitted even though the user does not enter those values into visible fields.**

---

# 5. Multi-Step Forms

Sometimes one form provides information needed to create another form.

This creates a **multi-step workflow**.

For example:

```text
Step 1
Ask how many items the user has
        ↓
User enters 3
        ↓
PHP receives 3
        ↓
Step 2
Generate 3 input fields
```

The first form affects what the second form looks like.

## Simple Workflow

```text
FORM 1
Collect information
      ↓
Submit
      ↓
PHP receives information
      ↓
Use information to prepare FORM 2
      ↓
FORM 2
Collect additional information
      ↓
Submit
      ↓
PHP processes everything needed
```

The important concept is that **information from an earlier step may need to remain available during a later step**.

That is where a hidden input becomes useful:

```text
Form 1
   ↓
value = 3
   ↓
PHP
   ↓
Form 2
hidden value = 3
   ↓
Submit
   ↓
PHP still has 3
```

---

# 6. Complete Multi-Step Form Example

Let's build a small example before introducing dynamic fields.

The first form asks the user to select a category.

The second form asks the user for an item in that category.

## Complete Example — `index.php`

```php
<?php

$category = "";

if (isset($_GET["category"])) {

    $category = htmlspecialchars($_GET["category"]);

} elseif (isset($_POST["category"])) {

    $category = htmlspecialchars($_POST["category"]);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item = htmlspecialchars($_POST["item"]);

    echo "<h2>Submitted Information</h2>";
    echo "<p>Category: {$category}</p>";
    echo "<p>Item: {$item}</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multi-Step Form</title>
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

## Follow the Value

Suppose the user chooses:

```text
Books
```

First:

```text
GET Form
   ↓
category = Books
   ↓
$_GET["category"]
```

PHP stores:

```php
$category = "Books";
```

PHP then creates the second form.

The second form contains:

```html
<input
    type="hidden"
    name="category"
    value="Books"
>
```

Now the second form submits:

```text
category = Books
item = The Hobbit
```

So the complete journey is:

```text
Form 1
category = Books
       ↓
GET
       ↓
$category
       ↓
Hidden Input
category = Books
       ↓
Form 2
       ↓
POST
       ↓
$_POST["category"]
```

That is the important multi-step concept.

---

# 7. Dynamically Generating Form Fields

Sometimes we do not know ahead of time how many fields a form needs.

For example, imagine asking:

> How many items would you like to enter?

The user might enter:

```text
2
```

or:

```text
5
```

or:

```text
10
```

Writing ten different versions of the form would not make sense.

Instead, PHP can generate the fields using a loop.

## Basic Example

```php
<?php

$field_count = 4;

for ($i = 1; $i <= $field_count; $i++) {

    echo "<input type='text' name='item{$i}'>";

}

?>
```

The loop runs four times.

The generated HTML contains input names equivalent to:

```text
item1
item2
item3
item4
```

Look carefully at:

```php
"item{$i}"
```

When:

```text
$i = 1
```

PHP creates:

```text
item1
```

When:

```text
$i = 2
```

PHP creates:

```text
item2
```

and so on.

## Complete Runnable Example — `index.php`

```php
<?php

$field_count = 5;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Fields</title>
</head>
<body>

    <h1>Enter Items</h1>

    <form method="POST">

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

</body>
</html>
```

PHP generates five fields:

```text
Item 1: [          ]

Item 2: [          ]

Item 3: [          ]

Item 4: [          ]

Item 5: [          ]
```

The important part is that every input has a unique name:

```text
item1
item2
item3
item4
item5
```

---

# 8. Collecting Dynamically Submitted Values

Generating the fields is only half of the problem.

PHP also needs to retrieve the submitted values.

Suppose our form generated:

```html
<input name="item1">
<input name="item2">
<input name="item3">
```

After submission, PHP receives:

```php
$_POST["item1"]
$_POST["item2"]
$_POST["item3"]
```

Because the names follow a pattern, we can use another loop to retrieve them.

## Using an Array

We can start with an empty array:

```php
$items = array();
```

Then:

```php
for ($i = 1; $i <= $field_count; $i++) {

    $items[] = $_POST["item{$i}"];

}
```

The syntax:

```php
$items[]
```

means:

> Add another value to the end of the `$items` array.

For example:

```php
$items[] = "Apple";
$items[] = "Banana";
$items[] = "Orange";
```

produces an array containing:

```text
Apple
Banana
Orange
```

We only need this small amount of array knowledge for our form workflow.

## Complete Runnable Example — `index.php`

```php
<?php

$field_count = 3;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $items = array();

    for ($i = 1; $i <= $field_count; $i++) {

        $items[] = htmlspecialchars(
            $_POST["item{$i}"]
        );

    }

    echo "<h2>Submitted Items</h2>";

    foreach ($items as $item) {

        echo "<p>{$item}</p>";

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Form</title>
</head>
<body>

    <h1>Enter Three Items</h1>

    <form method="POST">

        <?php

        for ($i = 1; $i <= $field_count; $i++) {

            $value = isset($_POST["item{$i}"])
                ? htmlspecialchars($_POST["item{$i}"])
                : "";

            echo "
                <p>
                    <label for='item{$i}'>
                        Item {$i}:
                    </label>

                    <input
                        type='text'
                        id='item{$i}'
                        name='item{$i}'
                        value='{$value}'
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

</body>
</html>
```

There is an important connection here:

```text
GENERATE FIELD

name="item{$i}"

       ↓

item1
item2
item3


PROCESS FIELD

$_POST["item{$i}"]

       ↓

$_POST["item1"]
$_POST["item2"]
$_POST["item3"]
```

We are using the **same naming pattern** when creating and processing the inputs.

## Key Idea

When fields are created dynamically, their submitted values can also be processed dynamically.

---

# 9. Applying the Concepts — Mean, Median & Mode Calculator

Now we can put these concepts together in a larger application.

The application will:

1. Ask the user how many numbers are in a data set.
2. Generate the required number of fields.
3. Let the user enter the numbers.
4. Submit those numbers.
5. Calculate the Mean, Median, and Mode.
6. Display the results.

Before looking at the application, we need to understand what those three calculations mean.

---

## Mean, Median, and Mode

Suppose we have:

```text
2, 3, 3, 5, 7
```

### Mean

The **mean** is the average.

Add all the numbers:

```text
2 + 3 + 3 + 5 + 7 = 20
```

Then divide by the number of values:

```text
20 ÷ 5 = 4
```

Therefore:

```text
Mean = 4
```

### Median

The **median** is the middle value after the numbers are placed in order.

```text
2, 3, 3, 5, 7
      ↑
```

Therefore:

```text
Median = 3
```

If there is an even number of values, there are two middle values, so their average is used.

### Mode

The **mode** is the value that appears most often.

```text
2, 3, 3, 5, 7
   ↑  ↑
```

`3` appears twice.

Therefore:

```text
Mode = 3
```

---

# Understanding the Calculator Workflow

Our application uses two forms.

```text
FORM 1 — GET

How many numbers?
        ↓
set-length
        ↓
Generate second form
        ↓
FORM 2 — POST
        ↓
Hidden set-length
        ↓
Generate:
num1
num2
num3
...
        ↓
User enters numbers
        ↓
POST
        ↓
Collect numbers into $nums
        ↓
Calculate
        ↓
Display Results
```

Notice how this application brings together the concepts from this lesson:

```text
Multiple forms
      ↓
Multi-step workflow
      ↓
Retaining information
      ↓
Hidden input
      ↓
Dynamic fields
      ↓
Dynamic field names
      ↓
Collect values into an array
```

---

# Step 1 — Retaining the Number of Fields

The application needs to know how many number fields should exist.

The supplied code stores this in:

```php
$set_length
```

It may arrive from the first GET form:

```php
$_GET["set-length"]
```

or later from the POST form:

```php
$_POST["set-length"]
```

The application checks both:

```php
$set_length = '';

switch (true) {

    case isset($_GET["set-length"]):

        $set_length =
            htmlspecialchars($_GET["set-length"]);

        break;


    case isset($_POST["set-length"]):

        $set_length =
            htmlspecialchars($_POST["set-length"]);

        break;
}
```

The important concept is:

```text
First submission
       ↓
GET
       ↓
set-length


Later submission
       ↓
POST
       ↓
set-length
```

The application keeps the information available during both stages.

---

# Step 2 — The First Form

The first form asks:

> How many numbers are in your data set?

```php
<form
    action="<?php echo $_SERVER['PHP_SELF']; ?>"
    method="GET"
>

    <label for="set-length">
        How many numbers are in your data set?
    </label>

    <input
        type="number"
        id="set-length"
        name="set-length"
        value="<?php echo $set_length; ?>"
    >

    <input
        type="submit"
        name="submit-get"
        value="Generate Form"
    >

</form>
```

Suppose the user enters:

```text
4
```

The GET submission gives PHP:

```php
$_GET["set-length"]
```

with the value:

```text
4
```

Now:

```php
$set_length = 4;
```

---

# Step 3 — Generate the Second Form

The second form only appears after `$set_length` contains a value:

```php
<?php if ($set_length != '') : ?>

    <!-- second form -->

<?php endif; ?>
```

If:

```php
$set_length = 4;
```

PHP knows that four number inputs are required.

---

# Step 4 — Carry `set-length` Forward

There is a problem.

The second form uses POST.

We still need to know that:

```text
set-length = 4
```

when the second form is submitted.

So the application places it into a hidden input:

```php
<input
    type="hidden"
    name="set-length"
    value="<?php echo $set_length; ?>"
>
```

Now the second form contains:

```text
set-length = 4
```

even though the user does not need to type `4` again.

The journey is:

```text
GET Form
       ↓
set-length = 4
       ↓
$set_length = 4
       ↓
Hidden Input
set-length = 4
       ↓
POST Form
       ↓
$_POST["set-length"]
       ↓
4
```

This is the main reason the hidden input is important in this application.

---

# Step 5 — Generate the Number Fields

The application now uses `$set_length` in a loop:

```php
for ($i = 1; $i <= $set_length; $i++) {

    echo "
        <label for='num{$i}'>
            Enter Number {$i}:
        </label>

        <input
            type='number'
            name='num{$i}'
            id='num{$i}'
            required
        >
    ";
}
```

If:

```php
$set_length = 4;
```

the loop generates:

```text
num1
num2
num3
num4
```

The form therefore looks approximately like:

```text
Enter Number 1: [     ]

Enter Number 2: [     ]

Enter Number 3: [     ]

Enter Number 4: [     ]

[ Calculate ]
```

---

# Step 6 — Retain the Dynamic Values

The supplied application also retains values entered into the dynamically generated fields.

Inside the loop:

```php
$value = isset($_POST["num{$i}"])
    ? htmlspecialchars($_POST["num{$i}"])
    : '';
```

Then:

```php
echo "
    <input
        type='number'
        name='num{$i}'
        id='num{$i}'
        value='{$value}'
        required
    >
";
```

This uses the sticky-form technique from the previous lesson, but now we are applying it to **dynamically generated inputs**.

---

# Step 7 — Collect the Numbers

After the second form is submitted, PHP needs all the entered numbers.

Start with:

```php
$nums = array();
```

Then use the same naming pattern:

```php
for ($i = 1; $i <= $set_length; $i++) {

    $nums[] = $_POST["num{$i}"];

}
```

If the user entered:

```text
2
3
3
5
7
```

then `$nums` contains those values.

The important connection is:

```text
Generated

num1
num2
num3
num4
num5

       ↓

Submitted

$_POST["num1"]
$_POST["num2"]
$_POST["num3"]
$_POST["num4"]
$_POST["num5"]

       ↓

Collected

$nums
```

---

# Step 8 — Process the Numbers

The supplied `process.php` first sorts the numbers:

```php
sort($nums);
```

Then counts them:

```php
$count = count($nums);
```

Then calculates their total:

```php
$sum = array_sum($nums);
```

The mean is:

```php
$mean = $sum / $count;
```

For the median, the code determines the middle position:

```php
$middle = floor(($count - 1) / 2);
```

Then checks whether the number of values is even or odd:

```php
if ($count % 2 == 0) {

    $median =
        ($nums[$middle] + $nums[$middle + 1]) / 2;

} else {

    $median = $nums[$middle];
}
```

For the mode:

```php
$mode = array_count_values($nums);

$mode = array_keys(
    $mode,
    max($mode)
);

$mode = implode(', ', $mode);
```

Some PHP functions being used are:

```text
sort()
→ sorts an array


count()
→ counts the values in an array


array_sum()
→ adds the numeric values


floor()
→ rounds down


array_count_values()
→ counts how often each value occurs


max()
→ finds the largest value


array_keys()
→ retrieves array keys


implode()
→ joins array values into a string
```

The important lesson here is not memorizing every function immediately.

Understand the larger workflow:

```text
Submitted dynamic fields
          ↓
Collect into array
          ↓
Process array
          ↓
Produce results
```

---

# Complete Application

The following puts the complete application together using the same overall structure as the supplied files.

## `index.php`

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Mean, Median & Mode Calculator
    </title>
</head>

<body>

    <h1>
        Mean, Median & Mode Calculator
    </h1>

    <?php

    $set_length = '';

    switch (true) {

        case isset($_GET["set-length"]):

            $set_length =
                htmlspecialchars($_GET["set-length"]);

            break;


        case isset($_POST["set-length"]):

            $set_length =
                htmlspecialchars($_POST["set-length"]);

            break;
    }


    include("process.php");

    ?>


    <h2>Step 1</h2>

    <form
        action="<?php echo $_SERVER['PHP_SELF']; ?>"
        method="GET"
    >

        <label for="set-length">
            How many numbers are in your data set?
        </label>

        <input
            type="number"
            id="set-length"
            name="set-length"
            value="<?php echo $set_length; ?>"
            min="1"
            required
        >

        <input
            type="submit"
            name="submit-get"
            value="Generate Form"
        >

    </form>


    <?php if ($set_length != '') : ?>

        <h2>Step 2</h2>

        <form
            action="<?php echo $_SERVER['PHP_SELF']; ?>"
            method="POST"
        >

            <input
                type="hidden"
                name="set-length"
                value="<?php echo $set_length; ?>"
            >

            <?php

            for ($i = 1; $i <= $set_length; $i++) {

                $value =
                    isset($_POST["num{$i}"])
                    ? htmlspecialchars($_POST["num{$i}"])
                    : '';

                echo "<p>";

                echo "
                    <label for='num{$i}'>
                        Enter Number {$i}:
                    </label>
                ";

                echo "
                    <input
                        type='number'
                        name='num{$i}'
                        id='num{$i}'
                        value='{$value}'
                        required
                    >
                ";

                echo "</p>";
            }

            ?>

            <input
                type="submit"
                value="Calculate"
            >

        </form>

    <?php endif; ?>

</body>

</html>
```

## `process.php`

```php
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nums = array();


    // Collect submitted numbers

    for ($i = 1; $i <= $set_length; $i++) {

        $nums[] = $_POST["num{$i}"];

    }


    // Sort the numbers

    sort($nums);


    // Count the numbers

    $count = count($nums);


    // Calculate the mean

    $sum = array_sum($nums);

    $mean = $sum / $count;


    // Calculate the median

    $middle = floor(($count - 1) / 2);


    if ($count % 2 == 0) {

        $median =
            ($nums[$middle] + $nums[$middle + 1]) / 2;

    } else {

        $median = $nums[$middle];

    }


    // Calculate the mode

    $mode = array_count_values($nums);

    $mode = array_keys(
        $mode,
        max($mode)
    );

    $mode = implode(", ", $mode);


    // Display results

    echo "<h2>Results</h2>";

    echo "<p>
        Your numbers:
        " . implode(", ", $nums) . "
    </p>";

    echo "<p>
        Mean: {$mean}
    </p>";

    echo "<p>
        Median: {$median}
    </p>";

    echo "<p>
        Mode: {$mode}
    </p>";

}

?>
```

---

# Following the Complete Workflow

Suppose the user first enters:

```text
5
```

## First Submission

```text
GET Form
   ↓
set-length = 5
   ↓
$_GET["set-length"]
   ↓
$set_length = 5
```

PHP now generates five number fields.

It also generates:

```html
<input
    type="hidden"
    name="set-length"
    value="5"
>
```

The user enters:

```text
2
3
3
5
7
```

## Second Submission

The POST form sends:

```text
set-length = 5

num1 = 2
num2 = 3
num3 = 3
num4 = 5
num5 = 7
```

PHP receives:

```text
$_POST["set-length"]

$_POST["num1"]
$_POST["num2"]
$_POST["num3"]
$_POST["num4"]
$_POST["num5"]
```

The loop collects the numbers:

```text
$nums
  ↓
2, 3, 3, 5, 7
```

Then PHP processes the array and displays the results.

That is the complete multi-form workflow.

---

