# Using `include()`

In `01-php-basics.md`, we learned that PHP and HTML can be written together in the same `.php` file.

For example:

```php
<?php
$name = "John";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Basics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">

    <h1>Welcome, <?= $name ?>!</h1>

    <p class="lead">This is my first PHP page.</p>

</body>

</html>
```

This works, but imagine that our website has several pages.

Every page would need some of the same code, such as:

- `<html>`
- `<head>`
- Bootstrap
- `<body>`
- navigation
- footer

Instead of copying and pasting the same code, PHP allows us to reuse code with `include()`.

---

## Creating a Header

We can move the beginning of our HTML document into a separate file.

Create:

```text
includes/header.php
```

Inside `header.php`:

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">

    <main class="py-5">
```

Now our common HTML and Bootstrap setup can be reused on different pages.

---

## Including the Header

Inside `index.php`, create a `$title` variable and include the header:

```php
<?php

$title = "Home Page";

include('includes/header.php');

?>
```

When PHP reaches `include()`, it uses the contents of `header.php` at that location.

Because `$title` was created before the `include()`, our `header.php` can use it here:

```php
<title><?= $title ?></title>
```

---

## Creating a Footer

We can also move the closing HTML into another file.

Create:

```text
includes/footer.php
```

Inside `footer.php`:

```html
    </main>

</body>

</html>
```

Then include it at the bottom of the page:

```php
<?php include('includes/footer.php'); ?>
```

---

## Putting It Together

Our project now looks like this:

```text
class-code/
│
├── index.php
│
└── includes/
    ├── header.php
    └── footer.php
```

Our `index.php` can now focus on the content for that page:

```php
<?php

$title = "Home Page";

include('includes/header.php');

?>

<h1><?= $title ?></h1>

<p class="lead">Welcome to my website!</p>

<a href="#" class="btn btn-primary">Learn More</a>

<?php include('includes/footer.php'); ?>
```

PHP combines the files together:

```text
header.php
    +
index.php
    +
footer.php
    ↓
Complete HTML page
```

---

## Using Different Page Titles

Because `$title` is created before `header.php` is included, each page can have its own title.

For example:

```php
<?php

$title = "About Us";

include('includes/header.php');

?>
```

The same `header.php` will now output:

```html
<title>About Us</title>
```

Another page could use:

```php
<?php

$title = "Contact Us";

include('includes/header.php');

?>
```

We can reuse the same header while changing the title for each page.

---

## Why Use `include()`?

`include()` helps us:

- avoid repeating the same code
- reuse headers and footers
- keep our pages smaller
- make changes in one place

For example, if we need to update our Bootstrap link later, we can change it once inside `header.php` instead of changing every page.

> **`include()` lets us reuse code from another file instead of copying and pasting the same code on every page.**
