# Lesson 17 — Introduction to CRUD: Read

In this lesson, we will begin building a CRUD application using our Canadian Cities database. We will organize our code into separate files, retrieve records using a prepared statement, and display those records in an HTML table. In the next lessons, we will add features to create, delete, and edit records.

## 1. Learning Objectives

By the end of this lesson, you should be able to:

- Explain the four operations in a CRUD application.
- Identify the purpose of the files in a PHP application.
- Write a function that retrieves records from MySQL.
- Execute a `SELECT` query using a prepared statement.
- Display records in an HTML table using PHP.
- Use `htmlspecialchars()` to display database values safely.

## 2. What Is a CRUD Application?

CRUD stands for **Create, Read, Update, and Delete**. These are the four basic operations used to manage records in a database.

| Operation | Purpose | SQL statement |
|---|---|---|
| Create | Add a new record | `INSERT` |
| Read | Retrieve and display records | `SELECT` |
| Update | Change an existing record | `UPDATE` |
| Delete | Remove a record | `DELETE` |

For example, a city database might allow visitors to view cities, add a city, update its population, or remove a city. We will begin with **Read**, which builds on the `SELECT` queries you used in Module 3.

## 3. Getting Started

Open the Lesson 17 starter project in your code editor. We will use the existing Canadian Cities database. If the `cities` table is missing, import the provided `init.sql` file using phpMyAdmin.

### Project file structure

```text
17-crud-read/
├── private/
│   ├── functions.php
│   ├── prepared.php
│   └── init.sql
└── public/
    ├── index.php
    ├── add.php
    ├── edit.php
    ├── delete.php
    ├── delete-confirmation.php
    └── includes/
        ├── header.php
        └── footer.php
```

The `private` folder contains reusable PHP functions and the SQL file. The `public` folder contains pages visitors can open in their browsers. In this lesson, we will work mainly with three files:

| File | Purpose |
|---|---|
| `private/prepared.php` | Runs SQL queries using prepared statements |
| `private/functions.php` | Retrieves cities and creates an HTML table |
| `public/index.php` | Displays the home page |

Continue using the provided `header.php` and `footer.php` files. The other public pages will be completed in later lessons. Your starter project may also include supporting connection or asset files; keep those files in their provided locations.

## 4. Understanding the Cities Table

The `cities` table contains these columns:

| Column | Description |
|---|---|
| `cid` | Unique city ID |
| `city_name` | City name |
| `province` | Province or territory |
| `population` | Population |
| `is_capital` | Whether the city is a capital |
| `trivia` | Optional information about the city |

We will display the city name, province, population, and trivia, with a star beside capital cities.

## 5. Creating a Reusable Database Function

Open **`private/prepared.php`**. Instead of writing a prepared statement on every page, we will create a function that can run a query for us.

### `private/prepared.php`

```php
<?php

// Execute a SQL query using a prepared statement.
function execute_prepared_statement($sql, $parameters = [], $datatypes = '') {
    global $conn;

    $statement = $conn->prepare($sql);

    // Bind values only when the query contains placeholders.
    if (!empty($parameters)) {
        $statement->bind_param($datatypes, ...$parameters);
    }

    $statement->execute();

    // SELECT queries return records.
    if (str_starts_with(strtolower(ltrim($sql)), 'select')) {
        $result = $statement->get_result();
        $statement->close();
        return $result;
    }

    $statement->close();
    return true;
}
```

The `execute_prepared_statement()` function accepts a SQL query, an optional array of parameter values, and an optional string describing their data types. The `global $connection` statement allows the function to use the database connection provided by the starter application. The function prepares and executes the SQL query. When there are placeholders, it binds the supplied values using `bind_param()`. For a `SELECT` query, it returns the matching records. We will extend our use of this function when we work with other CRUD operations.

Our first `SELECT` query does not need placeholders because it does not contain any visitor input. We still use a prepared statement to keep our database code consistent.

## 6. Retrieving and Displaying the Cities

Open **`private/functions.php`**. We will create two functions: `get_all_cities()` retrieves the records, and `generate_table()` displays them.

### `private/functions.php`

