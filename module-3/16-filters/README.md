# Lesson 16 — Filters

In Lesson 15, we used `LIKE`, `OR`, and dynamic prepared statements to search the Happy Planet Index database. Now we'll **extend those same ideas** with checkboxes so visitors can select multiple filters. We'll also connect filters to the pagination from Lesson 14. 

**By the end of this lesson, you will be able to:**

- Read checkbox selections from `$_GET` as arrays.
- Use SQL `IN` to match multiple choices.
- Use `BETWEEN` and `OR` for multiple numeric ranges.
- Build filter conditions with the dynamic prepared-statement approach from Lesson 15.
- Keep selected checkboxes checked and preserve filters during pagination.
- Combine keyword search, filters, and pagination for Lab 03.

**Starting point:** Continue using the Happy Planet Index application. `includes/header.php` creates the MySQLi connection as `$conn`, and `country.php?rank=...` displays one country. We will display results in a Bootstrap table, as in Lesson 15. The `happiness_index.continent` column uses numeric IDs from 1 to 8.

---

# Part 1 — Adding Checkbox Filters

## 1. Search versus filter

A **search** lets visitors enter text, such as `land`. A **filter** lets visitors select categories, such as Western Europe and Africa. Visitors can use both at once.

In Lesson 15, our form sent one keyword. Checkboxes can send **several values with the same name** by adding `[]`:

```html
<form action="filters.php" method="GET">
    <label><input type="checkbox" name="continent[]" value="3"> Western Europe</label>
    <label><input type="checkbox" name="continent[]" value="5"> Africa</label>
    <button type="submit">Apply Filters</button>
</form>
```

If both boxes are checked, PHP receives an array similar to `['3', '5']`.

## 2. Read and validate the selections

```php
$selected_continents = $_GET['continent'] ?? [];

if (!is_array($selected_continents)) {
    $selected_continents = [];
}

$allowed_continents = ['1', '2', '3', '4', '5', '6', '7', '8'];
$selected_continents = array_values(array_intersect(
    $selected_continents,
    $allowed_continents
));
```


First, we read the selected checkboxes from $_GET. If no checkboxes were selected, we use an empty array. Next, we check that the submitted value is an array. Finally, array_intersect() removes any values that are not valid continent IDs, and array_values() resets the array indexes. This ensures we only use valid selections when building our SQL query.

We keep only choices offered by the form. To keep a checkbox selected after submission, use `in_array()`:

```php
<input type="checkbox" name="continent[]" value="3"
    <?= in_array('3', $selected_continents, true) ? 'checked' : '' ?>>
```

This is a **sticky form**. We used the same idea for search text in Lesson 15.

---

# Part 2 — SQL for Filters

## 3. Multiple choices with `IN`

To show countries from either Western Europe (3) or Africa (5):

```sql
SELECT * FROM happiness_index WHERE continent IN (3, 5);
```

For visitor input, use placeholders instead:

```sql
SELECT * FROM happiness_index WHERE continent IN (?, ?);
```

The number of `?` placeholders must match the number of selected continents. In Part 3, PHP will create those placeholders automatically and bind each selected ID as an integer (`i`).

## 4. Numeric ranges with `BETWEEN` and `OR`


```sql
SELECT * FROM happiness_index
WHERE life_expectancy BETWEEN 60 AND 69.9;
```

`BETWEEN` includes both endpoints. If visitors select two ranges, join them with `OR`:

```sql
WHERE (life_expectancy BETWEEN 60 AND 69.9
       OR life_expectancy BETWEEN 70 AND 79.9)
```

In a prepared statement, each range uses `BETWEEN ? AND ?` and two numeric parameters. When offering ranges in a form, validate selections against the allowed options before building the query.

**Remember:** Use `OR` **within** one group of choices, and `AND` **between** different groups. For example, a country must match the chosen continent **and** one of the chosen life-expectancy ranges.

---

