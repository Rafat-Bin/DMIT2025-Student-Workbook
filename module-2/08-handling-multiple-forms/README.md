
# Lesson 08 — Handling Multiple Forms

In previous lessons, we worked with forms that send data using `GET` and `POST`.

Now we are going to take that further and work with **multiple forms, multi-step forms, and dynamically generated form fields**.

By the end of this lesson, you will be able to:

- Work with more than one form on a page
- Determine which form was submitted
- Carry information from one form to another
- Use hidden form inputs
- Generate form fields using PHP
- Collect dynamically generated values
- Process those values using an array



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
        $_POST["form_id"] === "profile"
    ) {

        $name = $_POST["name"];

        echo "<p>Profile form submitted.</p>";
        echo "<p>Name: {$name}</p>";
    }


    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] === "message"
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

But their values are different.

The first form sends:

```text
form_id = profile
```

The second form sends:

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
<input
    type="hidden"
    name="form_name"
    value="profile"
>
```

The important idea is that each form sends something that identifies it.



---

# 3. Hidden Inputs and Multi-Step Forms

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

The form carried the value automatically.

### Important

A hidden input is hidden from the page layout, but it should **not** be treated as secret or secure storage.

Its purpose here is to carry information with a form submission.

---

## Multi-Step Form Example

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

## Complete Example — `index.php`

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

The first form sends:

```text
category = Books
```

PHP receives:

```php
$_GET["category"]
```

and stores:

```php
$category = "Books";
```

PHP then creates the second form.

Inside that form is:

```html
<input
    type="hidden"
    name="category"
    value="Books"
>
```

If the user enters:

```text
The Hobbit
```

the second form submits:

```text
category = Books
item = The Hobbit
```

The complete journey is:

```text
Form 1
category = Books
       ↓
GET
       ↓
$category
       ↓
Hidden Input
       ↓
Form 2
       ↓
POST
       ↓
category = Books
item = The Hobbit
```

## Key Idea

**A hidden input can carry information from an earlier step into a later form submission.**

---

# 4. Dynamically Generating Form Fields

Sometimes we do not know ahead of time how many fields a form needs.

Imagine asking:

> How many items would you like to enter?

The user might enter:

```text
2
```
or:

```text
10
```

Writing a different form for every possibility would not make sense.

Instead, PHP can use a loop to generate the required number of inputs.


## Complete Runnable Example — `index.php`

```php
<?php

$field_count = 3;

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

PHP generates:

```text
Item 1: [          ]

Item 2: [          ]

Item 3: [          ]
```

Each input has a unique name:

```text
item1
item2
item3
```

---

# 5. Collecting Dynamically Generated Fields

Generating the inputs is only half of the job.

After the form is submitted, PHP also needs to retrieve their values.

If PHP generated:

```html
<input name="item1">
<input name="item2">
<input name="item3">
```

then the submitted values are available through:

```php
$_POST["item1"]
$_POST["item2"]
$_POST["item3"]
```

Because the input names follow a pattern, we can use another loop to retrieve them.

Start with an empty array:

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

For now, this is the main array behaviour we need to understand.

## Creating and Processing Use the Same Pattern

This is the important connection:

```text
CREATE                     PROCESS

name="item1"      →        $_POST["item1"]

name="item2"      →        $_POST["item2"]

name="item3"      →        $_POST["item3"]
```

Using the loop:

```text
CREATE                     PROCESS

"item{$i}"        →        $_POST["item{$i}"]
```

The same naming pattern is used when creating and processing the inputs.

## Complete Runnable Example — `index.php`

