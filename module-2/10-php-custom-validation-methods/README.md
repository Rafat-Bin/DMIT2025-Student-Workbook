
# Lesson 10 — Form Validation

Forms allow users to send information to our PHP applications.

However, we should never assume that the information submitted by a user is correct.

A user might:

- Leave a required field empty.
- Enter text where we expect a number.
- Enter a value that is too long.
- Enter a number outside the allowed range.
- Submit a value that was not one of the available choices.
- Enter information in the wrong format.
- Enter an invalid date.

**Validation** means checking submitted data before our application uses it.


---

# 1. Why Do We Validate?

Consider a form that asks for someone's age:

```html
<form method="POST">

    <label for="age">
        Age:
    </label>

    <input
        type="text"
        id="age"
        name="age"
    >

    <button type="submit">
        Submit
    </button>

</form>
```

We expect the user to enter something like:

```text
25
```

But they could enter:

```text
hello
```

They could also enter:

```text
500
```

Or they could leave the field completely empty.

Before using the value, our application should check that it meets our requirements.

This is **validation**.


### Key Idea

> Never blindly trust user-provided data.

---

# 2. Front-End and Back-End Validation

Validation can happen in two places.

## Front-End Validation

Front-end validation happens in the browser.

For example, HTML provides the `required` attribute:

```html
<input
    type="text"
    name="name"
    required
>
```

The browser can prevent the form from being submitted when the field is empty.

---

## Back-End Validation

Back-end validation happens on the server.

PHP can check the submitted value:

```php
if (empty($_POST["name"])) {

    echo "Please enter your name.";
}
```

Even when front-end validation is used, applications should still validate information on the server.

For this lesson, we want to practise validation using **PHP**, so we will perform our validation on the back end.

```html
<?php

if (isset($_POST["submit"])) {

    if (empty($_POST["name"])) {

        echo "Please  your name.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Validation</title>
</head>

<body>

    <form method="POST">

        <input
            type="text"
            name="name"
        >

        <button
            type="submit"
            name="submit"
        >
            Submit
        </button>

    </form>

</body>

</html>
```

---

# 3. Common Types of Validation

Different fields require different validation rules. Some common types are:

### Presence

Was a value provided?

```text
Name: [          ]
```

---

### Length

Is the value too short or too long?

```text
Username must be no more than 20 characters.
```

---

### Type

Is the submitted value the type of information we expect?

```text
Age: 25       ✓

Age: hello    ✗
```

---

### Range

Is a number within an acceptable range?

```text
Quantity: 5      ✓

Quantity: 500    ✗
```

---

### Format

Does the value follow the expected pattern?


```text
780-555-1234
```

---

### Allowed Values

Is the submitted value one of the choices our application allows?

```text
beginner
intermediate
advanced
```

---

### Uniqueness

Some applications also need to make sure a value has not already been used.


```text
Username
Email Address
```

Checking uniqueness usually requires saved information, such as information stored in a database.

We will deal with this type of validation when working with databases.

---

# 4. Required and Optional Fields

Some form fields are **required**.

Others are **optional**.

These fields should not always be validated in exactly the same way.

---

## Checking a Required Field

Suppose we have:

```html
<label for="name">
    Name:
</label>

<input
    type="text"
    id="name"
    name="name"
>
```

When the form is submitted, we can retrieve the value:

```php
$name = isset($_POST["name"])
    ? trim($_POST["name"])
    : "";
```

There are two important functions here:

```php
isset()
```

checks whether the value exists.

And:

```php
trim()
```

removes unnecessary spaces from the beginning and end.

For example:

```text
"   Sam Smith   "
        ↓
      trim()
        ↓
"Sam Smith"
```

Now we can check:

```php
if (empty($name)) {

    echo "Please enter your name.";
}
```

---


### Key Idea

A common pattern is:

```php
$value = isset($_POST["field"])
    ? trim($_POST["field"])
    : "";
```

Then:

```php
if (empty($value)) {

    // Show an error.
}
```

---

# 5. Optional Fields

An optional field does not need to contain a value.

For example:

```html
<label for="nickname">
    Nickname (Optional):
</label>

<input
    type="text"
    id="nickname"
    name="nickname"
>
```

We should not display an error simply because it is empty.

