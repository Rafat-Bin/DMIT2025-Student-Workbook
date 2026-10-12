# Lesson 14 — Pagination

In Lesson 13, we created a List View and a Single Record View using the Happy Planet Index database.

Our list page displayed every country at once. This worked well, but what if our database contained hundreds or thousands of records?

In this lesson, we'll improve our existing `index.php` by adding pagination, allowing visitors to browse a few countries at a time.

By the end of this lesson, you'll be able to:

- Explain why websites use pagination.
- Use SQL `LIMIT` and `OFFSET`.
- Calculate the number of pages needed.
- Read and validate a page number from the URL.
- Display Previous, Next, and numbered page links.

## 1. Why Do We Need Pagination?

Pagination divides a long list of results into smaller pages.

For example, instead of displaying 150 countries at once, we could display 10 countries per page.

This makes the page easier to browse and reduces the number of records retrieved from the database at one time.

You've probably seen pagination on search engines, online stores, and other websites.

## 2. How Pagination Works

Pagination uses four important values:

| Value            | Meaning                              | Example |
| ---------------- | ------------------------------------ | ------- |
| Current page     | The page the visitor is viewing      | 2       |
| Records per page | How many records to display          | 10      |
| Total records    | How many records are in the database | 35      |
| Total pages      | How many pages are needed            | 4       |

If we display 10 records per page, the records are divided like this:

| Page | Records displayed | Number of records |
| --- | --- | --- |
| 1 | 1–10 | 10 |
| 2 | 11–20 | 10 |
| 3 | 21–30 | 10 |
| 4 | 31–35 | 5 |

*Example: 35 records, displaying 10 per page.*

The last page may contain fewer records than the others.

## 3. SQL `LIMIT` and `OFFSET`

SQL gives us two useful keywords for pagination.

- `LIMIT` controls how many records are returned.
- `OFFSET` controls how many records are skipped.

For example, to retrieve the first 10 countries:

```sql
SELECT *
FROM happiness_index
ORDER BY `rank`
LIMIT 10 OFFSET 0;
```

To retrieve the next 10 countries:

```sql
SELECT *
FROM happiness_index
ORDER BY `rank`
LIMIT 10 OFFSET 10;
```

And for the third page:

```sql
SELECT *
FROM happiness_index
ORDER BY `rank`
LIMIT 10 OFFSET 20;
```

Notice that `LIMIT` stays the same, but `OFFSET` changes.

We also use `ORDER BY` to make sure the countries appear in a consistent order.

## 4. Count the Records

Before we can calculate how many pages we need, we must find out how many countries are in the database.

SQL provides the `COUNT(*)` function:

```sql
SELECT COUNT(*) AS total
FROM happiness_index;
```

This returns the number of records without retrieving every country.

In PHP, we can write:

```php
$sql = "SELECT COUNT(*) AS total FROM happiness_index";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_records = $row['total'];
```

Now `$total_records` contains the number of countries in our database.

## 5. Choose Records Per Page

Let's display 10 countries per page.

```php
$per_page = 10;
```

We can change this number later, but for now we'll keep it fixed.

## 6. Calculate Total Pages

To calculate the number of pages, divide the total number of records by the number of records per page.

For example:

\[ 35 \div 10 = 3.5 \]

We cannot have half a page, so we must round up.

PHP provides the `ceil()` function:

```php
$total_pages = ceil($total_records / $per_page);
```

**Try the page calculation**

| Total records | Records per page | Total pages needed |
| --- | --- | --- |
| 35 | 10 | **4** |

`ceil(35 / 10) = 4`

## 7. Read the Current Page

How does PHP know which page the visitor wants?

We'll use a query string, just like we did in Lesson 13.

For example:

```text
index.php?page=1
index.php?page=2
index.php?page=3
```

PHP reads the page number using `$_GET`:

```php
$current_page = filter_var(
    $_GET['page'] ?? 1,
    FILTER_VALIDATE_INT
);
```

Here:

- `$_GET['page']` reads the value from the URL.
- `?? 1` uses page 1 if the parameter is missing.
- `FILTER_VALIDATE_INT` checks whether the value is an integer.

This should look familiar from our Single Record View.

## 8. Validate the Page Number

A visitor could change the URL to something invalid:

```text
index.php?page=-2
index.php?page=abc
index.php?page=999
```

We should make sure the requested page is between 1 and the total number of pages.

```php
if (!$current_page || $current_page < 1 || $current_page > $total_pages) {
    $current_page = 1;
}
```

If the page number is invalid, we'll display page 1 instead.

Important: We must calculate `$total_pages` before checking whether `$current_page` is greater than it.

## 9. Calculate the Offset

Now we need to determine how many records to skip.

The formula is:

\[ \text{Offset} = (\text{Current Page} - 1) \times \text{Records Per Page} \]

In PHP:

```php
$offset = ($current_page - 1) * $per_page;
```

Let's see how this works.

**Choose a page — 10 records per page**

| Page | OFFSET | Records displayed |
| --- | --- | --- |
| 1 | 0 | 1–10 |
| **2** | **10** | **11–20** |
| 3 | 20 | 21–30 |
| 4 | 30 | 31–40 |

Page 2 skips the first 10 records.

## 10. Retrieve Records for One Page

We now have the two values SQL needs:

```php
$per_page = 10;
$offset = ($current_page - 1) * $per_page;
```

Let's use a prepared statement to retrieve only the countries for the current page.