```php
<?php

$field_count = 3;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $items = array();

    for ($i = 1; $i <= $field_count; $i++) {

        $items[] = $_POST["item{$i}"];

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


## Key Idea

**When form fields are generated dynamically, their submitted values can also be processed dynamically.**

---

# 6. Applying the Concepts — Mean, Median & Mode Calculator

Now we can combine the concepts from this lesson.

We are going to create a **Mean, Median, and Mode Calculator**.

The application will:

1. Ask how many numbers are in a data set.
2. Generate that many number fields.
3. Let the user enter the numbers.
4. Submit the numbers.
5. Collect them into an array.
6. Calculate the mean, median, and mode.
7. Display the results.

The main focus is the **form workflow**.

You do not need to memorize every array function used in the calculations.

---

## Mean, Median, and Mode

Before building the application, let's briefly review the calculations.

Suppose we have:

```text
2, 3, 3, 5, 7
```

### Mean

The **mean** is the average.

```text
2 + 3 + 3 + 5 + 7 = 20

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

If there is an even number of values, the two middle values are averaged.

### Mode

The **mode** is the value that appears most often.

```text
2, 3, 3, 5, 7
   ↑  ↑
```

Therefore:

```text
Mode = 3
```

---

# 7. Understanding the Calculator Workflow

The calculator contains two forms.

```text
STEP 1

GET Form
How many numbers?
       ↓
set-length
       ↓
PHP receives the value
       ↓
Generate number fields


STEP 2

POST Form
       ↓
Hidden set-length
       ↓
num1
num2
num3
...
       ↓
User enters numbers
       ↓
POST
       ↓
Collect into $nums
       ↓
Calculate
       ↓
Display results
```

This combines the main concepts from the lesson:

```text
Multiple forms
      ↓
Multi-step workflow
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

# Step 1 — Determine the Number of Fields

The application needs to know how many number fields to generate.

We store this value in:

```php
$set_length
```

The value may come from the first GET form:

```php
$_GET["set-length"]
```

or from the later POST form:

```php
$_POST["set-length"]
```

We can check both:

```php
$set_length = '';

switch (true) {

    case isset($_GET["set-length"]):

        $set_length = $_GET["set-length"];

        break;


    case isset($_POST["set-length"]):

        $set_length = $_POST["set-length"];

        break;
}
```

Why check both?

Because the application has two stages:

```text
First submission
      ↓
GET
      ↓
set-length


Second submission
      ↓
POST
      ↓
set-length
```

---

# Step 2 — Ask How Many Numbers

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
        min="1"
        required
    >

    <input
        type="submit"
        value="Generate Form"
    >

</form>
```

Suppose the user enters:

```text
4
```

PHP receives:

```php
$_GET["set-length"]
```

and:

```php
$set_length = 4;
```

---

# Step 3 — Generate the Second Form

We only display the second form when `$set_length` contains a value.

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

The second form uses POST.

However, we still need to remember that:

```text
set-length = 4
```

So we include it in the second form using a hidden input:

```php
<input
    type="hidden"
    name="set-length"
    value="<?php echo $set_length; ?>"
>
```

Now when the second form is submitted, PHP receives the value again through:

```php
$_POST["set-length"]
```

The user does not need to enter it again.

The value moves through the application like this:

```text
GET Form
       ↓
set-length = 4
       ↓
$set_length
       ↓
Hidden Input
       ↓
POST Form
       ↓
$_POST["set-length"]
```

---

# Step 5 — Generate the Number Fields

Now we can use `$set_length` in a loop.

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

PHP generates:

```text
num1
num2
num3
num4
```

The form looks approximately like:

```text
Enter Number 1: [     ]

Enter Number 2: [     ]

Enter Number 3: [     ]

Enter Number 4: [     ]

[ Calculate ]
```

---

# Step 6 — Retain the Dynamic Values

We can also make the dynamically generated fields **sticky**.

Inside the loop:

```php
$value = isset($_POST["num{$i}"])
    ? $_POST["num{$i}"]
    : '';
```

Then use `$value` when generating the input:

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

This is the same sticky-form technique from the previous lesson.

The difference is that we are now applying it to **dynamically generated inputs**.

---

# Step 7 — Collect the Numbers

