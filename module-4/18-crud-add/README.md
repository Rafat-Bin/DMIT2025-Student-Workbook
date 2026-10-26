# Lesson 18 — Introduction to CRUD: Add (Create)

In Lesson 17, we used a prepared `SELECT` statement to display Canadian cities. In this lesson, we will add the **Create** operation: visitors will be able to submit a form and save a new city with a prepared `INSERT` statement.

We will reuse the database connection and `execute_prepared_statement()` function from Lesson 17. We will also put the form fields in a separate file so we can reuse them for Edit in Lesson 20.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Create a form that submits data using `POST`.
- Retrieve values from `$_POST`.
- Validate submitted values and display errors.
- Keep previously entered values when a form has errors (a *sticky form*).
- Insert a record using a prepared statement.
- Reuse form fields in a separate PHP file.

## 2. Getting Started

Copy your completed Lesson 17 project into a new folder named `18-crud-add`. Keep the existing `cities` database and all your completed Lesson 17 work.

We will create two files and make one small change to an existing file:

```text
18-crud-add/
├── data/
│   └── connect.php              (existing)
├── private/
│   ├── functions.php            (existing)
│   └── prepared.php             (existing)
└── public/
    ├── index.php                (existing)
    ├── add.php                  (new)
    └── includes/
        ├── header.php           (small change)
        ├── footer.php           (existing)
        └── form.php             (new)
```

The form uses the five fields that visitors can enter: `city_name`, `province`, `population`, `is_capital`, and `trivia`. MySQL creates `cid` automatically.

## 3. Understanding INSERT

An `INSERT` statement adds a new row to a table:

```sql
INSERT INTO cities
    (city_name, province, population, is_capital, trivia)
VALUES
    ('Lethbridge', 'AB', 106550, 0, 'Located in southern Alberta.');
```

In PHP, we use **placeholders** instead of placing visitor input directly into SQL:

```sql
INSERT INTO cities
    (city_name, province, population, is_capital, trivia)
VALUES (?, ?, ?, ?, ?);
```

Our existing `execute_prepared_statement()` function will supply the five values safely.

## 4. Creating the Reusable Form

Create `public/includes/form.php` with the following code. The form will use variables prepared by `add.php`.

**`public/includes/form.php`**

```php
<form action="<?= htmlspecialchars($form_action) ?>" method="POST">
    <p class="text-muted">Fields marked * are required.</p>

    <div class="mb-3">
        <label for="city_name" class="form-label">City Name *</label>
        <input type="text" class="form-control" id="city_name"
               name="city_name" maxlength="36"
               value="<?= htmlspecialchars($city_name) ?>">
    </div>

    <div class="mb-3">
        <label for="province" class="form-label">Province or Territory *</label>
        <select class="form-select" id="province" name="province">
            <option value="">Choose a province or territory</option>
            <?php foreach ($provinces as $code => $name): ?>
                <option value="<?= htmlspecialchars($code) ?>"
                    <?= $province === $code ? 'selected' : '' ?>>
                    <?= htmlspecialchars($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="population" class="form-label">Population *</label>
        <input type="number" class="form-control" id="population"
               name="population" min="0" step="1"
               value="<?= htmlspecialchars($population) ?>">
    </div>

    <fieldset class="mb-3">
        <legend class="fs-6">Is this a provincial or territorial capital? *</legend>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="capital_yes"
                   name="is_capital" value="1"
                   <?= $is_capital === '1' ? 'checked' : '' ?>>
            <label for="capital_yes" class="form-check-label">Yes</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="capital_no"
                   name="is_capital" value="0"
                   <?= $is_capital === '0' ? 'checked' : '' ?>>
            <label for="capital_no" class="form-check-label">No</label>
        </div>
    </fieldset>

    <div class="mb-3">
        <label for="trivia" class="form-label">Trivia (Optional)</label>
        <textarea class="form-control" id="trivia" name="trivia"
                  rows="3" maxlength="255"><?= htmlspecialchars($trivia) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">
        <?= htmlspecialchars($submit_label) ?>
    </button>
    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
</form>
```

**What to notice:** `method="POST"` sends the form values to PHP. Each `name` matches a key in `$_POST`. The variables in `value`, `selected`, `checked`, and the textarea keep the form *sticky* when validation fails. `$form_action` and `$submit_label` let us reuse this form in Lesson 20.

## 5. Receiving POST Data

