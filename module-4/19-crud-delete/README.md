# Lesson 19 — Introduction to CRUD: Delete

In Lesson 17, we learned to retrieve and display records from MySQL. In Lesson 18, we added cities with a form and a prepared `INSERT` statement. Now we'll add **Delete** to the same Canadian Cities Online Database.

A visitor will select a city, review a confirmation page, and choose whether to delete it. We will reuse the connection and prepared-statement helper from previous lessons.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain `DELETE FROM` and why `WHERE` is important.
- Identify a record using its primary key (`cid`).
- Pass a city ID from one page to another using `$_GET`.
- Retrieve one record with a prepared `SELECT` statement.
- Confirm deletion with a form submitted using `POST`.
- Execute a prepared `DELETE` statement and show a success message.

## 2. Getting Started

Copy your completed Lesson 18 project and rename the copy **`19-crud-delete`**.

```text
19-crud-delete/
├── data/
│   └── connect.php
├── private/
│   ├── init.sql
│   ├── functions.php
│   └── prepared.php
└── public/
    ├── index.php
    ├── add.php
    ├── edit.php
    ├── delete.php                 ← Work here
    ├── delete-confirmation.php    ← Work here
    └── includes/
        ├── header.php
        ├── footer.php
        └── form.php
```

We will complete only `delete.php` and `delete-confirmation.php`. The existing Read and Add features should continue to work. Your Lesson 18 `header.php` should already reuse an existing `$conn` rather than opening a second connection.

## 3. Understanding SQL DELETE

The `DELETE` statement removes records from a database table:

```sql
DELETE FROM cities
WHERE cid = 17;
```

This removes the record with the primary key `17`. Each city has a unique `cid`, even if two cities happen to share the same name.

**Be careful:** This statement would remove **every record** in the table:

```sql
DELETE FROM cities;
```

We will always use `WHERE cid = ?` in a prepared statement so that we delete only the selected city.

## 4. Creating `delete.php`

Open `public/delete.php` and replace its contents with the following code:

```php
<?php
$title = 'Delete a City';
$introduction = 'Choose a city from the list below to delete it.';

include 'includes/header.php';

$cities = get_all_cities();
?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success" role="alert">
        City deleted successfully.
    </div>
<?php endif; ?>

<h2 class="fw-light mb-3">Select a City to Delete</h2>

<?php if (empty($cities)): ?>
    <p>No cities were found.</p>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>City</th>
                <th>Province</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?= htmlspecialchars($city['city_name']) ?></td>
                    <td><?= htmlspecialchars($city['province']) ?></td>
                    <td>
                        <a href="delete-confirmation.php?cid=<?= $city['cid'] ?>"
                           class="btn btn-danger btn-sm">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
```

### How it works

- `get_all_cities()` is the function from Lesson 17. We do not need to write another database function.
- `foreach` displays one table row for each city.
- `htmlspecialchars()` safely displays city names and provinces.
- Each Delete link contains the city's `cid`.
- The success message appears when the visitor returns with `?deleted=1` in the URL.

For example, a Delete button may open:

```text
delete-confirmation.php?cid=17
```

**Clicking this link does not delete the city.** It only opens the confirmation page.

## 5. Passing the Selected City ID

The part of a URL after `?` is called a **query string**. PHP reads its values through `$_GET`.

```php
$cid = $_GET['cid'] ?? '';
```

The `?? ''` means that if the URL does not contain `cid`, we use an empty string instead.

Before using an ID, we should check that it is a positive whole number:

```php
if (!ctype_digit((string) $cid) || (int) $cid < 1) {
    $cid = 0;
}
```

An invalid ID becomes `0`, which will not match a normal city record.

## 6. Creating `delete-confirmation.php`

This page will retrieve the selected city, display a confirmation message, and delete the record **only if** the visitor submits the confirmation form.

Open `public/delete-confirmation.php` and replace its contents with the following **complete code**:

```php
<?php
$title = 'Confirm Deletion';
$introduction = 'Review the city below before deleting it.';

// Connect before displaying HTML so a successful POST can redirect.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';
$conn = db_connect();

// The ID comes from the URL first, then from the confirmation form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = $_POST['cid'] ?? '';
} else {
    $cid = $_GET['cid'] ?? '';
}

// Check that the ID is a positive whole number.
if (!ctype_digit((string) $cid) || (int) $cid < 1) {
    $cid = 0;
}

// Find the selected city.
$sql = 'SELECT * FROM cities WHERE cid = ?';
$result = execute_prepared_statement($sql, [(int) $cid], 'i');
$city = $result->fetch_assoc();

// Delete only after the visitor submits the confirmation form.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $city) {
    $sql = 'DELETE FROM cities WHERE cid = ?';
    execute_prepared_statement($sql, [(int) $cid], 'i');

    header('Location: delete.php?deleted=1');
    exit();
}

include 'includes/header.php';
?>

<?php if (!$city): ?>
    <div class="alert alert-warning" role="alert">
        The selected city could not be found.
    </div>
    <a href="delete.php" class="btn btn-secondary">Back to Cities</a>
<?php else: ?>
    <div class="alert alert-warning" role="alert">
        Are you sure you want to delete
        <strong>
            <?= htmlspecialchars($city['city_name']) ?>,
            <?= htmlspecialchars($city['province']) ?>
        </strong>?
    </div>

    <p>This action cannot be undone.</p>

    <form method="POST">
        <input type="hidden" name="cid" value="<?= $city['cid'] ?>">

        <button type="submit" class="btn btn-danger">
            Yes, Delete City
        </button>

        <a href="delete.php" class="btn btn-secondary">Cancel</a>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
```

### Let's understand the important parts

**1. Get the selected ID.** On the first visit, the ID comes from `$_GET`. When the form is submitted, it comes from `$_POST`.

**2. Retrieve the city.** The prepared `SELECT` statement finds the city using `cid`:

```php
$sql = 'SELECT * FROM cities WHERE cid = ?';
$result = execute_prepared_statement($sql, [(int) $cid], 'i');
$city = $result->fetch_assoc();
```

If no matching city exists, the page displays a message rather than a broken confirmation form.

**3. Delete only after confirmation.** The `POST` check prevents a simple link from deleting a record:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $city) {
    $sql = 'DELETE FROM cities WHERE cid = ?';
    execute_prepared_statement($sql, [(int) $cid], 'i');

    header('Location: delete.php?deleted=1');
    exit();
}
```

**4. Keep the ID in a hidden input.** The form sends the selected `cid` back to the same page:

```php
<input type="hidden" name="cid" value="<?= $city['cid'] ?>">
```

**5. Cancel safely.** Cancel is an ordinary link back to `delete.php`; it does not submit the form.

## 7. Understanding the Delete Workflow

1. The visitor opens **`delete.php`** and clicks Delete beside a city.
2. The browser opens **`delete-confirmation.php?cid=...`** using `GET`.
3. PHP retrieves the matching city with a prepared `SELECT` statement.
4. The visitor reviews the city and chooses **Yes, Delete City** or **Cancel**.
5. If the visitor confirms, the form submits using `POST` and PHP runs a prepared `DELETE` statement.
6. PHP redirects to **`delete.php?deleted=1`**, where a success message appears.

| Method | What it does in this lesson |
| --- | --- |
| `GET` | Opens the confirmation page for the selected city. |
| `POST` | Submits the confirmation and performs the deletion. |

**Remember:** `WHERE` limits which record is deleted, and `POST` ensures that merely visiting a link does not delete anything.

## 8. Testing the Application

Use the Add feature from Lesson 18 to create a **temporary city** for testing. Avoid deleting your original sample records.

For example, add a city called *Practice City*, with a valid province and population.

### Test 1 — Successful deletion

1. Open `delete.php`.
2. Find Practice City and click Delete.
3. Check that the confirmation page shows the correct city.
4. Click **Yes, Delete City**.
5. Confirm that the city disappears from the table and that **City deleted successfully.** appears.

### Test 2 — Cancel

1. Add another temporary city.
2. Click its Delete button.
3. Click **Cancel**.
4. Confirm that the city is still in the database.

### Test 3 — Missing or invalid ID

Try these URLs:

```text
delete-confirmation.php
delete-confirmation.php?cid=999999
```

If no record matches, the page should display **The selected city could not be found.** It should not display PHP warnings or delete anything.

### Test 4 — Previous features

Visit `index.php` and `add.php`. The home page should still display the remaining cities, and the Add form should still work.

## 9. Exercise 23 — Improving the Delete Feature

Make the following changes on your own:

1. Change the confirmation button label to **Permanently Delete City**.
2. Make sure the confirmation message identifies the selected city by name and province.
3. Add a warning that deletion cannot be undone.
4. Test Cancel and verify that the city remains in the database.
5. Add a temporary city using `add.php`, delete it, and confirm that it no longer appears on `index.php`.

### Exercise Checklist

- [ ] `delete.php` lists existing cities with individual Delete buttons.
- [ ] Each button opens the correct city's confirmation page.
- [ ] The confirmation page identifies the selected city.
- [ ] Cancel leaves the city in the database.
- [ ] Confirming deletes only the selected city.
- [ ] A success message appears after deletion.
- [ ] Missing or invalid IDs are handled without PHP warnings.
- [ ] The Read and Add pages still work.