# Part 3 — Complete Working Example: `filters.php`

## 5. Filter countries by continent

We'll start with **one filter group** so the complete example stays manageable. This is the same Happy Planet Index application used in Lessons 13–15, with a Bootstrap results table and working View Details links.

Create `filters.php`:

```php
<?php
$title = 'Filter Countries';
include 'includes/header.php';

// Continent IDs used in our happiness_index database.
$continents = [
    1 => 'Latin America',
    2 => 'North America & Oceania',
    3 => 'Western Europe',
    4 => 'Middle East',
    5 => 'Africa',
    6 => 'South Asia',
    7 => 'Eastern Europe & Central Asia',
    8 => 'East Asia'
];

// Read and validate the selected checkbox values.
$selected_continents = $_GET['continent'] ?? [];

if (!is_array($selected_continents)) {
    $selected_continents = [];
}

//array_keys($continents) gets the IDs (1 through 8), without the continent names.
//array_map('strval', ...) converts those IDs into strings because values submitted through $_GET are strings.

$allowed_ids = array_map('strval', array_keys($continents));
$selected_continents = array_values(array_intersect(
    $selected_continents,
    $allowed_ids
));

// Build a dynamic query, as in Lesson 15.
$sql = 'SELECT `rank`, country FROM happiness_index WHERE 1 = 1';
$parameters = [];
$datatypes = '';


//checks If the visitor selected at least one valid continent, add a continent filter to the query.
if (!empty($selected_continents)) { 

    //The code works from the inside out: $selected_continents = ['3', '5'];
        //1. count($selected_continents) counts the selected continents. In this example, it returns 2.
        //2. array_fill(0, 2, '?') creates an array containing two question marks: ['?', '?']. The 0 means the array starts at index 0.
        //3. implode(', ', ...) joins the question marks into one string, separated by commas and spaces: '?, ?'.

    $placeholders = implode(', ', array_fill(0, count($selected_continents), '?'));
    $sql .= " AND continent IN ($placeholders)";

    foreach ($selected_continents as $id) { //goes through each selected continent ID, one at a time.
        $parameters[] = (int) $id;
        $datatypes .= 'i';
    }
}

$sql .= ' ORDER BY `rank`';

$statement = $conn->prepare($sql);
if ($datatypes !== '') {
    $statement->bind_param($datatypes, ...$parameters);
}
$statement->execute();
$result = $statement->get_result();
?>

<h2 class="display-5 my-3">Filter Countries</h2>
<p class="lead">Select one or more continents to narrow the country list.</p>

<form action="filters.php" method="GET" class="border rounded p-4 mb-4">
    <fieldset>
        <legend class="fs-5">Continents</legend>

        <?php foreach ($continents as $id => $label): ?>

        <!--
        //The foreach loop goes through each continent:
                //- $id contains the continent ID, such as 3.
                //- $label contains the continent name, such as Western Europe.
                //The loop creates one checkbox for every continent.

           -->
            <div class="form-check">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="continent-<?= $id ?>" //Gives each checkbox a unique ID
                    name="continent[]" //Allows multiple selected values to be submitted as an array
                    value="<?= $id ?>" //Sends the continent ID when the checkbox is selected 
                    <?= in_array((string) $id, $selected_continents, true) ? 'checked' : '' ?>
                >
                 <!--
                   // The in_array() function checks whether the current continent ID exists in the $selected_continents array.
                        //- (string) $id converts the numeric ID to a string because values submitted through $_GET are strings.
                        //- $selected_continents contains the IDs selected by the visitor.
                        //- true tells PHP to compare both the value and its data type.
                    //The ? 'checked' : '' ---
                        //- If the continent was selected, output checked.
                        //- Otherwise, output nothing.
                       -->
                <label class="form-check-label" for="continent-<?= $id ?>">
                    <?= htmlspecialchars($label) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </fieldset>

    <button type="submit" class="btn btn-success mt-3">Apply Filters</button>
    <a href="filters.php" class="btn btn-outline-secondary mt-3">Clear Filters</a>
</form>

<h3>Results</h3>
<p><?= $result->num_rows ?> countries found.</p>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
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
                        <a href="country.php?rank=<?= urlencode((string) $row['rank']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No countries match the selected filters.</p>
<?php endif; ?>

<?php
$statement->close();
include 'includes/footer.php';
?>

```

