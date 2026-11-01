# Lesson 20 — Introduction to CRUD: Edit (Update)

In Lesson 17, we displayed records from MySQL. In Lesson 18, we added records using a form. In Lesson 19, we deleted records after confirmation. In this final lesson, we will **edit existing cities**.

We will complete **one file**, `public/edit.php`, and reuse `includes/form.php` from Lesson 18. We do not need any new helper functions.

## 1. Getting Started

Copy your completed Lesson 19 folder and rename the copy `20-crud-edit`.

```text
20-crud-edit/
├── data/
│   └── connect.php
├── private/
│   ├── init.sql
│   ├── functions.php
│   └── prepared.php
└── public/
    ├── index.php
    ├── add.php
    ├── edit.php                 ← Complete this file
    ├── delete.php
    ├── delete-confirmation.php
    └── includes/
        ├── header.php
        ├── footer.php
        └── form.php             ← Reuse from Lesson 18
```

Make sure your Lesson 18 form uses `$form_action`, `$submit_label`, `$city_name`, `$province`, `$population`, `$is_capital`, `$trivia`, and `$provinces`, as in the previous lesson. Its Cancel link may continue to point to `index.php`.

**Learning goals:** Select an existing record, display its current values in a form, validate edits, keep submitted values after errors, and save changes with a prepared `UPDATE` statement.

## 2. Understanding SQL UPDATE

An `UPDATE` statement changes a record that already exists.

```sql
UPDATE cities
SET population = 120000
WHERE cid = 17;
```

- `UPDATE cities` selects the table.
- `SET` describes the changes.
- `WHERE cid = 17` identifies the record.

**Always use `WHERE` when changing a particular record.** Without it, MySQL could update every city.

Our PHP version will use placeholders for the values:

```sql
UPDATE cities
SET city_name = ?,
    province = ?,
    population = ?,
    is_capital = ?,
    trivia = ?
WHERE cid = ?;
```

## 3. Building `public/edit.php`

Our page has three jobs:

1. **No city selected:** show a table with Edit buttons.
2. **City selected:** load that city's current values into the Lesson 18 form.
3. **Form submitted:** validate the new values, update the record, and redirect with a success message.

The Edit link uses the same idea as Lesson 19's Delete link:

```php
<a href="edit.php?cid=<?= $item['cid'] ?>" class="btn btn-warning btn-sm">Edit</a>
```

The ID comes from the URL. We use a prepared `SELECT` statement to find the city:

```php
$sql = 'SELECT * FROM cities WHERE cid = ?';
$result = execute_prepared_statement($sql, [(int) $cid], 'i');
$city = $result->fetch_assoc();
```

Then we fill the existing form variables with the database values:

```php
$city_name = $city['city_name'];
$province = $city['province'];
$population = (string) $city['population'];
$is_capital = (string) $city['is_capital'];
$trivia = $city['trivia'] ?? '';
```

**The key difference from Add:** When the Edit page first opens, the form uses **existing database values**. After the visitor submits the form, it uses the **new values from `$_POST`**. If validation fails, we leave those new values in the form. This is the sticky-form behaviour required for Lab 4.

We will use the same validation rules from Lesson 18. We do not need to learn another validation technique.

### Complete code: `public/edit.php`

Replace the contents of `public/edit.php` with the following code. This is the **complete file**; you do not need to combine it with the smaller examples above.

