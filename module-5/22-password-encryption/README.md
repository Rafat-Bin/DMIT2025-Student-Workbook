# Lesson 22 — Password Hashing in PHP

In Lesson 21, we learned how PHP sessions remember information across multiple pages.

In this lesson, we'll learn how to create and verify administrator passwords using PHP's built-in password functions.

We'll build a small application that creates administrator accounts and checks their passwords using a MySQL database.

In Lesson 23, we'll use these accounts to build a secure login system.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain why passwords are stored as hashes.
- Understand the difference between hashing and encryption.
- Create password hashes using `password_hash()`.
- Store password hashes in MySQL using prepared statements.
- Verify passwords using `password_verify()`.

## 2. Understanding Password Hashing

### What is password hashing?

Password hashing converts a password into a value called a **hash**.

For example:

```text
Password:
mypassword123

Password hash:
$2y$12$...
```

The hash shown above is shortened for illustration. An actual password hash is longer.

When an administrator creates an account, PHP generates a password hash and stores it in the database.

When the administrator enters their password later, PHP checks it against the stored hash.

**Password → `password_hash()` → MySQL stores the hash**

### Why do we use password hashes?

Consider this administrator table:

| admin_id | username | password |
|---|---|---|
| 1 | alex | `mypassword123` |
| 2 | sam | `summer2026` |

The passwords are readable by anyone who can access the table.

A safer approach is to store password hashes instead.

| admin_id | username | password_hash |
|---|---|---|
| 1 | alex | `$2y$12$...` |
| 2 | sam | `$2y$12$...` |

These are shortened examples. The actual hashes contain more characters.

Password hashes are designed to be difficult to reverse, helping protect passwords if database records are exposed.

### Hashing versus encryption

Hashing and encryption are different.

| Hashing | Encryption |
|---|---|
| One-way process | Reversible with a key |
| Used to verify passwords | Used to recover protected information |
| Original password cannot be directly recovered | Original information can be decrypted |

PHP can verify a password without recovering it from the hash.

### What is a salt?

A **salt** is random data used when hashing a password.

It helps ensure that two accounts using the same password receive different hashes.

PHP automatically generates and manages salts when we use `password_hash()`.

We don't need to create salts ourselves.

## 3. Hashing and Verifying Passwords

PHP provides two functions for working with passwords:

| Function | Purpose |
|---|---|
| `password_hash()` | Creates a password hash |
| `password_verify()` | Checks a password against a stored hash |

### Creating a password hash

```php
$password = 'mypassword123';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash;
```

The `password_hash()` function accepts two arguments:

- `$password` — the password to hash.
- `PASSWORD_DEFAULT` — tells PHP to use its default password-hashing algorithm.

Each time we run this code, PHP generates a new hash.

Even when the password stays the same, the resulting hash will usually be different because PHP uses a new random salt.

### Verifying a password

To check a password, we use `password_verify()`.

```php
$password = 'mypassword123';

$hash = password_hash($password, PASSWORD_DEFAULT);

if (password_verify('mypassword123', $hash)) {
    echo 'Password is correct.';
} else {
    echo 'Incorrect password.';
}
```

The result is:

```text
Password is correct.
```

Now try an incorrect password:

```php
if (password_verify('wrongpassword', $hash)) {
    echo 'Password is correct.';
} else {
    echo 'Incorrect password.';
}
```

The result is:

```text
Incorrect password.
```

`password_verify()` returns `true` when the password matches and `false` when it does not.

We should not hash the submitted password again and compare the two hash strings. Since PHP generates a new salt, the hashes will usually be different.

Instead, use:

```php
password_verify($password, $storedHash);
```

## 4. Preparing the Database

We'll create a small application with the following files:

```text
22-password-hashing/
├── private/
│   └── connect.php
└── public/
    ├── create-account.php
    └── verify-password.php
```

The application has two pages:

- `create-account.php` creates administrator accounts.
- `verify-password.php` checks administrator passwords.

We'll use the existing `dmit2025` database and add a table named `lesson22_admins`.

### Step 1: Create the administrator table

Open **phpMyAdmin** in your browser and select the existing **`dmit2025`** database.

1. Click the **SQL** tab.
2. Copy the following SQL code.
3. Paste it into the SQL editor.
4. Click **Go** to execute the code.

```sql
CREATE TABLE IF NOT EXISTS lesson22_admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);
```

After executing the SQL, refresh the table list for `dmit2025`.

You should see the `lesson22_admins` table in the existing `dmit2025` database.

The table will be empty. We'll add administrator records using PHP.

### Understanding the table

| Column | Purpose |
|---|---|
| `admin_id` | Unique identifier for each administrator |
| `username` | Administrator's username |
| `password_hash` | Stores the password hash |

