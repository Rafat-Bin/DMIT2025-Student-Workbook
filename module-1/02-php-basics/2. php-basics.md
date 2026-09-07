## Using `include()`

In 1. php-basics.md, we learned that PHP and HTML can be written together in the same `.php` file.

For example:

```php
<?php
$name = "John";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Basics</title>
</head>
<body>

    <h1>Welcome, <?= $name ?>!</h1>

    <p>This is my first PHP page.</p>

</body>
</html>
```

This works well, but imagine that our website has several pages.

Each page might need the same:

- `<html>`
- `<head>`
- `<body>`
- navigation
- footer

We could copy and paste this HTML into every page, but that would create a lot of repeated code.

PHP gives us a way to reuse code with `include()`.

---

## Creating a Header

We can move the HTML at the beginning of our page into a separate file.

Create a file called:

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
</head>

<body>
```

Now we can include this file in another PHP page:

```php
<?php

$title = "PHP Basics";

include('includes/header.php');

?>
```

When PHP reaches `include()`, it inserts the contents of `header.php` into the page.

You can think of it like this:

```text
index.php
    ↓
include header.php
    ↓
display page content
```

---

## Creating a Footer

We can do the same thing with the HTML at the bottom of every page.

Create:

```text
includes/footer.php
```

Inside `footer.php`:

```html
</body>
</html>
```

Then include it at the bottom of the page:

```php
<?php include('includes/footer.php'); ?>
```

---

## Putting It Together

Our project could now look like this:

```text
project/
│
├── index.php
│
└── includes/
    ├── header.php
    └── footer.php
```

Our `index.php` becomes much smaller:

```php
<?php

$title = "PHP Basics";

include('includes/header.php');

?>

<h1>Welcome!</h1>

<p>This is the main content of the page.</p>

<?php include('includes/footer.php'); ?>
```

The browser still receives one complete HTML page.

PHP simply combines the files for us:

```text
header.php
    +
index.php content
    +
footer.php
    ↓
Complete HTML page
```

---

## Using Variables in Included Files

An included `.php` file can use variables that were created before `include()`.

For example, in `index.php`:

```php
<?php

$title = "Home Page";

include('includes/header.php');

?>
```

Then `header.php` can use `$title`:

```php
<title><?= $title ?></title>
```

The browser will receive:

```html
<title>Home Page</title>
```

We could use a different title on another page:

```php
<?php

$title = "About Us";

include('includes/header.php');

?>
```

Now the title becomes:

```html
<title>About Us</title>
```

This allows us to reuse the same `header.php` while still changing information on each page.

---

## `.html` or `.php`?

You can include both HTML and PHP files.

If the file contains only HTML, it could be:

```text
footer.html
```

If the included file needs to use PHP code or PHP variables, use:

```text
header.php
```

For this course, we will commonly use `.php` for our included files because it allows us to use PHP when needed.

---

## Why Use `include()`?

`include()` helps us:

- avoid repeating the same code
- reuse headers and footers
- keep our files smaller
- make changes in one place

For example, if 10 pages use the same `header.php`, we only need to change the header once instead of editing all 10 pages.

---


The important idea to remember is:

> **`include()` lets us reuse code from another file instead of copying and pasting the same code on every page.**