Instead:

```php
$nickname = isset($_POST["nickname"])
    ? trim($_POST["nickname"])
    : "";
```

Then we only validate it if the user entered something:

```php
if (!empty($nickname)) {

    // Validate the nickname.
}
```

The `!` means **NOT**.

So:

```php
!empty($nickname)
```

means:

> The nickname is NOT empty.


### Key Idea

A required field must contain a valid value.

An optional field may be empty, but **if the user provides a value, that value may still need validation**.


```php
<?php

$name = "";
$nickname = "";

if (isset($_POST["submit"])) {

    $name = isset($_POST["name"])
        ? trim($_POST["name"])
        : "";

    $nickname = isset($_POST["nickname"])
        ? trim($_POST["nickname"])
        : "";


    // Required field
    if (empty($name)) {

        echo "<p>Please enter your name.</p>";

    } else {

        echo "<p>Name: " . htmlspecialchars($name) . "</p>";
    }


    // Optional field
    if (!empty($nickname)) {

        echo "<p>Nickname: "
            . htmlspecialchars($nickname)
            . "</p>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Required and Optional Fields</title>
</head>

<body>

    <h1>Registration</h1>

    <form method="POST">

        <div>

            <label for="name">
                Name:
            </label>

            <input
                type="text"
                id="name"
                name="name"
            >

        </div>

        <div>

            <label for="nickname">
                Nickname (Optional):
            </label>

            <input
                type="text"
                id="nickname"
                name="nickname"
            >

        </div>

        <button
            type="submit"
            name="submit"
        >
            Submit
        </button>

    </form>

</body>

</html>
```


---

# 6. Validating String Length

Sometimes text cannot be too short or too long.

PHP provides:

```php
strlen()
```

`strlen()` returns the number of characters in a string.

Example:

```php
$username = "samantha";

echo strlen($username);
```

Output:

```text
8
```

We can use this for validation.


```php
if (
    strlen($username) < 3 ||
    strlen($username) > 20
) {

    echo "Username must be between 3 and 20 characters.";
}
```


---

# 7. Validating Numbers

Before using a submitted value in numeric calculations, we should check that it contains a number.

PHP provides:

```php
is_numeric()
```

Example:

```php
$quantity = "5";

if (is_numeric($quantity)) {

    echo "This is numeric.";
}
```

But:

```php
$quantity = "hello";

if (!is_numeric($quantity)) {

    echo "Please enter a number.";
}
```

means:

> The value is NOT numeric.

---

# 8. Validating a Number Range

Sometimes being numeric is not enough.

Imagine a workshop registration form allows groups between:

```text
1 and 8 people
```


We should normally check that the value is numeric **before** checking its range.

```php
if (!is_numeric($group_size)) {

    echo "Group size must be a number.";

} elseif ($group_size < 1 || $group_size > 8) {

    echo "Group size must be between 1 and 8.";
}
```




---

# 9. Validating Allowed Values

Sometimes a field should only accept certain values.

Suppose a form asks for experience level:

```html
<select name="level">

    <option value="">
        Choose a Level
    </option>

    <option value="beginner">
        Beginner
    </option>

    <option value="intermediate">
        Intermediate
    </option>

    <option value="advanced">
        Advanced
    </option>

</select>
```

We might create an array containing the values our application allows:

```php
$allowed_levels = [
    "beginner",
    "intermediate",
    "advanced"
];
```

PHP provides:

```php
in_array()
```

to check whether a value exists inside an array.

```php
if (in_array($level, $allowed_levels)) {

    echo "Valid level.";
}
```

If we want to detect an invalid value:

```php
if (!in_array($level, $allowed_levels)) {

    echo "Please select a valid level.";
}
```

This is important even when the value comes from a `<select>`.

We should not assume that submitted form data is valid simply because our HTML only displayed certain options.





---

# 10. Creating an Allowed-Value Function

Because we learned custom functions in the previous lesson, we can put this validation inside a function.



