# Lesson 23 — Secure Login, Logout, and Protected Admin Pages

In Lesson 21, we learned how PHP sessions remember information across multiple pages.

In Lesson 22, we learned how to create administrator accounts, hash passwords using `password_hash()`, and verify passwords using `password_verify()`.

In this lesson, we'll combine these concepts to build an administrator login system. Administrators will be able to log in, access protected pages, and log out.

We'll continue working with the application and administrator accounts created in Lesson 22.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain how authentication works with PHP sessions.
- Create a login form that verifies administrator credentials.
- Use `password_verify()` to check passwords stored in MySQL.
- Store administrator information in a PHP session.
- Protect pages so only logged-in administrators can access them.
- Create a logout page that ends the administrator's session.


## 2. Understanding Authentication and Sessions

### What is authentication?

**Authentication** is the process of checking whether someone is who they claim to be.

For example, an administrator enters a username and password. PHP checks the submitted information against an account stored in MySQL.

If the credentials are correct, the administrator can log in.

**Authorization** determines which pages or actions an authenticated user is allowed to access.

In this lesson, all administrator accounts have the same access. We won't create different administrator roles.

### How does a login system work?

A login system follows these steps:

1. The administrator enters a username and password.
2. PHP retrieves the matching administrator account from MySQL.
3. `password_verify()` checks the submitted password against the stored hash.
4. If the password is correct, PHP creates a login session.
5. The administrator is redirected to a protected dashboard.
6. Protected pages check the session before displaying their content.
7. When the administrator logs out, PHP ends the session.

### Remembering a logged-in administrator

In Lesson 21, we used `$_SESSION` to store information across multiple pages.

We'll use the same approach to remember which administrator has logged in.

For example:

```php
$_SESSION['admin_id'] = 1;
$_SESSION['username'] = 'alex';
```

These values allow PHP to identify the logged-in administrator when they visit another page.

We don't need to store the administrator's password or password hash in the session.

### Checking whether someone is logged in

We can check whether the session contains an administrator ID:

```php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
```

If the administrator ID is missing, the visitor is redirected to the login page.

This is how we'll protect administrator pages.

## 3. Preparing the Login Application

We'll continue using the application created in Lesson 22.

### Project structure

Our project will now contain these files:

```text
22-password-hashing/
├── private/
│   ├── connect.php
│   └── auth.php
└── public/
    ├── create-account.php
    ├── verify-password.php
    ├── login.php
    ├── dashboard.php
    ├── logout.php
    └── products.php
```

The files from Lesson 22 remain unchanged.

We'll add these files:

| File | Purpose |
|---|---|
| `private/auth.php` | Checks whether an administrator is logged in |
| `public/login.php` | Verifies credentials and starts a login session |
| `public/dashboard.php` | Displays a protected administrator dashboard |
| `public/logout.php` | Ends the administrator's session |
| `public/products.php` | Additional protected page for Exercise 27 |

The `products.php` file will be created during the exercise.

### Reusing the database

We'll use the existing database and table from Lesson 22.

| Item | Name |
|---|---|
| Database | `dmit2025` |
| Table | `lesson22_admins` |
| Connection | MySQLi |

The table already contains administrator accounts created in Lesson 22.

```sql
CREATE TABLE IF NOT EXISTS lesson22_admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);
```

You do not need to run this SQL again if the table already exists.

### Reusing the database connection

We'll continue using `private/connect.php` from Lesson 22.

```php
<?php
$host = 'mysql';
$username = 'student';
$password = 'student';
$database = 'dmit2025';

$conn = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

return $conn;
```

The connection file returns `$conn`, which we can use to execute MySQLi queries.

Before continuing, make sure you have at least one administrator account in the `lesson22_admins` table.

## 4. Creating the Login Page

We'll create a login form that accepts an administrator's username and password.

When the form is submitted, PHP will:

1. Receive the submitted username and password.
2. Check that neither field is empty.
3. Retrieve the administrator account from MySQL.
4. Verify the password using `password_verify()`.
5. Start a login session if the credentials are correct.
6. Redirect the administrator to the dashboard.

If the credentials are incorrect, the page will display an error message.

### Complete `public/login.php`

