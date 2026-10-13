# Lesson 13 — Single Record View



## Introduction

In Lesson 12, we connected PHP to MySQL and displayed **multiple records**. In this lesson, we will let visitors select one country from the Happy Planet Index and see its statistics on a second page. This is called a **single record view**.

**By the end of this lesson, you will be able to:** explain list and single record views; create links with query strings; read `$_GET`; validate a rank; explain SQL injection; use a prepared statement; display a record with a reusable include; and handle invalid or missing records.

## 1. Set Up the Database

Import `private/happiness_index.sql` into the `dmit2025` database using phpMyAdmin at `http://localhost:8081`. The Happy Planet Index dataset contains information about 152 countries.

Test the import:

```sql
SELECT * FROM happiness_index LIMIT 5;
```

We will use the `rank` and `country` columns to build the list, and the other columns to display details.

## 2. Two Views, One Database

A **list view** shows many records. A **single record view** shows one selected record.

```text
index.php → click View Stats → country.php → country statistics
```

We do not need a separate PHP page for every country. The same `country.php` can retrieve different records based on the URL.

## 3. Reuse Our Connection

Our existing Lesson 12 `db_connect()` function uses `mysqli_connect()` and returns a MySQLi connection. We can keep it.

MySQLi supports two styles:

```php
// Procedural
$result = mysqli_query($conn, $sql);

// Object-oriented
$result = $conn->query($sql);
```

Both use the same connection. This lesson uses the object-oriented style. We will introduce the `prepare()` method only after discussing user input.

**Project note:** Keep the original `data/connect.php`, `includes/header.php`, and `includes/footer.php`. The full code below works with these existing files. The footer calls `db_disconnect($connection)`, so retain that helper from Lesson 12.

## 4. Identify a Country

The `rank` column identifies the country we want to retrieve in this dataset.

```sql
SELECT * FROM happiness_index WHERE `rank` = 5;
```

This works, but it always retrieves rank 5. We need to let the user choose the rank.

## 5. Query Strings

A **query string** passes small pieces of information through a URL:

```text
country.php?rank=5
```

The `?` starts the query string. `rank` is the parameter name, and `5` is its value. Different links can open the same PHP page with different ranks:

```text
country.php?rank=1
country.php?rank=2
country.php?rank=3
```

A URL can have more than one parameter: `country.php?rank=5&country=Ecuador`. The `&` separates parameters. Our project sends both `rank` and `country`, but **only rank is needed to query the database**. We will use that simpler link here. The country name will come from the database, not from the URL.

## 6. Build the List Page

Our `index.php` needs to (1) retrieve countries, (2) display them, and (3) create a View Stats link for each row.

Inside the loop, `$row['rank']` contains the current country's rank. We can create the link directly:

```php
<a href="country.php?rank=<?= urlencode($row['rank']) ?>">View Stats</a>
```

`urlencode()` makes the value safe to place in a URL. Here the rank is numeric, but it is a useful habit when constructing query strings.

### Complete `index.php`

```php
<?php
$title = 'Home';

include 'includes/header.php';
$sql = 'SELECT `rank`, `country` FROM happiness_index';
$result = $conn->query($sql);
?>

<h2 class="display-5 my-3">Welcome to the Happy Planet Index</h2>
<p class="lead mb-5">
    Explore the countries in the Happy Planet Index.
    Select View Stats to see more information.
</p>

<?php if ($result->num_rows > 0): ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>HPI Rank</th>
                <th>Country Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $row['rank']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td>
                        <a class="link-success"
                           href="country.php?rank=<?= urlencode($row['rank']) ?>">
                            View Stats
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>No countries found.</p>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>
```

**What is happening?** `$connection->query($sql)` runs the query. `$result->fetch_assoc()` gives us one row at a time. We display its rank and country, then create a link containing that rank.

**Try it:** Open `index.php` and hover over several View Stats links. Look for `?rank=` in the URLs.

## 7. Read the Rank with `$_GET`

When someone opens `country.php?rank=5`, PHP makes the value available as `$_GET['rank']`.

```php
$rank = $_GET['rank'] ?? null;
```

`?? null` means: use `null` if the parameter is missing. This avoids an undefined-array-key warning.

For example, `country.php?rank=5` supplies `5`, but `country.php` supplies no rank. Query strings are automatically URL-decoded by PHP when they enter `$_GET`; we do not need to call `urldecode()` ourselves.


## 8. Check the Rank

A user can edit the URL, so we must check that the rank is a positive integer:

```php
$rank = filter_var($_GET['rank'] ?? null, FILTER_VALIDATE_INT);

if (!$rank || $rank < 1) {
    echo 'Invalid country.';
    exit;
}
```