The `UNIQUE` constraint prevents duplicate usernames.

We use `VARCHAR(255)` for `password_hash` to provide enough space for hashes generated by PHP's password-hashing algorithms.

### Step 2: Connect to MySQL

Create `private/connect.php`.

We'll use the same MySQLi connection approach from Module 4 and connect to the existing `dmit2025` database.

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

The connection file returns `$conn`. In our PHP pages, we'll assign that returned connection using `$conn = require '../private/connect.php';`. 

## 5. Creating an Administrator Account

We'll create a form that accepts a username and password.

When the form is submitted, PHP will:

1. Receive the submitted values.
2. Check that the fields are not empty.
3. Hash the password.
4. Insert the username and password hash into MySQL.
5. Display a message.



### Complete `public/create-account.php`

```php
<?php
$conn = require '../private/connect.php';

$message = '';

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $message = 'Please enter a username and password.';
    } elseif (strlen($username) > 50) {
        $message = 'Username must be 50 characters or fewer.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO lesson22_admins (username, password_hash)
                VALUES (?, ?)";

        try {
            $statement = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($statement, "ss", $username, $hash);

            mysqli_stmt_execute($statement);

            $message = 'Administrator account created successfully.';
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $message = 'Unable to create the account. Check the username or try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Administrator Account</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Create Administrator Account</h1>

    <p>Create an administrator account with a hashed password.</p>

    <?php if ($message !== ''): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="create-account.php" method="post">

        <div class="mb-3">
            <label for="username" class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                id="username"
                class="form-control"
                maxlength="50"
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
            Create Account
        </button>

    </form>

    <a href="verify-password.php" class="btn btn-link mt-3">
        Test Password Verification
    </a>

</main>
</body>
</html>
```

### Understanding the important code

First, we create the password hash:

```php
$hash = password_hash($password, PASSWORD_DEFAULT);
```

Then we prepare the INSERT statement:

```php
$sql = "INSERT INTO lesson22_admins (username, password_hash)
        VALUES (?, ?)";
```

Finally, we execute it:

```php
$statement = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($statement, "ss", $username, $hash);

mysqli_stmt_execute($statement);
```

Notice that we're storing `$hash`, not `$password`.

The `?` placeholders represent the values to insert. `mysqli_stmt_bind_param()` binds the username and password hash to those placeholders. The `"ss"` means both values are strings. The prepared statement safely handles the values being inserted into the database.

### Check the database

Open `create-account.php` in your browser and create an administrator account.

Next, open phpMyAdmin and browse the `lesson22_admins` table.

You should see a record similar to this:

| admin_id | username | password_hash |
|---|---|---|
| 1 | alex | `$2y$12$...` |

The actual password hash will be longer than the example.

## 6. Verifying an Administrator Password

Now we'll create a page that checks a submitted username and password against the stored administrator account.

When the form is submitted, PHP will:

1. Receive the username and password.
2. Find the administrator record in MySQL.
3. Use `password_verify()` to check the password.
4. Display the result.

This page only verifies credentials. We'll add login sessions in Lesson 23.

### Complete `public/verify-password.php`

```php
<?php
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
            $message = 'Password is correct.';
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
    <title>Verify Administrator Password</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body>
<main class="container py-5">

    <h1>Verify Administrator Password</h1>

    <p>Enter an administrator username and password to test verification.</p>

    <?php if ($message !== ''): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="verify-password.php" method="post">

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
            Verify Password
        </button>

    </form>

    <a href="create-account.php" class="btn btn-link mt-3">
        Create Another Account
    </a>

</main>
</body>
</html>
```

### Understanding the important code

First, we retrieve the administrator record:

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

The `?` placeholder represents the username. `mysqli_stmt_bind_param()` binds it as a string (`"s"`). After the statement runs, `mysqli_stmt_get_result()` gets the query result, and `mysqli_fetch_assoc()` returns the matching administrator record as an associative array, or `null` if none was found.

Next, we check the password:

```php
if ($admin && password_verify($password, $admin['password_hash'])) {
    $message = 'Password is correct.';
} else {
    $message = 'Incorrect username or password.';
}
```

The `$admin` condition checks whether an account was found.

If the account exists, `password_verify()` checks the submitted password against the stored hash.

The same error message is used for an unknown username and an incorrect password. This avoids revealing which usernames exist in the database.

## 7. Exercise 26

Now practise creating and verifying multiple administrator accounts.

**Your task:**

1. Create two administrator accounts with different usernames.
2. Give both accounts the same password.
3. Open phpMyAdmin and compare their stored password hashes.
4. Verify both accounts using the correct password.
5. Try an incorrect password and confirm that verification fails.


You don't need to create any additional PHP files.