```php
<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$conn = require '../private/connect.php';

$message = '';

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $message = 'Please enter a username and password.';
    } else {
        $sql = "SELECT admin_id, username, password_hash
                FROM lesson22_admins
                WHERE username = ?";

        $statement = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($statement, "s", $username);

        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);

        $admin = mysqli_fetch_assoc($result);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];

            header('Location: dashboard.php');
            exit();
        } else {
            $message = 'Incorrect username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Administrator Login</h1>

    <p>Enter your administrator username and password to log in.</p>

    <?php if ($message !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">

        <div class="mb-3">
            <label for="username" class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                id="username"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Log In
        </button>

    </form>

</main>
</body>
</html>
```

### Understanding the important code

**Step 1: Start the session**

```php
session_start();
```

This starts a new session or resumes an existing one. It must be called before HTML output.

**Step 2: Check whether the administrator is already logged in**

```php
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}
```

If the administrator is already logged in, PHP redirects them to the dashboard. This prevents logged-in administrators from seeing the login form again.

**Step 3: Retrieve the administrator account**

This is the same prepared SELECT query we used in Lesson 22.

```php
$sql = "SELECT admin_id, username, password_hash
        FROM lesson22_admins
        WHERE username = ?";

$statement = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($statement, "s", $username);

mysqli_stmt_execute($statement);

$result = mysqli_stmt_get_result($statement);

$admin = mysqli_fetch_assoc($result);
```

The query searches for an administrator with the submitted username. If a matching account exists, `$admin` contains its information.

**Step 4: Verify the password**

```php
if ($admin && password_verify($password, $admin['password_hash'])) {
```

This checks two conditions:

- An administrator account was found.
- The submitted password matches the stored hash.

Both conditions must be true for the administrator to log in.

**Step 5: Create the login session**

```php
session_regenerate_id(true);

$_SESSION['admin_id'] = $admin['admin_id'];
$_SESSION['username'] = $admin['username'];
```

`session_regenerate_id(true)` generates a new session ID after successful authentication. This helps protect against session fixation attacks.

We then store the administrator's ID and username in `$_SESSION`. We do not store their password.

**Step 6: Redirect to the dashboard**

```php
header('Location: dashboard.php');
exit();
```

The `header()` function redirects the administrator to another page. `exit()` stops the current PHP script after the redirect.

**Step 7: Handle incorrect credentials**

```php
$message = 'Incorrect username or password.';
```

We use the same error message whether the username doesn't exist or the password is incorrect. This avoids revealing which usernames are registered.

## 5. Protecting Administrator Pages

Logging in is only part of the process.

We also need to prevent visitors from opening administrator pages without logging in.

For example, someone could try entering the dashboard URL directly into their browser.

We must check the session before displaying protected content.

### Step 1: Create `private/auth.php`

Instead of repeating the same session check on every administrator page, we'll create a reusable file.

```php
<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
```

### Understanding the code

First, we start or resume the session:

```php
session_start();
```

Then we check whether the administrator ID exists:

```php
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
```

If the administrator is not logged in, PHP redirects them to `login.php`.

This example assumes protected pages are in the same `public` folder as `login.php`. 
### Step 2: Create `public/dashboard.php`

The dashboard will be available only to logged-in administrators.

```php
<?php
require '../private/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Administrator Dashboard</h1>

    <div class="alert alert-success">
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!
    </div>

    <p>You are logged in as an administrator.</p>

    <p>This page is protected. Visitors must log in before they can access it.</p>

    <a href="logout.php" class="btn btn-danger">
        Log Out
    </a>

</main>
</body>
</html>
```

### Understanding the important code

At the beginning of the file, we include our authentication check:

```php
require '../private/auth.php';
```

This runs the code in `auth.php`.

If the visitor is not logged in, PHP redirects them to `login.php` before displaying the dashboard. If they are logged in, the rest of the page runs normally.

We display the administrator's username using:

```php
<?= htmlspecialchars($_SESSION['username']) ?>
```

`htmlspecialchars()` safely displays the username as text in HTML.

### Why protect every administrator page?

Hiding a link does not prevent someone from opening a page directly.

For example, hiding a link to `delete.php` does not stop a visitor from entering its URL.

Each protected administrator page must check whether the visitor is logged in.

