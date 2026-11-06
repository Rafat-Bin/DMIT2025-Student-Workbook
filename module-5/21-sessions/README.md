# Lesson 21 — Remembering States with PHP Sessions

In previous lessons, we used HTML forms to send data to PHP and MySQL databases to store information permanently.

But what if we want PHP to remember something while a visitor moves between pages?

For example, after an administrator logs in, we don't want them to enter their username and password every time they open another page.

PHP **sessions** help us remember information across multiple page requests.

In this lesson, we'll build a simple application that remembers a visitor's name. The visitor can enter a name, view it on another page, and clear the saved information.

We'll use these same concepts when building a secure administrator login system in Lesson 23.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain what PHP sessions are and why they are useful.
- Start a session using `session_start()`.
- Store and retrieve values using `$_SESSION`.
- Access session values across multiple PHP pages.
- Clear session information using `session_unset()` and `session_destroy()`.

## 2. Understanding PHP Sessions

### Why do we need sessions?

PHP processes each page request separately.

Imagine a visitor enters their name on `index.php`. PHP receives the name through a form and displays a greeting. But when the visitor opens `profile.php`, the original form submission is no longer available.

Without another way to remember the name, PHP would need the visitor to enter it again.

A **session** solves this problem by allowing PHP to remember information between requests.

```text
index.php                 PHP Session                profile.php
Save name: Alex    --->   username = Alex    --->   Read name: Alex
```

PHP usually uses a session ID stored in a browser cookie to recognize the visitor. The session values themselves are stored on the server.

We don't need to manage cookies directly in this lesson.

### Sessions versus databases

Sessions and databases serve different purposes.

| Sessions | Databases |
|---|---|
| Remember temporary information | Store information permanently |
| Associated with a visitor's session | Available across visits and users, subject to access controls |
| Useful for login state and temporary preferences | Useful for catalogue records and administrator accounts |
| Data may expire or be destroyed | Data remains until it is changed or deleted |

A session does not necessarily end immediately when someone closes their browser. Its lifetime depends on browser and server settings.

### Starting a session

Before working with session variables, call:

```php
session_start();
```

This starts a new session or resumes an existing session.

Place it at the beginning of the PHP file, **before any HTML is sent to the browser**.

### Storing a session value

PHP provides the `$_SESSION` superglobal array. We can store a name like this:

```php
$_SESSION['username'] = 'Alex';
```

The value is now associated with the visitor's session.

### Reading a session value

We can retrieve the saved name:

```php
echo $_SESSION['username'];
```

However, the visitor might not have saved a name yet. Check whether the value exists before reading it:

```php
if (isset($_SESSION['username'])) {
    echo $_SESSION['username'];
}
```

We already used `isset()` in earlier lessons to check form values. Here, we're using it to check a session value.

## 3. Remembering a Name

Let's build an application that remembers a visitor's name.

Create the following files:

```text
21-remembering-states/
└── public/
    ├── index.php
    └── profile.php
```

We'll start with `index.php`.

### Step 1: Save the submitted name

When the visitor submits the form, we'll store their name in the session.

```php
<?php
session_start();

if (isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if ($username !== '') {
        $_SESSION['username'] = $username;

        header('Location: index.php');
        exit();
    }
}
?>
```

Here's what happens:

1. `session_start()` starts or resumes the session.
2. `isset($_POST['username'])` checks whether the form submitted a name.
3. `trim()` removes spaces from the beginning and end.
4. We make sure the name is not empty.
5. We save the name in `$_SESSION['username']`.
6. We redirect to `index.php` so refreshing the page won't resubmit the form.

The key difference is that `$_POST` contains data from the current form submission, while `$_SESSION` can remember data for later requests.

### Step 2: Display the saved name

If a name has been saved, display a greeting:

```php
<?php if (isset($_SESSION['username'])): ?>

    <h1>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

<?php else: ?>

    <h1>What's Your Name?</h1>

<?php endif; ?>
```

We use `htmlspecialchars()` because the name comes from user input. This prevents HTML entered by the visitor from being interpreted as page markup.

If no name has been saved, we'll display the form instead.

### Step 3: Complete `index.php`

The following is the **complete working page**. It includes the form, greeting, and a button to forget the saved name.