`FILTER_VALIDATE_INT` checks for an integer. If the value is missing, invalid, zero, or negative, we stop. The rank is valid in format, but we still need to check whether the country exists.

## 9. Retrieve One Country Safely

We could write a query with a fixed rank:

```sql
SELECT * FROM happiness_index WHERE `rank` = 5;
```

But the rank comes from the URL. We should **not** insert user input directly into SQL, because it can lead to **SQL injection**.

Instead, use a **prepared statement**. The `?` is a placeholder for the rank:

```php
$query = 'SELECT * FROM happiness_index WHERE `rank` = ?';

$statement = $conn->prepare($query);
$statement->bind_param('i', $rank);
$statement->execute();

$result = $statement->get_result();
$row = $result->fetch_assoc();

$statement->close();
```

| Method | Purpose |
|---|---|
| `prepare()` | Prepares the SQL with its placeholder |
| `bind_param('i', $rank)` | Supplies the integer rank (`i` means integer) |
| `execute()` | Runs the query |
| `get_result()` | Gets the result set |
| `fetch_assoc()` | Gets the matching record |
| `close()` | Closes the statement |

If there is no matching record, `$row` will be `null`.

## 10. Display the Country

After fetching the record, we can display values from `$row`:

```php
<h2><?= htmlspecialchars($row['country']) ?></h2>
<p>Rank: <?= htmlspecialchars((string) $row['rank']) ?></p>
<p>Life Expectancy: <?= htmlspecialchars((string) $row['life_expectancy']) ?></p>
<p>Happy Planet Index: <?= htmlspecialchars((string) $row['hpi']) ?></p>
```

`htmlspecialchars()` ensures that text is displayed as text, not interpreted as HTML.

## 11. Complete `country.php`

This page uses the **same connection setup** as `index.php`. It checks the rank, retrieves one country, and displays a message if the rank is invalid or no country exists.

```php
<?php
$title = 'Country Statistics';
include 'includes/header.php';

$rank = filter_var($_GET['rank'] ?? null, FILTER_VALIDATE_INT);
?>

<h2>Country Statistics</h2>

<?php if (!$rank || $rank < 1): ?>
    <p>Invalid country.</p>
<?php else: ?>
    <?php
    $query = 'SELECT * FROM happiness_index WHERE `rank` = ?';

    $statement = $conn->prepare($query);
    $statement->bind_param('i', $rank);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    $statement->close();
    ?>

    <?php if ($row): ?>
        <h3><?= htmlspecialchars($row['country']) ?></h3>
        <p>Rank: <?= htmlspecialchars((string) $row['rank']) ?></p>
        <p>Life Expectancy: <?= htmlspecialchars((string) $row['life_expectancy']) ?></p>
        <p>Happy Planet Index: <?= htmlspecialchars((string) $row['hpi']) ?></p>
    <?php else: ?>
        <p>Country not found.</p>
    <?php endif; ?>

<?php endif; ?>

<a href="index.php">Back to Countries</a>

<?php include 'includes/footer.php'; ?>
```

Read the file from top to bottom: **connect → check rank → query → fetch → display**.

Notice that we display the country name from the **database**, not from the URL. Someone can change the URL, but the database gives us the actual record.


## 12. Two Kinds of Safety

**Prepared statements** keep user input separate from SQL instructions. **`htmlspecialchars()`** safely displays text in HTML. They protect different parts of the application, so we use both.

## 13. What Happens When Something Goes Wrong?

| Situation | Example | Result |
|---|---|---|
| Valid rank, matching record | `country.php?rank=5` | Country statistics |
| Missing or invalid rank | `country.php?rank=hello` | Invalid country |
| Valid rank, no matching record | `country.php?rank=9999` | Country not found |

A database connection or query failure is different from a missing record. During development, PHP may show diagnostic errors. On a live website, technical details should be logged rather than shown to visitors.

## 14. Exercise — City Details

In Lesson 12, we worked with the `cities` table. Now let's create a **Single Record View** for cities.

### Part 1: City List (`cities.php`)

Create a page that:

1. Retrieves all cities from the database.
2. Displays each city's **name** with a **View Details** link.
3. Passes the selected city's ID through the URL.

### Part 2: City Details (`city.php`)

Create a page that:

1. Reads and validates the city ID using `$_GET`.
2. Uses a prepared statement to retrieve the selected city.
3. Displays the **city name** and at least **two other details** from the database.
4. Includes a **Back to Cities** link.
5. Shows a helpful message if the city is not found.

### Test Your Work

- Do the View Details links display the correct city?
- What happens if the ID is missing or invalid?
- Does the Back to Cities link work?