For the Project, this includes pages that create, edit, or delete records. The authentication check should run before any database changes, not just before displaying the page.

Using `auth.php` makes this easier because we can reuse the same check across multiple pages.

## 6. Creating the Logout Page

Administrators also need a way to end their login sessions.

When someone logs out, PHP should:

1. Start or resume the existing session.
2. Clear the session variables.
3. Remove the session cookie.
4. Destroy the session.
5. Redirect the visitor to the login page.

### Complete `public/logout.php`

```php
<?php
session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite']
    ]);
}

session_destroy();

header('Location: login.php');
exit();
```

### Understanding the important code

**Step 1: Start the session**

```php
session_start();
```

We need to access the existing session before ending it.

**Step 2: Clear session variables**

```php
$_SESSION = [];
```

This removes the information stored in the current session, including the administrator ID and username.

**Step 3: Remove the session cookie**

```php
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite']
    ]);
}
```

PHP normally uses a browser cookie to identify a session. This code expires the session cookie using the same settings it was created with.

**Step 4: Destroy the session**

```php
session_destroy();
```

This removes the stored session data.

**Step 5: Redirect to login**

```php
header('Location: login.php');
exit();
```

After logging out, the visitor returns to the login page. If they try to open the dashboard again, the authentication check will redirect them back to login.

### Basic login security

Our application already uses several important security practices:

| Practice | Why it matters |
|---|---|
| Password hashes | Avoid storing readable passwords |
| Prepared statements | Help prevent SQL injection |
| Generic login errors | Avoid revealing registered usernames |
| Session ID regeneration | Helps prevent session fixation |
| Protected pages | Prevent access without authentication |
| Proper logout | Ends the current login session |
| HTTPS | Protects login information and session cookies in transit |


## 7. Testing the Login System

We'll test the complete application using an administrator account created in Lesson 22.

### Test 1: Successful login

1. Open `login.php`.
2. Enter a valid administrator username and password.
3. Click **Log In**.

**Expected result:** The browser redirects to `dashboard.php`, where the administrator's username is displayed.

### Test 2: Incorrect password

1. Log out if you're currently logged in.
2. Open `login.php`.
3. Enter a valid username with an incorrect password.
4. Click **Log In**.

**Expected result:** `Incorrect username or password.` The administrator remains on the login page.

### Test 3: Unknown username

Enter a username that doesn't exist in `lesson22_admins`.

**Expected result:** `Incorrect username or password.`

### Test 4: Access the dashboard without logging in

1. Log out.
2. Enter the URL for `dashboard.php` directly into your browser.

**Expected result:** PHP redirects you to `login.php`.

### Test 5: Open the login page while logged in

1. Log in successfully.
2. Enter the URL for `login.php` directly into your browser.

**Expected result:** PHP redirects you to `dashboard.php`.

### Test 6: Logout

1. Log in successfully.
2. Click **Log Out**.
3. Try opening `dashboard.php` again.

**Expected result:** You are redirected to the login page because your session has ended.

## 8. Exercise 27 — Protecting Another Page

Now practise applying session-based authentication to another administrator page.

### Your task

Create a new file named `public/products.php`.

This page will represent a protected products management page.


Your page should:

1. Include `private/auth.php` to check whether the administrator is logged in.
2. Display the heading **Manage Products**.
3. Display the logged-in administrator's username.
4. Include a link back to `dashboard.php`.
5. Include a link to `logout.php`.
6. Redirect visitors to `login.php` if they are not logged in.

You do not need to create a new database table or implement product CRUD operations for this exercise.

### Testing your page

| Test | Expected result |
|---|---|
| Open `products.php` without logging in | Redirect to `login.php` |
| Log in and open `products.php` | Display the protected page |
| Check the displayed username | Show the logged-in administrator |
| Click the dashboard link | Open `dashboard.php` |
| Log out and reopen `products.php` | Redirect to `login.php` |

### Applying this to the Project

In your  Project, you'll have administrator pages that allow users to create, edit, and delete records.

Those pages should only be accessible to authenticated administrators.

You can use the same `auth.php` approach to protect them.

Remember that the account-creation page from Lesson 22 was intended for classroom setup. It should be removed or restricted before deploying your application publicly.


In **Lesson 24**, we'll move on to image processing with PHP's GD library.