```php
<?php

$level = "";

$allowed_levels = [
    "beginner",
    "intermediate",
    "advanced"
];


function has_allowed_value($value, $list) {

    return in_array($value, $list);
}


if (isset($_POST["submit"])) {

    $level = $_POST["level"];

    if (empty($level)) {

        echo "<p>Please select a level.</p>";

    } elseif (
        has_allowed_value($level, $allowed_levels) == FALSE
    ) {

        echo "<p>Please select a valid level.</p>";

    } else {

        echo "<p>You selected: $level</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Allowed Values</title>
</head>

<body>

    <form method="POST">

        <label for="level">
            Experience Level:
        </label>

        <select id="level" name="level">

            <option value="">
                Choose a Level
            </option>

            <option value="beginner">
                Beginner
            </option>

            <option value="intermediate">
                Intermediate
            </option>

            <option value="advanced">
                Advanced
            </option>

        </select>

        <button type="submit" name="submit">
            Submit
        </button>

    </form>

</body>

</html>
```

This connects directly to what we learned about **parameters and return values** in the previous lesson.

---

# 11. Validating Formats

Sometimes we need to check the **format** of submitted text.

For example, imagine we want phone numbers to look like:

```text
780-555-1234
```

These should not be accepted:

```text
7805551234
hello
555-1234
```

One way to check patterns is with a **Regular Expression**, usually called **RegEx**.

---

# 12. Regular Expressions

A Regular Expression describes what a string should look like.

For example:

```php
$phone_regex = "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";
```

This pattern describes:

```text
3 numbers
   ↓
   -
   ↓
3 numbers
   ↓
   -
   ↓
4 numbers
```

PHP provides:

```php
preg_match()
```

to compare a string against a Regular Expression.

Example:

```php
$phone = "780-555-1234";

$phone_regex =
    "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";

if (preg_match($phone_regex, $phone)) {

    echo "Valid phone number.";

} else {

    echo "Please enter a valid phone number.";
}
```

You do not need to memorize every RegEx symbol.


---

# 13. Using RegEx in a Function

We can also create a reusable validation function:

```php
function has_valid_phone_format($phone) {

    $phone_regex =
        "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";

    return preg_match(
        $phone_regex,
        $phone
    );
}
```

Then:

```php
$phone = "780-555-1234";

if (
    has_valid_phone_format($phone)
) {

    echo "Valid phone number.";
}
```

This combines what we learned about **custom functions** with validation.

---

# 14. Validating Dates

Dates need special attention.

A user could submit something that looks like a date but is not actually a real date.

For example:

```text
2026-02-31
```

February does not have 31 days.

PHP provides:

```php
date_parse_from_format()
```

which can help us check a date.

Suppose our expected format is:

```text
YYYY-MM-DD
```

For example:

```text
2026-10-15
```

We can create a function:

```php
function is_valid_date($user_date) {

    $date_format = "Y-m-d";

    $parsed_date =
        date_parse_from_format(
            $date_format,
            $user_date
        );

    if (
        $parsed_date["error_count"] == 0 &&
        $parsed_date["warning_count"] == 0
    ) {

        return TRUE;

    } else {

        return FALSE;
    }
}
```

Then:

```php
$date = "2026-10-15";

if (is_valid_date($date)) {

    echo "Valid date.";

} else {

    echo "Please enter a valid date.";
}
```

### Key Idea

Date validation asks:

> Is this actually a valid date?

---

# 15. Comparing Dates

Sometimes we also need to compare one date with another.

PHP's:

```php
strtotime()
```

can convert a date into a value that can be compared.

For example:

```php
$event_date = "2026-12-15";

$converted_event_date =
    strtotime($event_date);

$converted_today =
    strtotime(date("Y-m-d"));
```

Now we can compare them:

```php
if (
    $converted_event_date >
    $converted_today
) {

    echo "The event is in the future.";
}
```


---

# 16. Checking How Far Away a Date Is

Sometimes a date needs to be a certain number of days in the future.

Suppose an event must be booked at least **7 days in advance**.

We can create the earliest allowed date:

```php
$minimum_date =
    strtotime("+7 days");
```

Then convert the submitted date:

```php
$event_date =
    strtotime($date);
```

Now compare them:

```php
if ($event_date < $minimum_date) {

    echo "Please book at least 7 days in advance.";
}
```
Full code:

```php
<?php

$today = "2026-10-01";

$event_date = "2026-10-03";

// Convert today to timestamp
$today = strtotime($today);

// Add 7 days to today
$minimum_date = strtotime("+7 days", $today);

// Convert event date to timestamp
$event_date = strtotime($event_date);

// Check if event is at least 7 days away
if ($event_date < $minimum_date) {

    echo "Please book at least 7 days in advance.";

} else {

    echo "This date is allowed.";
}

?>
```

strtotime() can understand many human-readable date/time phrases, which is why it's convenient.

```
strtotime("+7 days");
strtotime("+21 days");
strtotime("+1 week");
strtotime("+2 weeks");
strtotime("+1 month");
strtotime("+1 year");


strtotime("-7 days");
strtotime("-1 month");
strtotime("-1 year");
```

---

# 17. Handling Multiple Validation Errors

A form may contain several errors at the same time.

Instead of displaying only one error, we can collect them.

Start with:

```php
$message = "";
```

Then add errors:

```php
if (empty($name)) {

    $message .=
        "<p>Please enter your name.</p>";
}
```

Notice:

```php
.=
```

This adds text to the existing string.

It does not replace what was already there.

For example:

```php
$message = "";

$message .= "<p>Name is required.</p>";

$message .= "<p>Phone is required.</p>";
```

Now `$message` contains both messages.

```
<?php

$message = "";

if (isset($_POST["submit"])) {

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);

    if (empty($name)) {

        $message .= "<p>Please enter your name.</p>";
    }

    if (empty($phone)) {

        $message .= "<p>Please enter your phone number.</p>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Validation Errors</title>
</head>

<body>

    <?php echo $message; ?>

    <form method="POST">

        <label for="name">Name:</label>
        <input
            type="text"
            id="name"
            name="name">

        <br><br>

        <label for="phone">Phone:</label>
        <input
            type="text"
            id="phone"
            name="phone">

        <br><br>

        <button type="submit" name="submit">
            Submit
        </button>

    </form>

</body>

</html>
```
---

# 18. Tracking Whether Everything Is Valid

We also need a way to know whether the entire form passed validation.

We can start with:

```php
$all_good = TRUE;
```

This means:

> We assume everything is valid unless we find a problem.

Then:

```php
if (empty($name)) {

    $all_good = FALSE;

    $message .=
        "<p>Please enter your name.</p>";
}
```

Every time we find an error:

```php
$all_good = FALSE;
```

At the end:

```php
if ($all_good == TRUE) {

    echo "Everything is valid.";
}
```


---
# 19. Complete Example — Workshop Registration

Now let's put the validation concepts together.

This example is intentionally larger than the previous examples.

Imagine we are creating a registration form for a programming workshop.

The user must provide:

- Their name.
- Their phone number.
- Their preferred workshop date.

Our requirements are:

```text
Name
- Required
- Maximum 60 characters

Phone
- Required
- Format: 780-555-1234

Workshop Date
- Required
- Must be a valid date
- Must be at least 7 days from today
```

---

## Complete PHP Example