```php
$sql = "SELECT `rank`, country
        FROM happiness_index
        ORDER BY `rank`
        LIMIT ? OFFSET ?";

$statement = $conn->prepare($sql);

$statement->bind_param("ii", $per_page, $offset);

$statement->execute();

$result = $statement->get_result();
```

The two `?` placeholders represent the limit and offset.

The `"ii"` tells MySQLi that both values are integers.

We can display the results using the same `while` loop from Lesson 13:

```php
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars((string) $row['rank']) ?></td>
        <td><?= htmlspecialchars($row['country']) ?></td>
        <td>
            <a href="country.php?rank=<?= urlencode($row['rank']) ?>">
                View Details
            </a>
        </td>
    </tr>
<?php endwhile; ?>
```

Our View Details links still work. We're only changing how many countries appear on the list page.

## 11. Build Pagination Navigation

Now we need to let visitors move between pages.

### Previous and Next

The previous page is one less than the current page:

```php
$previous_page = $current_page - 1;
```

The next page is one more:

```php
$next_page = $current_page + 1;
```

We should only display Previous if we're not on the first page, and Next if we're not on the last page.

```php
<nav aria-label="Country pagination">
    <ul class="pagination">

        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link"
                   href="index.php?page=<?= $current_page - 1 ?>">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <?php if ($current_page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link"
                   href="index.php?page=<?= $current_page + 1 ?>">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>
```

### Numbered Page Links

Visitors should also be able to jump directly to a page.

We'll use a `for` loop to create one link for each page:

```php
<?php for ($page = 1; $page <= $total_pages; $page++): ?>
    <li class="page-item <?= $page == $current_page ? 'active' : '' ?>">
        <a class="page-link" href="index.php?page=<?= $page ?>">
            <?= $page ?>
        </a>
    </li>
<?php endfor; ?>
```

The `active` class highlights the page we're currently viewing.

**Example pagination navigation**

`Previous` · `1` · **`2`** · `3` · `4` · `Next`

Page 2 of 4 — the active page is highlighted.

## 12. Complete `index.php`

Now let's put everything together.

This is our updated country list page from Lesson 13, with pagination added.

The example assumes `includes/header.php` already creates `$conn`, as in our previous lesson.

```php
<?php
$title = 'Happy Planet Index';
include 'includes/header.php';

// Count all countries.
$count_sql = "SELECT COUNT(*) AS total FROM happiness_index";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];

// Calculate the number of pages.
$per_page = 10;
$total_pages = ceil($total_records / $per_page);

// Read and validate the current page.
$current_page = filter_var(
    $_GET['page'] ?? 1,
    FILTER_VALIDATE_INT
);

if (!$current_page || $current_page < 1 || $current_page > $total_pages) {
    $current_page = 1;
}

// Calculate how many records to skip.
$offset = ($current_page - 1) * $per_page;

// Retrieve countries for the current page.
$sql = "SELECT `rank`, country
        FROM happiness_index
        ORDER BY `rank`
        LIMIT ? OFFSET ?";

$statement = $conn->prepare($sql);
$statement->bind_param("ii", $per_page, $offset);
$statement->execute();

$result = $statement->get_result();
?>

<h2 class="display-5 my-3">Happy Planet Index</h2>

<p class="lead mb-4">
    Explore countries in the Happy Planet Index.
    Select View Details to learn more about a country.
</p>

<?php if ($result->num_rows > 0): ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Rank</th>
                <th>Country</th>
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
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No countries found.</p>

<?php endif; ?>

<?php if ($total_pages > 1): ?>

    <nav aria-label="Country pagination">
        <ul class="pagination">

            <!-- Previous -->
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="index.php?page=<?= $current_page - 1 ?>">
                        Previous
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <li class="page-item <?= $page == $current_page ? 'active' : '' ?>">
                    <a class="page-link"
                       href="index.php?page=<?= $page ?>">
                        <?= $page ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="index.php?page=<?= $current_page + 1 ?>">
                        Next
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>

<?php endif; ?>

<?php
$statement->close();
include 'includes/footer.php';
?>
```

### Test the Page

Open the following URLs in your browser:

```text
index.php
index.php?page=2
index.php?page=3
index.php?page=-1
index.php?page=abc
index.php?page=999
```

Check that the correct countries appear and invalid page numbers return to page 1.

Also check that the View Details links still open the correct country.

## 13. Exercise — City Pagination

In Lesson 13, we created a City List and a City Details page using the `cities` table.

Now let's add pagination to the City List.

### Part 1: Update `cities.php`

Update your existing page to:

1. Display 10 cities per page.
2. Count the total number of cities using `COUNT(*)`.
3. Calculate the total number of pages.
4. Read and validate the page number using `$_GET`.
5. Use `LIMIT` and `OFFSET` to retrieve only the cities for the current page.
6. Display the city ID, city name, and View Details link.
7. Add Previous, Next, and numbered page links using Bootstrap.

Use the actual column names from our database: `cid` and `city_name`.

### Part 2: Test Your Pagination

- Does the first page display up to 10 cities?
- Does Next display a different group of cities?
- Does Previous return to the correct page?
- Is the current page number highlighted?
- What happens if the page number is missing or invalid?
- Do the View Details links still open the correct city?

## 14. Summary

In this lesson, we learned how to divide database results into smaller pages.

We used:

- `COUNT(*)` to count records.
- `ceil()` to calculate the total number of pages.
- `$_GET` to read the current page.
- `LIMIT` and `OFFSET` to retrieve the correct records.
- Prepared statements to safely pass numeric values to SQL.
- Bootstrap pagination to create Previous, Next, and numbered page links.

We also continued using the List View and Single Record View from Lesson 13.