**Test it:** Open the page with no filters; select one continent; select two continents; confirm the checkboxes stay checked; click Clear Filters; and check that View Details still works.

---

# Part 4 — Combining Search, Filters, and Pagination

## 6. Extend what we already built

Lesson 14 taught pagination. Lesson 15 taught keyword search and dynamic SQL. Part 3 introduced continent checkboxes. Now we will **combine these three features**.

We will still use `filters.php`. The following steps explain what changes and why.

### Step 1 — Add a keyword field

Read the keyword near the top of the PHP file, before building the query:

```php
$keyword = trim($_GET['keyword'] ?? '');
```

Inside the existing GET form, add a search input:

```php
<label for="keyword" class="form-label">Country Name</label>
<input type="search" id="keyword" name="keyword" class="form-control"
       value="<?= htmlspecialchars($keyword) ?>">
```

The `value` attribute makes the search field **sticky**. The checkbox code from Part 3 already keeps selected continents checked.

### Step 2 — Build the conditions once

In Part 3, we built a `SELECT` query directly. Now we build a reusable `$where` string so **both the count query and the results query use the same filters**.

```php
$where = ' WHERE 1 = 1';
$parameters = [];
$datatypes = '';

if ($keyword !== '') {
    $where .= " AND country LIKE CONCAT('%', ?, '%')";
    $parameters[] = $keyword;
    $datatypes .= 's';
}

if (!empty($selected_continents)) {
    $placeholders = implode(', ', array_fill(0, count($selected_continents), '?'));
    $where .= " AND continent IN ($placeholders)";

    foreach ($selected_continents as $id) {
        $parameters[] = (int) $id;
        $datatypes .= 'i';
    }
}
```

The keyword uses `LIKE`, and the checkboxes use `IN`. The `AND` between them means that a country must satisfy **both groups** when both are provided. If the visitor leaves one group empty, its condition is not added.

### Step 3 — Count matching records and calculate the page

Pagination must count only the countries that match the current search and filters, not the entire table.

```php
$count_sql = 'SELECT COUNT(*) AS total FROM happiness_index' . $where;
$count_statement = $conn->prepare($count_sql);

if ($datatypes !== '') {
    $count_statement->bind_param($datatypes, ...$parameters);
}

$count_statement->execute();
$total_records = (int) $count_statement->get_result()->fetch_assoc()['total'];
$count_statement->close();

$per_page = 10;
$total_pages = (int) ceil($total_records / $per_page);
$current_page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);

if (!$current_page || $current_page < 1) {
    $current_page = 1;
}

if ($total_pages > 0 && $current_page > $total_pages) {
    $current_page = $total_pages;
}

$offset = ($current_page - 1) * $per_page;
```

This reuses the pagination calculations from Lesson 14. For example, with 10 records per page, page 2 starts at offset 10.

### Step 4 — Retrieve only the current page

Use the **same `$where` conditions** in the results query, then add `LIMIT` and `OFFSET`:

```php
$sql = 'SELECT `rank`, country FROM happiness_index' . $where .
       ' ORDER BY `rank` LIMIT ? OFFSET ?';

$statement = $conn->prepare($sql);
$result_parameters = [...$parameters, $per_page, $offset];
$result_datatypes = $datatypes . 'ii';

$statement->bind_param($result_datatypes, ...$result_parameters);
$statement->execute();
$result = $statement->get_result();
```