```php
<?php

function has_valid_phone_format($phone) {

    $phone_regex =
        "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";

    return preg_match(
        $phone_regex,
        $phone
    );
}


function is_valid_date($user_date) {

    $date =
        date_parse_from_format(
            "Y-m-d",
            $user_date
        );

    return (
        $date["error_count"] == 0 &&
        $date["warning_count"] == 0
    );
}


$name = "";
$phone = "";
$workshop_date = "";

$message = "";


if (isset($_POST["submit"])) {

    $name = trim($_POST["name"]);

    $phone = trim($_POST["phone"]);

    $workshop_date =
        trim($_POST["workshop_date"]);


    // Validate name

    if (empty($name)) {

        $message .=
            "<p>Name is required.</p>";

    } elseif (strlen($name) > 60) {

        $message .=
            "<p>Name cannot be longer than 60 characters.</p>";
    }


    // Validate phone

    if (empty($phone)) {

        $message .=
            "<p>Phone number is required.</p>";

    } elseif (
        has_valid_phone_format($phone) == 0
    ) {

        $message .=
            "<p>Please use the format 780-555-1234.</p>";
    }


    // Validate workshop date

    if (empty($workshop_date)) {

        $message .=
            "<p>Please enter a workshop date.</p>";

    } elseif (
        is_valid_date($workshop_date) == FALSE
    ) {

        $message .=
            "<p>Please enter a valid date.</p>";

    } else {

        $today =
            strtotime(date("Y-m-d"));

        $minimum_date =
            strtotime("+7 days", $today);

        $converted_workshop_date =
            strtotime($workshop_date);

        if (
            $converted_workshop_date <
            $minimum_date
        ) {

            $message .=
                "<p>Please select a date at least 7 days from today.</p>";
        }
    }


    // Everything passed validation

    if (empty($message)) {

        echo "<p>Registration successful!</p>";

        echo "<p>Name: $name</p>";

        echo "<p>Phone: $phone</p>";

        echo "<p>Workshop Date: $workshop_date</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Workshop Registration
    </title>

</head>

<body>

    <h1>
        Workshop Registration
    </h1>

    <?php echo $message; ?>

    <form method="POST">

        <div>

            <label for="name">
                Name:
            </label>

            <input
                type="text"
                id="name"
                name="name"
            >

        </div>


        <div>

            <label for="phone">
                Phone:
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="780-555-1234"
            >

        </div>


        <div>

            <label for="workshop_date">
                Preferred Workshop Date:
            </label>

            <input
                type="text"
                id="workshop_date"
                name="workshop_date"
                placeholder="YYYY-MM-DD"
            >

        </div>


        <button
            type="submit"
            name="submit"
        >
            Register
        </button>

    </form>

</body>

</html>
```

---

## Understanding the Complete Example

This example combines several validation techniques that we have learned.

### Name

The name:

- Is required.
- Cannot be longer than 60 characters.

```php
if (empty($name)) {

    $message .=
        "<p>Name is required.</p>";

} elseif (strlen($name) > 60) {

    $message .=
        "<p>Name cannot be longer than 60 characters.</p>";
}
```

---

### Phone

The phone number:

- Is required.
- Must match the expected format.

```text
780-555-1234
```

We use our custom function to check the format:

```php
has_valid_phone_format($phone)
```

---

### Workshop Date

The workshop date:

- Is required.
- Must be a valid date.
- Must be at least 7 days from today.

We calculate the minimum allowed date:

```php
$today =
    strtotime(date("Y-m-d"));

$minimum_date =
    strtotime("+7 days", $today);
```

Then we compare the workshop date with the minimum date.

---

### Collecting Errors

Each validation error is added to `$message`:

```php
$message .=
    "<p>Error message here.</p>";
```

The `.=` operator adds the new message without removing the previous message.

This allows us to display several validation errors at the same time.

---

### Checking if Validation Passed

At the end, we check whether `$message` is empty:

```php
if (empty($message)) {

    echo "<p>Registration successful!</p>";

    echo "<p>Name: $name</p>";

    echo "<p>Phone: $phone</p>";

    echo "<p>Workshop Date: $workshop_date</p>";
}
```

If `$message` is empty, no validation errors were found.

The submitted information is then displayed.

## Exercise — Event Registration Validation

Create a PHP form for registering for a community event.

The form should ask the user for:

- Their name.
- Their email address.
- Their event date.

### Requirements

```text
Name
- Required
- Maximum 50 characters

Email
- Required
- Must be a valid email address

Event Date
- Required
- Must be a valid date
- Must be at least 5 days from today
```

### Instructions

1. Create an empty `$message` variable.

2. Retrieve the submitted form values using `$_POST`.

3. Use `trim()` on the submitted values.

4. Validate the name using:

```php
empty()
strlen()
```

5. Validate the email using:

```php
filter_var(
    $email,
    FILTER_VALIDATE_EMAIL
)
```

6. Validate the event date using the `is_valid_date()` function from this lesson.

7. Use `strtotime()` to make sure the event date is at least `5` days from today.

8. Add each error to `$message` using:

```php
$message .=
    "<p>Error message here.</p>";
```

9. If `$message` is empty, display:

```text
Registration successful!
```

Also display the submitted name, email, and event date.

### Example

If the user enters:

```text
Name: Sarah Smith
Email: sarah@example.com
Event Date: 2026-10-10
```

and all values pass validation, the page should display:

```text
Registration successful!

Name: Sarah Smith
Email: sarah@example.com
Event Date: 2026-10-10
```

If several values are invalid, display all validation errors.