When a form is submitted, PHP receives its values in `$_POST`:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city_name = trim($_POST['city_name'] ?? '');
}
```

`trim()` removes extra spaces, while `?? ''` provides an empty string if a value is missing. In the completed page below, we will retrieve all five fields.

## 6. Validation and Sticky Values

We should validate the submitted data in PHP even if the form uses HTML attributes such as `maxlength` and `min`.

For example:

```php
if ($city_name === '') {
    $errors[] = 'Please enter a city name.';
}

if ($population === '' || !ctype_digit($population)) {
    $errors[] = 'Please enter a valid population.';
}
```

The completed page will also check the database's field lengths, province codes, capital selection, and maximum population. If errors occur, we will show them and redisplay the form with the entered values.

## 7. Completing the Add Page

### Step A — Make one small change to `header.php`

Lesson 17's `header.php` opens the database connection and starts the HTML page. Our Add page needs to process the form **before** HTML is sent, so it can redirect after a successful insert.

In `public/includes/header.php`, replace:

```php
$conn = db_connect();
```

with:

```php
if (!isset($conn)) {
    $conn = db_connect();
}
```

This keeps the existing Lesson 17 pages working and avoids opening a second connection on `add.php`.

### Step B — Create `add.php`

**`public/add.php`**

```php
<?php
$title = 'Add a City';
$introduction = 'Add a new city to our Canadian Cities database.';

// Load the existing Lesson 17 code before processing the form.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';
require_once __DIR__ . '/../private/functions.php';
$conn = db_connect();

// The same form fields can be reused for Edit in Lesson 20.
$form_action = 'add.php';
$submit_label = 'Add City';

// Province codes must match the ENUM values in our database.
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

// Empty values for the first visit.
$city_name = '';
$province = '';
$population = '';
$is_capital = '';
$trivia = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the submitted values.
    $city_name = trim($_POST['city_name'] ?? '');
    $province = $_POST['province'] ?? '';
    $population = trim($_POST['population'] ?? '');
    $is_capital = $_POST['is_capital'] ?? '';
    $trivia = trim($_POST['trivia'] ?? '');

    // Check the fields.
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

    // Save only when every field is valid.
    if (empty($errors)) {
        $sql = 'INSERT INTO cities
                (city_name, province, population, is_capital, trivia)
                VALUES (?, ?, ?, ?, ?)';

        execute_prepared_statement(
            $sql,
            [$city_name, $province, (int) $population, (int) $is_capital, $trivia],
            'ssiis'
        );

        header('Location: index.php');
        exit();
    }
}

include 'includes/header.php';
?>

<h2 class="fw-light mb-3">City Information</h2>

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

<?php
include 'includes/form.php';
include 'includes/footer.php';
?>
```

The five placeholders (`?`) match the five values in the array. The type string `'ssiis'` means **string, string, integer, integer, string**.

If validation fails, `form.php` displays the errors and previously entered values. If validation succeeds, the prepared statement inserts the city, and `header('Location: index.php')` returns the visitor to the list. The redirect also prevents accidentally resubmitting the form when refreshing the page.

> **Note:** The `cities` table uses a signed `INT` for population, so the largest supported value is 2,147,483,647. We check this limit before saving. The `trivia` field is optional; this lesson stores an empty string if it is left blank.

## 8. Testing the Application

Try these three tests:

1. **Valid city:** Add Lethbridge, AB, population `106550`, capital **No**, and optional trivia. Confirm that it appears on `index.php`.
2. **Invalid values:** Leave the city name blank or enter `-500` for the population. Confirm that the form shows an error and does not insert a record.
3. **Sticky form:** Enter a city name, province, capital choice, and trivia, but leave population blank. Submit and confirm that the other values are still there.

You can also check the `cities` table in phpMyAdmin to confirm the new record was saved.

## 9. Exercise 22 — Improve the Add City Form

1. Change the submit button text to **Save New City** by editing `$submit_label` in `add.php`.
2. Rewrite the introduction on `add.php` to invite visitors to contribute a city.
3. Add a short note below the trivia textarea explaining that it is optional.
4. Test the form with a missing required value and confirm that the other values are preserved.
5. Add another Canadian city and verify that it appears on the home page.

### Lesson Summary

We have added **Create** to our Canadian Cities application. We used `POST` and `$_POST` to receive form data, validated it in PHP, preserved values when errors occurred, and used the prepared-statement helper from Lesson 17 to insert a new city. In Lesson 19, we will add **Delete**. In Lesson 20, we will reuse the form fields for **Edit**.