We add two integer parameters for `LIMIT` and `OFFSET`. The Bootstrap table from Part 3 can still display the results, but now it displays **at most 10 countries**. To show the number of *all matching countries*, use `$total_records` instead of `$result->num_rows`.

### Step 5 — Preserve selections when changing pages

A link like `filters.php?page=2` loses the keyword and selected checkboxes. We can use `http_build_query()` to include all three values:

```php
$page_url = function (int $page) use ($keyword, $selected_continents): string {
    return 'filters.php?' . http_build_query([
        'keyword' => $keyword,
        'continent' => $selected_continents,
        'page' => $page
    ]);
};
```

The pagination links in the complete example call `$page_url()` for Previous, Next, and nearby page numbers. The existing **Clear Filters** link points to `filters.php` without query parameters, so it resets the keyword, checkboxes, and page.

## 7. Complete `filters.php`

The code below puts all five steps together. It includes the form, results table, View Details links, and pagination navigation.

```php
<?php
$title = 'Search and Filter Countries';
include 'includes/header.php';

$continents = [
    1 => 'Latin America',
    2 => 'North America & Oceania',
    3 => 'Western Europe',
    4 => 'Middle East',
    5 => 'Africa',
    6 => 'South Asia',
    7 => 'Eastern Europe & Central Asia',
    8 => 'East Asia'
];

// 1. Read search and checkbox selections.
$keyword = trim($_GET['keyword'] ?? '');
$selected_continents = $_GET['continent'] ?? [];
if (!is_array($selected_continents)) {
    $selected_continents = [];
}
$allowed_ids = array_map('strval', array_keys($continents));
$selected_continents = array_values(array_intersect(
    $selected_continents,
    $allowed_ids
));

// 2. Build conditions used by both queries.
$where = ' WHERE 1 = 1';
$parameters = [];
$datatypes = '';

if ($keyword !== '') {
    $where .= " AND country LIKE CONCAT('%', ?, '%')";
    $parameters[] = $keyword;
    $datatypes .= 's';
}
if (!empty($selected_continents)) {
    $placeholders = implode(', ', array_fill(0, count($selected_continents), '?'));
    $where .= " AND continent IN ($placeholders)";
    foreach ($selected_continents as $id) {
        $parameters[] = (int) $id;
        $datatypes .= 'i';
    }
}

// 3. Count matching records.
$count_sql = 'SELECT COUNT(*) AS total FROM happiness_index' . $where;
$count_statement = $conn->prepare($count_sql);
if ($datatypes !== '') {
    $count_statement->bind_param($datatypes, ...$parameters);
}
$count_statement->execute();
$total_records = (int) $count_statement->get_result()->fetch_assoc()['total'];
$count_statement->close();

// 4. Calculate pagination.
$per_page = 10;
$total_pages = (int) ceil($total_records / $per_page);
$current_page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
if (!$current_page || $current_page < 1) {
    $current_page = 1;
}
if ($total_pages > 0 && $current_page > $total_pages) {
    $current_page = $total_pages;
}
$offset = ($current_page - 1) * $per_page;

// 5. Get only the matching records for the current page.
$sql = 'SELECT `rank`, country FROM happiness_index' . $where .
       ' ORDER BY `rank` LIMIT ? OFFSET ?';
$statement = $conn->prepare($sql);
$result_parameters = [...$parameters, $per_page, $offset];
$result_datatypes = $datatypes . 'ii';
$statement->bind_param($result_datatypes, ...$result_parameters);
$statement->execute();
$result = $statement->get_result();

// 6. Keep search and filters in pagination URLs.
$page_url = function (int $page) use ($keyword, $selected_continents): string {
    return 'filters.php?' . http_build_query([
        'keyword' => $keyword,
        'continent' => $selected_continents,
        'page' => $page
    ]);
};
$start_page = max(1, $current_page - 2);
$end_page = min($total_pages, $current_page + 2);
?>

<h2 class="display-5 my-3">Search and Filter Countries</h2>
<p class="lead">Search by country name or select one or more continents.</p>

<form action="filters.php" method="GET" class="border rounded p-4 mb-4">
    <div class="mb-4">
        <label for="keyword" class="form-label">Country Name</label>
        <input type="search" id="keyword" name="keyword" class="form-control"
               value="<?= htmlspecialchars($keyword) ?>"
               placeholder="Enter part of a country name">
    </div>

    <fieldset>
        <legend class="fs-5">Continents</legend>
        <?php foreach ($continents as $id => $label): ?>
            <div class="form-check">
                <input type="checkbox" class="form-check-input"
                       id="continent-<?= $id ?>" name="continent[]"
                       value="<?= $id ?>"
                       <?= in_array((string) $id, $selected_continents, true) ? 'checked' : '' ?>>
                <label class="form-check-label" for="continent-<?= $id ?>">
                    <?= htmlspecialchars($label) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </fieldset>

    <button type="submit" class="btn btn-success mt-3">Search and Filter</button>
    <a href="filters.php" class="btn btn-outline-secondary mt-3">Clear Filters</a>
</form>

<h3>Results</h3>
<p><?= $total_records ?> countries found.</p>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>Rank</th><th>Country</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $row['rank']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td>
                        <a href="country.php?rank=<?= urlencode((string) $row['rank']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No countries match your search and filters.</p>
<?php endif; ?>

<?php if ($total_pages > 1): ?>
    <nav aria-label="Country pagination" class="my-4">
        <ul class="pagination flex-wrap">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?= htmlspecialchars($page_url($current_page - 1)) ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($page = $start_page; $page <= $end_page; $page++): ?>
                <li class="page-item <?= $page === $current_page ? 'active' : '' ?>">
                    <a class="page-link"
                       href="<?= htmlspecialchars($page_url($page)) ?>"
                       <?= $page === $current_page ? 'aria-current="page"' : '' ?>>
                        <?= $page ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?= htmlspecialchars($page_url($current_page + 1)) ?>">Next</a>
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



---

# Part 5 — Exercise 19: City Search and Filters

## 11. Continue the Cities application from Lesson 15

In Lesson 15, you updated `cities.php` to search `city_name` and `province`. Now extend **that same file** so visitors can filter by province and move through the matching results.

The existing `cities` table includes `cid`, `city_name`, `province`, `population`, `is_capital`, and `trivia`. Keep the View Details links to `city.php?cid=...` working.

### Part 1 — Add province checkboxes

Add checkboxes to the existing GET search form. Use `name="provinces[]"` so visitors can select more than one province. For example:

```html
<label class="form-check-label">
    <input type="checkbox" name="provinces[]" value="Alberta"
           class="form-check-input">
    Alberta
</label>
```

**Use the exact values stored in your `cities.province` column.** If the database stores `AB` rather than `Alberta`, use `value="AB"`. Include only provinces found in your data.

### Part 2 — Filter by province

Read `$_GET['provinces']` as an array, validate it against the available choices, and use a prepared `IN` condition. If two provinces are checked, show cities from either province.

### Part 3 — Combine filtering with Lesson 15's search

Keep the existing keyword search across `city_name` **or** `province` using `LIKE` and `OR`. Join that search group to the province filter using `AND`.

Searching should still work with no province selected, and filtering should work with an empty keyword.

### Part 4 — Keep pagination working

- Count only cities matching the current keyword and province selections.
- Display **10 matching cities per page**.
- Show Previous, Next, and a limited window of numbered page links.
- Preserve `keyword` and `provinces[]` in every pagination URL.
- Keep the existing View Details links working.

### Part 5 — Sticky selections and clear filters

Keep the keyword in the search input and the selected province checkboxes checked. Add a Clear Filters link that returns to `cities.php` without query parameters. Show a helpful no-results message and continue using `htmlspecialchars()` for displayed database values.