```php
<?php
session_start();

// Save the submitted name.
if (isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if ($username !== '') {
        $_SESSION['username'] = $username;

        header('Location: index.php');
        exit();
    }
}

// Clear the session when requested.
if (isset($_POST['forget'])) {
    session_unset();
    session_destroy();

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remembering States</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <?php if (isset($_SESSION['username'])): ?>

        <h1>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

        <p>Your name has been remembered using a PHP session.</p>

        <a href="profile.php" class="btn btn-primary">
            View Profile
        </a>

        <form action="index.php" method="post" class="mt-3">
            <button
                type="submit"
                name="forget"
                value="1"
                class="btn btn-danger"
            >
                Forget Me
            </button>
        </form>

    <?php else: ?>

        <h1>What's Your Name?</h1>

        <p>Enter your name so PHP can remember it.</p>

        <form action="index.php" method="post">

            <div class="mb-3">
                <label for="username" class="form-label">
                    Your Name
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Remember Me
            </button>

        </form>

    <?php endif; ?>

</main>
</body>
</html>
```

### How the page behaves

1. Open `index.php`. You'll see the name-entry form.
2. Enter a name and click **Remember Me**.
3. The page redirects and displays a greeting.
4. Refresh the page. The name remains because it is stored in the session.
5. Click **View Profile** to see the name on another page once we create `profile.php`.

## 4. Using Sessions Across Pages

Now let's see why sessions are useful when a website has more than one page.

We'll create `profile.php` to display the name saved by `index.php`. We don't need another form, and we don't need to send the name through the URL.

Both pages can access the same session information.

### Complete `profile.php`

```php
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Your Profile</h1>

    <?php if (isset($_SESSION['username'])): ?>

        <p>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</p>

        <p>
            Your name is available on this page because
            it was remembered in the session.
        </p>

    <?php else: ?>

        <p>No name has been saved.</p>

    <?php endif; ?>

    <a href="index.php" class="btn btn-primary">
        Back to Home
    </a>

</main>
</body>
</html>
```

### How does this work?

`profile.php` also begins with `session_start()`, which lets PHP resume the visitor's existing session.

Because `index.php` saved the name using `$_SESSION['username'] = $username;`, `profile.php` can retrieve that same value.

Try navigating between the two pages. The name should remain available without submitting the form again.

This is the same idea we'll use in Lesson 23: an administrator will log in on one page, and other pages will check the login session.

## 5. Clearing a Session

Sometimes we want PHP to stop remembering information. For example, when an administrator logs out, their login information should no longer remain in the session.

Our application has a **Forget Me** button for this purpose.

Look at the form in `index.php`:

```html
<form action="index.php" method="post">
    <button type="submit" name="forget" value="1">
        Forget Me
    </button>
</form>
```

When the button is clicked, the form submits a `forget` value. At the top of `index.php`, we check for it:

```php
if (isset($_POST['forget'])) {
    session_unset();
    session_destroy();

    header('Location: index.php');
    exit();
}
```

| Function | Purpose |
|---|---|
| `session_unset()` | Removes all variables from the current session |
| `session_destroy()` | Destroys the session data stored on the server |

After clearing the session, the redirect loads `index.php` again. Because the name is no longer stored, the page displays the name-entry form.

**Note:** `session_destroy()` does not automatically remove the session cookie from the browser. For this simple demonstration, clearing the variables and destroying the session data is sufficient. We'll cover secure logout handling in Lesson 23.


## 6. Exercise — Remembering a Preference

In this exercise, you'll extend the application to remember another piece of information.

### Your task

Modify the application so visitors can choose their **favourite colour** as well as enter their name.

Requirements:

1. Add a `<select>` field to the form in `index.php` with at least three colours.
2. Save the selected colour in `$_SESSION['colour']`.
3. Display the favourite colour on `index.php` and `profile.php`.
4. Make sure both the name and colour remain available when navigating between pages.
5. Make sure clicking **Forget Me** clears both values.

### Hint

You can save the selected colour in the same way you saved the name:

```php
$_SESSION['colour'] = $_POST['colour'];
```

Use a fixed list of colour choices, and only save a value that matches one of those choices.

You do not need a database, additional pages, or custom functions.

### Summary

In this lesson, we learned how PHP sessions remember information across multiple page requests.

| Concept | Purpose |
|---|---|
| `session_start()` | Start or resume a session |
| `$_SESSION` | Store and retrieve information |
| `isset()` | Check whether a session value exists |
| `session_unset()` | Clear session variables |
| `session_destroy()` | Destroy stored session data |