After the second form is submitted, PHP needs to retrieve all of the entered numbers.

We begin with an empty array:

```php
$nums = array();
```

Then use the same naming pattern that we used when generating the fields:

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

then `$nums` contains:

```text
2, 3, 3, 5, 7
```

Notice the relationship:

```text
GENERATED                  PROCESSED

num1              →        $_POST["num1"]

num2              →        $_POST["num2"]

num3              →        $_POST["num3"]

num4              →        $_POST["num4"]

num5              →        $_POST["num5"]
```

The loop allows PHP to process any number of fields without writing each one manually.

---

# Step 8 — Process the Numbers

Once the numbers are inside `$nums`, we can perform the calculations.

First, sort the numbers:

```php
sort($nums);
```

Count them:

```php
$count = count($nums);
```

Calculate their total:

```php
$sum = array_sum($nums);
```

Calculate the mean:

```php
$mean = $sum / $count;
```

Calculate the median:

```php
$middle = floor(($count - 1) / 2);

if ($count % 2 == 0) {

    $median =
        ($nums[$middle] + $nums[$middle + 1]) / 2;

} else {

    $median = $nums[$middle];
}
```

Calculate the mode:

```php
$mode = array_count_values($nums);

$mode = array_keys(
    $mode,
    max($mode)
);

$mode = implode(", ", $mode);
```

### Don't Worry About Memorizing These Yet

This application uses several PHP functions:

```text
sort()
count()
array_sum()
floor()
array_count_values()
max()
array_keys()
implode()
```

You do not need to memorize all of these functions right now.

The important workflow for this lesson is:

```text
Generate fields
      ↓
Submit fields
      ↓
Collect values into an array
      ↓
Process the array
      ↓
Display results
```

---

# Complete Application

Now let's put everything together.

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

            $set_length = $_GET["set-length"];

            break;


        case isset($_POST["set-length"]):

            $set_length = $_POST["set-length"];

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
                    ? $_POST["num{$i}"]
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

Suppose the user enters:

```text
5
```

## First Submission

The GET form sends:

```text
set-length = 5
```

PHP receives:

```php
$_GET["set-length"]
```

and stores:

```php
$set_length = 5;
```

PHP now generates five number fields:

```text
num1
num2
num3
num4
num5
```

The second form also contains:

```html
<input
    type="hidden"
    name="set-length"
    value="5"
>
```

Suppose the user enters:

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

PHP collects the numbers using:

```php
for ($i = 1; $i <= $set_length; $i++) {

    $nums[] = $_POST["num{$i}"];

}
```

The result is:

```text
$nums
  ↓
2, 3, 3, 5, 7
```

PHP then processes the array and displays the results.

---

# Lesson Summary

In this lesson, we learned that a page can contain **multiple forms**.

Each form is submitted independently.

When multiple forms submit to the same PHP page, we can identify them using a value such as:

```html
<input
    type="hidden"
    name="form_id"
    value="profile"
>
```

We also learned that **hidden inputs** can carry information from one step of a form workflow to another.

```text
Form 1
   ↓
PHP receives value
   ↓
Hidden input
   ↓
Form 2
   ↓
PHP receives value again
```

When we do not know how many fields are needed ahead of time, PHP can generate them using a loop:

```php
for ($i = 1; $i <= $field_count; $i++) {

    echo "<input name='item{$i}'>";

}
```

We can then use the same naming pattern to collect the submitted values:

```php
for ($i = 1; $i <= $field_count; $i++) {

    $items[] = $_POST["item{$i}"];

}
```

The overall pattern is:

```text
Collect information
       ↓
Submit form
       ↓
PHP receives information
       ↓
Use information to create another form
       ↓
Carry required values forward
       ↓
Submit second form
       ↓
Collect values
       ↓
Process values
```

The most important idea is not memorizing every line of code.

It is understanding **how the data moves from one form submission to the next**.
````