```php
<?php
$title = 'Edit a City';
$introduction = 'Select a city to update its information.';

// Load the existing database files before any HTML is displayed.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

// Reuse the form settings from Lesson 18.
$provinces = [
    'AB' => 'Alberta',
    'BC' => 'British Columbia',
    'MB' => 'Manitoba',
    'NB' => 'New Brunswick',
    'NL' => 'Newfoundland and Labrador',
    'NS' => 'Nova Scotia',
    'NT' => 'Northwest Territories',
    'NU' => 'Nunavut',
    'ON' => 'Ontario',
    'PE' => 'Prince Edward Island',
    'QC' => 'Quebec',
    'SK' => 'Saskatchewan',
    'YT' => 'Yukon'
];

$submit_label = 'Save Changes';
$errors = [];
$city = null;

// Get the selected city's ID from the URL.
$cid = $_GET['cid'] ?? '';

if (!ctype_digit((string) $cid) || (int) $cid < 1) {
    $cid = 0;
}

// Retrieve the selected city.
if ($cid > 0) {
    $sql = 'SELECT * FROM cities WHERE cid = ?';
    $result = execute_prepared_statement($sql, [(int) $cid], 'i');
    $city = $result->fetch_assoc();
}

// Fill the form with the city's existing values.
if ($city) {
    $city_name = $city['city_name'];
    $province = $city['province'];
    $population = (string) $city['population'];
    $is_capital = (string) $city['is_capital'];
    $trivia = $city['trivia'] ?? '';
}

// Keep the city ID in the form URL when submitting changes.
$form_action = 'edit.php?cid=' . $cid;

// Process the submitted form.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $city) {

    // Use the newest submitted values, even if validation fails.
    $city_name = trim($_POST['city_name'] ?? '');
    $province = $_POST['province'] ?? '';
    $population = trim($_POST['population'] ?? '');
    $is_capital = $_POST['is_capital'] ?? '';
    $trivia = trim($_POST['trivia'] ?? '');

    // Same validation rules as Lesson 18.
    if ($city_name === '' || strlen($city_name) > 36) {
        $errors[] = 'Enter a city name (36 characters or fewer).';
    }

    if (!array_key_exists($province, $provinces)) {
        $errors[] = 'Choose a valid province or territory.';
    }

    if ($population === '' || !ctype_digit($population)
        || (float) $population > 2147483647) {
        $errors[] = 'Enter a whole-number population from 0 to 2147483647.';
    }

    if ($is_capital !== '0' && $is_capital !== '1') {
        $errors[] = 'Select Yes or No for capital city.';
    }

    if (strlen($trivia) > 255) {
        $errors[] = 'Trivia must be 255 characters or fewer.';
    }

    // Save only if the submitted values are valid.
    if (empty($errors)) {
        $sql = 'UPDATE cities
                SET city_name = ?,
                    province = ?,
                    population = ?,
                    is_capital = ?,
                    trivia = ?
                WHERE cid = ?';

        execute_prepared_statement(
            $sql,
            [
                $city_name,
                $province,
                (int) $population,
                (int) $is_capital,
                $trivia,
                (int) $cid
            ],
            'ssiisi'
        );

        header('Location: edit.php?updated=1');
        exit();
    }
}

include 'includes/header.php';
?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success" role="alert">
        City updated successfully.
    </div>
<?php endif; ?>

<?php if ($city): ?>

    <h2 class="fw-light mb-3">
        Edit <?= htmlspecialchars($city['city_name']) ?>
    </h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <p class="fw-bold mb-2">Please correct these errors:</p>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php include 'includes/form.php'; ?>

<?php elseif ($cid > 0): ?>

    <div class="alert alert-warning" role="alert">
        The selected city could not be found.
    </div>
    <a href="edit.php" class="btn btn-secondary">Back to City List</a>

<?php else: ?>

    <h2 class="fw-light mb-3">Select a City to Edit</h2>
    <?php $cities = get_all_cities(); ?>

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
                <?php foreach ($cities as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['city_name']) ?></td>
                        <td><?= htmlspecialchars($item['province']) ?></td>
                        <td>
                            <a href="edit.php?cid=<?= (int) $item['cid'] ?>"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
```

**Why this works:** The page reads `cid` from the URL, retrieves the city, and fills the form. On `POST`, the submitted values replace the original form values. If they are valid, the prepared `UPDATE` saves the changes and redirects to `edit.php?updated=1`. Otherwise, the form reappears with errors and the visitor's latest entries.

The form already uses `$form_action`, so **you do not need to add a hidden city ID input** or modify `includes/form.php`.

## 4. Testing the Edit Feature

**Create a temporary city using `add.php`** so you can test without changing the original sample records.

### Test A — Successful update

1. Open `edit.php` and click Edit beside your temporary city.
2. Confirm that the form displays the city's existing values.
3. Change the population or trivia and click **Save Changes**.
4. Confirm that **City updated successfully.** appears.
5. Open `index.php` and verify the saved changes.

### Test B — Validation and sticky values

1. Open the same city's Edit form.
2. Clear the city name and change the population to `2500`.
3. Submit the form.
4. Confirm that an error appears and the population field **still shows `2500`**.
5. Correct the name and save again.

### Test C — Cancel

Open an Edit form, change a value, and click **Cancel** without saving. The existing Lesson 18 form returns to `index.php`; no update should occur.

### Test D — Missing record

Try opening `edit.php?cid=999999`. If that ID does not exist, the page should show **The selected city could not be found.** instead of a PHP error.

Finally, verify that the Read, Add, and Delete pages still work.

## 5. Exercise 24 — Editing an Existing City

Complete these tasks independently:

1. Change `$submit_label` to **Update City**.
2. Add a temporary city using `add.php`.
3. Edit its name, population, and trivia.
4. Submit invalid data and confirm that the form retains the latest entered values.
5. Save valid data and confirm that the changes appear on `index.php`.
6. Test Cancel and an invalid city ID.