```php
<?php

// Retrieve all cities as an associative array.
function get_all_cities() {
    $sql = 'SELECT * FROM cities ORDER BY city_name';
    $result = execute_prepared_statement($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Display the cities in an HTML table.
function generate_table() {
    $cities = get_all_cities();

    if (count($cities) === 0) {
        echo '<p>No cities were found.</p>';
        return;
    }
    ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>City Name</th>
                <th>Population</th>
                <th>Trivia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td>
                        <?php if ($city['is_capital']): ?>
                            &starf;
                        <?php endif; ?>

                        <?= htmlspecialchars($city['city_name']) ?>,
                        <?= htmlspecialchars($city['province']) ?>
                    </td>
                    <td>
                        <?= number_format((int) $city['population']) ?>
                    </td>
                    <td>
                        <?php if (!empty($city['trivia'])): ?>
                            <span
                                class="bi bi-info-circle"
                                data-bs-toggle="tooltip"
                                title="<?= htmlspecialchars($city['trivia']) ?>"
                            ></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <aside class="mt-4">
        <h3 class="fs-5">Notes</h3>
        <p>&starf; indicates a capital city.</p>
        <p>Hover over an information icon to see city trivia.</p>
    </aside>

    <?php
}
```

The `get_all_cities()` function runs a `SELECT` query and uses `fetch_all(MYSQLI_ASSOC)` to turn the results into an array. Each record becomes an associative array whose keys are the column names.

The `generate_table()` function first calls `get_all_cities()`. If there are no records, it displays a message and stops. Otherwise, it creates an HTML table and uses `foreach` to display each city. An `if` statement adds a star when `is_capital` is true. We use `number_format()` to add commas to populations and display a trivia icon only when trivia is available. `htmlspecialchars()` safely displays values from the database.

Keeping these functions in `functions.php` means we can reuse them on other pages without copying the entire table into each page.

## 7. Creating the Home Page

Open **`public/index.php`**. This page is short because our database and table code are stored in reusable functions.

### `public/index.php`

```php
<?php
$title = 'Welcome!';
$introduction = 'Welcome to the Canadian Cities Online Database. Browse the cities currently stored in our database.';

include 'includes/header.php';
?>

<h2 class="fw-light mb-3">Current Cities in Our Database</h2>

<?php
generate_table();

include 'includes/footer.php';
?>
```

First, we define `$title` and `$introduction` for the page layout. We then include the provided header, which should load the database connection and the reusable functions. Calling `generate_table()` retrieves and displays the city records. Finally, we include the footer to complete the page.

**Check your starter files:** Before running the page, confirm that `header.php` or its supporting includes load `private/prepared.php` and `private/functions.php`, and that the database connection variable is named `$connection`. If your starter uses different paths or names, keep them consistent across the files.

## 8. How the Files Work Together

When a visitor opens `index.php`, the page calls `generate_table()` from `functions.php`. That function calls `get_all_cities()`, which uses `execute_prepared_statement()` from `prepared.php` to run a query. MySQL returns the city records, and `generate_table()` displays them in the browser.

```text
public/index.php
    ↓ calls generate_table()
private/functions.php
    ↓ calls get_all_cities()
private/functions.php
    ↓ calls execute_prepared_statement()
private/prepared.php
    ↓ runs SELECT query
MySQL cities table
    ↓ returns records
HTML table displayed on index.php
```

Each file has a clear responsibility, which makes the application easier to understand and maintain.

## 9. Testing the Application

Open `index.php` in your browser. You should see the cities listed in a table. Check that the names and provinces appear, populations have commas, capital cities show a star, and trivia icons appear for cities with trivia.

If the table does not appear, confirm that the database connection works, the `cities` table exists, and the required files are included. Bootstrap tooltips also require the tooltip initialization supplied by the project's JavaScript; check the provided footer if the icons appear but hovering does nothing.

## 10. Exercise 20 — Displaying Database Records

Make the following changes to the Canadian Cities application:

1. Change the query in `get_all_cities()` so cities are sorted by population from largest to smallest.
2. Add a separate **Province** column to the table.
3. Add a **Capital** column that displays **Yes** or **No** based on `is_capital`.
4. Test the application and make sure the trivia icons still work.

**Hint:** To sort by population, use `ORDER BY population DESC`.

To display whether a city is a capital, you can use:

```php
<?php if ($city['is_capital']): ?>
    Yes
<?php else: ?>
    No
<?php endif; ?>
```

## Lesson Summary

In this lesson, we introduced CRUD and completed the Read operation. We organized our application into separate files, created reusable functions, retrieved records with a prepared `SELECT` statement, and displayed the results in an HTML table. In **Lesson 18 — CRUD: Add**, we will create a form to insert new records into the database.
