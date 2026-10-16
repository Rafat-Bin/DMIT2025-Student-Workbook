# Lesson 15 — Advanced Search Features

In Lesson 14, we added pagination to our Happy Planet Index application so visitors could browse countries across multiple pages.

But what if a visitor wants to find a particular country without browsing through every page?

In this lesson, we'll add **search functionality** to our application. We'll begin with a simple search form and gradually build more advanced searches using multiple conditions and prepared statements.

By the end of this lesson, you'll be able to:

- Create a search form using the `GET` method.
- Read search values using `$_GET`.
- Use SQL `LIKE` to find partial matches.
- Search multiple database columns.
- Use prepared statements to safely handle search values.
- Build SQL queries dynamically based on the search options selected.
- Understand how different search conditions can work together.

## 1. Introduction to Searching

Searching allows visitors to find specific records instead of browsing through an entire list.

For example, someone visiting our Happy Planet Index application might want to find countries containing the word `land`.

A search could return countries such as Iceland, Finland, and Ireland, depending on the records in our database.

Unlike pagination, which divides all records into smaller groups, **searching limits the results to records that match a condition**.

# Part 1 — Basic Search

## 2. Creating a Basic Search Form

We'll begin with a new file named `basic-search.php`.

First, create a simple HTML form:

```html
<form action="basic-search.php" method="GET">
    <label for="keyword" class="form-label">Search Countries</label>

    <input
        type="search"
        name="keyword"
        id="keyword"
        class="form-control"
        placeholder="Enter a country name"
    >

    <button type="submit" class="btn btn-primary mt-2">
        Search
    </button>
</form>
```

The form uses the `GET` method, so the search keyword will appear in the URL.

For example, entering `can` produces a URL similar to:

```text
basic-search.php?keyword=can
```

Notice that the input's `name` attribute is `keyword`. PHP uses this name to retrieve the submitted value.

## 3. Reading the Search Keyword

Just as we used `$_GET['page']` in Lesson 14, we can use `$_GET['keyword']` to read the search value.

```php
$keyword = trim($_GET['keyword'] ?? '');
```

Let's break this down:

- `$_GET['keyword']` reads the submitted keyword.
- `?? ''` provides an empty string when no keyword is submitted.
- `trim()` removes extra spaces from the beginning and end.

For example, if someone enters `"  Canada  "`, the value stored in `$keyword` becomes `"Canada"`.

We'll use this variable when building our SQL query.

## 4. SQL `LIKE` and Wildcards

So far, we've used `WHERE` with exact comparisons.

For example:

```sql
SELECT *
FROM happiness_index
WHERE country = 'Canada';
```

This only matches a country with the exact name `Canada`.

But what if someone enters only part of a country name?

SQL provides the `LIKE` operator.

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%land%';
```

The `%` symbol is a wildcard that represents zero or more characters.

| Pattern | Meaning |
|---|---|
| `'Can%'` | Starts with `Can` |
| `'%land'` | Ends with `land` |
| `'%land%'` | Contains `land` anywhere |

For our search form, we'll use `%` on both sides so visitors can enter any part of a country name.

## 5. Searching with Prepared Statements

We should not insert a visitor's keyword directly into an SQL string.

Instead, we'll use a prepared statement, just as we did in Lesson 13.

```php
$sql = "SELECT `rank`, country
        FROM happiness_index
        WHERE country LIKE CONCAT('%', ?, '%')
        ORDER BY country";

$statement = $conn->prepare($sql);
$statement->bind_param("s", $keyword);
$statement->execute();

$result = $statement->get_result();
```

The `?` is a placeholder for the search keyword.

`CONCAT('%', ?, '%')` adds wildcards around the keyword, allowing partial matches.

The `"s"` tells MySQLi that the parameter is a string.

This is the same prepared-statement process students already know:

1. Prepare the SQL.
2. Bind the parameter.
3. Execute the statement.
4. Retrieve the results.

## 6. Displaying Search Results

Now we can display the matching countries.

We'll use the same `while` loop we've used in previous lessons.

```php
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
                        <a href="country.php?rank=<?= urlencode($row['rank']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No matching countries found.</p>

<?php endif; ?>
```

If the query returns matching records, they'll appear in the table.

Otherwise, visitors will see a helpful message.

Our existing Single Record View still works because the View Details link continues to pass the country's rank.

## 7. Keeping the Search Keyword

After submitting the form, the search field should continue displaying the keyword.

Otherwise, visitors may forget what they searched for.

We can use PHP to set the input's `value` attribute:

```php
<input
    type="search"
    name="keyword"
    id="keyword"
    class="form-control"
    value="<?= htmlspecialchars($keyword) ?>"
>
```

`htmlspecialchars()` ensures the value is safely displayed inside HTML.

### Complete `basic-search.php`

Let's combine everything into one working page.

This example assumes our shared `includes/header.php` creates the `$conn` database connection, as in the previous lesson.

```php
<?php
$title = 'Search Countries';
include 'includes/header.php';

// Read the search keyword.
$keyword = trim($_GET['keyword'] ?? '');

// Search countries using a prepared statement.
$sql = "SELECT `rank`, country
        FROM happiness_index
        WHERE country LIKE CONCAT('%', ?, '%')
        ORDER BY country";

$statement = $conn->prepare($sql);
$statement->bind_param("s", $keyword);
$statement->execute();

$result = $statement->get_result();
?>

<h2 class="display-5 my-3">Search Countries</h2>

<p class="lead">
    Enter a country name or part of a country name.
</p>

<form action="basic-search.php" method="GET" class="mb-4">

    <label for="keyword" class="form-label">
        Country Name
    </label>

    <input
        type="search"
        name="keyword"
        id="keyword"
        class="form-control"
        value="<?= htmlspecialchars($keyword) ?>"
        placeholder="Enter a country name"
    >

    <button type="submit" class="btn btn-primary mt-2">
        Search
    </button>

</form>

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
                        <a href="country.php?rank=<?= urlencode($row['rank']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No matching countries found.</p>

<?php endif; ?>

<?php
$statement->close();
include 'includes/footer.php';
?>
```

### Test the Search

Try searching for different values:

| Search | What to Check |
|---|---|
| `can` | Does it find countries containing `can`? |
| `land` | Does it find countries containing `land`? |
| `xyz123` | Does it display the no-results message? |
| Empty keyword | Does it display the available countries? |

## 8. Searching Multiple Columns

Sometimes one search field needs to check several database columns.

We can use SQL `OR` to search multiple columns with the same keyword.

For our Happy Planet Index example, let's search both country names and continents:

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%america%'
   OR continent LIKE '%america%';
```

Using a prepared statement:

```php
$sql = "SELECT `rank`, country, continent
        FROM happiness_index
        WHERE country LIKE CONCAT('%', ?, '%')
           OR continent LIKE CONCAT('%', ?, '%')
        ORDER BY country";

$statement = $conn->prepare($sql);

$statement->bind_param("ss", $keyword, $keyword);

$statement->execute();

$result = $statement->get_result();
```

Notice that we have two placeholders, even though we're searching with one keyword.

That means we must bind the keyword twice.

The `"ss"` tells MySQLi that both parameters are strings.

This is an important technique for Lab 03, where one keyword must search multiple comic-book fields.

# Part 2 — Advanced Search

Our basic search uses one keyword. However, some websites let visitors combine several search options.

For example, visitors might want to find countries that:

- Have a particular name.
- Have a wellbeing score above a chosen value.

Instead of building a complicated search form, we'll start with just **two optional search fields**.

This will help us understand how dynamic SQL works before we explore multiple filters in Lesson 16.

## 9. Introduction to Advanced Search

Let's create a new file called `search.php`.

Our advanced search form will have two sections:

| Search option | HTML input | SQL concept |
|---|---|---|
| Country name | Text input | `LIKE` |
| Minimum wellbeing score | Number input | `>=` |

Each option is optional. Visitors can use one option, both options, or neither.

For example:

| Country name | Minimum wellbeing | Results |
|---|---|---|
| `land` | Empty | Countries containing `land` |
| Empty | `6` | Countries with wellbeing of at least 6 |
| `land` | `6` | Countries matching both conditions |
| Empty | Empty | All countries |

This introduces a new challenge: **we don't know which conditions the visitor will select until the form is submitted.**

That's why we'll need dynamic SQL.


## 10. Building Dynamic SQL Queries

A **dynamic SQL query** is a query that PHP builds based on the information entered by a visitor.

Imagine we have a search form with two optional fields:

- **Country:** Search for countries containing certain letters.
- **Minimum wellbeing score:** Show countries with a wellbeing score equal to or higher than a given number.

Visitors can fill in one field, both fields, or leave both empty.

The SQL query needs to change depending on what they enter.

### Example 1: Searching by Country

If a visitor enters `land` in the country field, we want to find countries whose names contain those letters.

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%land%';
```

### Example 2: Searching by Country and Wellbeing Score

If the visitor enters `land` and a minimum wellbeing score of `6`, we need two conditions:

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%land%'
  AND wellbeing >= 6;
```

The `AND` operator means **both conditions must be true** for a record to appear in the results.

But what if the visitor only enters a country name and leaves the wellbeing field empty?

In that case, we should only include the country condition.

Instead of writing a different SQL query for every possible combination, we can use PHP to **build the query one piece at a time**.

### Adding Text to a SQL Query in PHP

In PHP, the `.=` operator lets us add more text to an existing string.

For example:

```php
$sql = "SELECT * FROM happiness_index";

$sql .= " ORDER BY country";
```

The first line creates the SQL query.

The second line adds `ORDER BY country` to the end of it.

The final query becomes:

```sql
SELECT *
FROM happiness_index
ORDER BY country;
```

Notice that we included a space before `ORDER BY`. This prevents the new text from joining directly to the previous word.

We can use this same technique to add search conditions only when the visitor provides a value.

However, there's one small problem we need to solve first: **when should we use `WHERE`, and when should we use `AND`?**

---

## 11. Using `WHERE 1 = 1`

When writing SQL queries, we use:

- `WHERE` to introduce the first condition.
- `AND` to add another condition.

For example, if we only search by country:

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%land%';
```

But if we also want to filter by wellbeing score:

```sql
SELECT *
FROM happiness_index
WHERE country LIKE '%land%'
  AND wellbeing >= 6;
```

The problem is that our search fields are optional.

If the visitor only enters a wellbeing score, we need:

```sql
SELECT *
FROM happiness_index
WHERE wellbeing >= 6;
```

We cannot start with `AND wellbeing >= 6` because `AND` needs an existing condition before it.

This means PHP would normally need to check whether it's adding the first condition or an additional one.

### A Simple Solution: `WHERE 1 = 1`

To make things easier, we can start our query with:

```sql
SELECT *
FROM happiness_index
WHERE 1 = 1;
```

What does `1 = 1` mean?

It simply checks whether 1 equals 1.

Since this is always true, **every record passes this condition**. Nothing is filtered out.

The benefit is that our query already contains `WHERE`, so any new search condition can begin with `AND`.

### Adding the Country Condition

First, we create our starting query:

```php
$sql = "SELECT * FROM happiness_index WHERE 1 = 1";
```

Then we check whether the visitor entered a country:

```php
if ($country_search !== '') {
    $sql .= " AND country LIKE CONCAT('%', ?, '%')";
}
```

Here's what happens:

- If the country field contains a value, PHP adds the country condition.
- If the country field is empty, PHP skips that condition.

The `?` is a placeholder for the visitor's input. We will provide its value later using a prepared statement.

### Adding the Wellbeing Condition

We can do the same for the minimum wellbeing score:

```php
if ($minimum_wellbeing !== '' && is_numeric($minimum_wellbeing)) {
    $sql .= " AND wellbeing >= ?";
}
```

This checks two things:

1. The wellbeing field is not empty.
2. The entered value is a number.

If both checks pass, PHP adds the wellbeing condition to the query.

### What Does the Final Query Look Like?

If the visitor enters both a country and a valid wellbeing score, PHP builds:

```sql
SELECT *
FROM happiness_index
WHERE 1 = 1
  AND country LIKE CONCAT('%', ?, '%')
  AND wellbeing >= ?;
```

If the visitor only enters a country, PHP builds:

```sql
SELECT *
FROM happiness_index
WHERE 1 = 1
  AND country LIKE CONCAT('%', ?, '%');
```

If both fields are empty, the query remains:

```sql
SELECT *
FROM happiness_index
WHERE 1 = 1;
```

This returns all records because no additional search conditions were added.

### Why Use `WHERE 1 = 1`?

It allows us to build a query without worrying about whether to use `WHERE` or `AND` for each optional search field.

**Remember:** `WHERE 1 = 1` does not filter the records. It simply provides a starting condition so we can use `AND` for every optional search condition.

The actual searching happens when we add conditions such as `country LIKE ...` or `wellbeing >= ...`.

The `?` placeholders must still be supplied with values through a prepared statement before the query is executed.

Now that we know how to build a query with optional conditions, we need to make sure each `?` placeholder receives the correct value. We'll learn how to do that using **dynamic prepared statements** in the next section.


## 12. Dynamic Prepared Statements

Dynamic queries still need prepared statements.

The challenge is that the number of placeholders may change depending on the visitor's choices.

For example:

- Searching only by country name requires one placeholder.
- Searching only by wellbeing requires one placeholder.
- Searching by both requires two placeholders.
- Leaving both fields empty requires no placeholders.

We need to track three things:

1. The SQL query.
2. The values that will replace the placeholders.
3. The data type of each value.

Let's begin with three variables:

```php
$sql = "SELECT * FROM happiness_index WHERE 1 = 1";

$parameters = [];
$datatypes = "";
```

The `$parameters` array stores the submitted values.

The `$datatypes` string stores the MySQLi parameter types:

| Type | Meaning | Example |
|---|---|---|
| `s` | String | Country name |
| `d` | Decimal number | Wellbeing score |

### Example 1: Country Name

Suppose someone searches for `Canada`.

```php
if ($country_search !== '') {
    $sql .= " AND country LIKE CONCAT('%', ?, '%')";

    $parameters[] = $country_search;
    $datatypes .= "s";
}
```

This adds one placeholder, one parameter, and one data type.

Our variables would contain:

```php
$parameters = ['Canada'];
$datatypes = "s";
```

### Example 2: Minimum Wellbeing Score

Suppose the visitor also wants countries with a wellbeing score of at least 6.

```php
if ($minimum_wellbeing !== '' && is_numeric($minimum_wellbeing)) {
    $sql .= " AND wellbeing >= ?";

    $parameters[] = (float) $minimum_wellbeing;
    $datatypes .= "d";
}
```

`is_numeric()` checks whether the value is a number.

`(float)` converts the value to a number that can contain decimals.

If both country name and wellbeing are provided, the SQL becomes:

```sql
SELECT *
FROM happiness_index
WHERE 1 = 1
  AND country LIKE CONCAT('%', ?, '%')
  AND wellbeing >= ?;
```

And our PHP variables would contain values similar to:

```php
$parameters = ['Canada', 6];
$datatypes = "sd";
```

**The order matters.** The first parameter replaces the first `?`, and the second parameter replaces the second `?`.

### Binding the Parameters

After building the SQL, we prepare the statement:

```php
$statement = $conn->prepare($sql);
```

If we have parameters, we can use the PHP spread operator (`...`) to pass the values to `bind_param()`:

```php
if ($datatypes !== '') {
    $statement->bind_param($datatypes, ...$parameters);
}

$statement->execute();
$result = $statement->get_result();
```

The spread operator passes the array values as separate arguments.

For example:

```php
$parameters = ['Canada', 6];
$datatypes = "sd";
```

The spread operator lets us bind both values without writing a separate `bind_param()` call for every possible combination.

We check `$datatypes !== ''` because there is nothing to bind when both search fields are empty.


## 13. Complete Advanced Search Example

Now let's put these ideas together.

To keep the example manageable, we'll use just two search options:

1. Country name.
2. Minimum wellbeing score.

Visitors can use either option or combine both.

### Create `search.php`

This example assumes our existing `includes/header.php` creates the `$conn` database connection.


```php

<?php

$title = 'Advanced Country Search';
include 'includes/header.php';

// Read the search values.
$country_search = trim($_GET['country-search'] ?? '');
$minimum_wellbeing = $_GET['minimum-wellbeing'] ?? '';

// Build the SQL query.
$sql = "SELECT *
        FROM happiness_index
        WHERE 1 = 1";

$parameters = [];
$datatypes = "";

// Country name.
if ($country_search !== '') {
    $sql .= " AND country LIKE CONCAT('%', ?, '%')";

    $parameters[] = $country_search;
    $datatypes .= "s";
}

// Minimum wellbeing score.
if ($minimum_wellbeing !== '' && is_numeric($minimum_wellbeing)) {
    $sql .= " AND wellbeing >= ?";

    $parameters[] = (float) $minimum_wellbeing;
    $datatypes .= "d";
}

$sql .= " ORDER BY country";

// Prepare and execute the query.
$statement = $conn->prepare($sql);

if ($datatypes !== '') {
    $statement->bind_param($datatypes, ...$parameters);
}

$statement->execute();
$result = $statement->get_result();

?>

<h2 class="display-5 my-3">Advanced Country Search</h2>

<p class="lead mb-4">
    Search countries by name, minimum wellbeing score, or both.
</p>

<form action="search.php" method="GET" class="border rounded p-4 mb-4">

    <!-- Country name -->
    <div class="mb-4">
        <label for="country-search" class="form-label">
            Country Name
        </label>

        <input
            type="search"
            id="country-search"
            name="country-search"
            class="form-control"
            value="<?= htmlspecialchars($country_search) ?>"
            placeholder="Enter a country name"
        >
    </div>

    <!-- Minimum wellbeing -->
    <div class="mb-4">
        <label for="minimum-wellbeing" class="form-label">
            Minimum Wellbeing Score
        </label>

        <input
            type="number"
            id="minimum-wellbeing"
            name="minimum-wellbeing"
            class="form-control"
            min="0"
            max="10"
            step="0.1"
            value="<?= htmlspecialchars((string) $minimum_wellbeing) ?>"
            placeholder="Enter a score between 0 and 10"
        >
    </div>

    <button type="submit" class="btn btn-success">
        Search
    </button>

    <a href="search.php" class="btn btn-outline-secondary">
        Clear Search
    </a>

</form>

<h3 class="mb-3">Results</h3>

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
                    <td>
                        <?= htmlspecialchars((string) $row['rank']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['country']) ?>
                    </td>

                    <td>
                        <a
                            class="link-success"
                            href="country.php?rank=<?= urlencode($row['rank']) ?>"
                        >
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No countries match your search options.</p>

<?php endif; ?>

<?php

$statement->close();
include 'includes/footer.php';

?>

```

### Understanding the Completed Example

The important part is not memorizing the entire file.

Focus on how each form input contributes to the SQL query.

| Form input | SQL condition |
|---|---|
| Country name | `AND country LIKE CONCAT('%', ?, '%')` |
| Minimum wellbeing | `AND wellbeing >= ?` |

The SQL only includes conditions for values the visitor has supplied.

### Why Do We Need an Array of Parameters?

A visitor might search only by country name, so there is one placeholder.

Another visitor might provide both a country name and a wellbeing score, so there are two placeholders.

The `$parameters` array and `$datatypes` string allow our prepared statement to work with different combinations.

This is important for Lab 03, where visitors will combine keyword searches with optional filters.

### Test the Advanced Search

Try the following:

1. Search for a country name.
2. Enter a minimum wellbeing score of `6`.
3. Combine a country name with a minimum wellbeing score.
4. Leave the country name empty and search only by wellbeing.
5. Leave both fields empty and confirm that all countries appear.
6. Search for a nonexistent country and check the no-results message.
7. Submit the form and confirm that both input values remain visible.
8. Clear the form and confirm that the search options reset.

---

# Part 3 — Exercise

## 14. Exercise — City Search

In Lessons 13 and 14, we created a City List, a City Details page, and pagination using the `cities` table.

Now we'll add searching to the existing `cities.php`.

### Part 1: Create a Search Form

Add a search form above the city table.

Your form should:

1. Use the `GET` method.
2. Include a text input named `keyword`.
3. Include a Search button.
4. Keep the submitted keyword visible in the input field.

### Part 2: Search the Database

Update the SQL query to search both `city_name` and `province`.

For example, entering `Alberta` should find cities whose city name or province contains that keyword.

Use:

- SQL `LIKE` with wildcards.
- `OR` to search both columns.
- A prepared statement to bind the keyword.

### Part 3: Display the Results

Update your City List to:

1. Display only matching cities.
2. Show a helpful message if no cities match.
3. Keep the View Details links working.
4. Continue using `htmlspecialchars()` when displaying database values.

Use the existing column names: `cid`, `city_name`, and `province`.

### Part 4: Challenge — Search and Pagination

In Lesson 14, we added pagination to `cities.php`.

Can you make pagination work with the search results?

For example, if someone searches for `Alberta`, clicking page 2 should continue displaying only matching cities.

**Hint:** You'll need to count the matching records, not all records, and include the search keyword in the pagination URLs.

This is an optional challenge for now. We'll revisit combining features in Lesson 16.

### Part 5: Challenge — Dynamic City Search

In Part 2 of this lesson, we learned how to build dynamic SQL queries using optional search conditions.

As an additional challenge, add a minimum population field to your City Search form.

Visitors should be able to:

1. Search by city name or province.
2. Enter a minimum population.
3. Use both search options together.
4. Leave either option empty.

For example, a visitor might search for cities in `Alberta` with a population of at least `100000`.

**Hint:** Use `WHERE 1 = 1`, add conditions with `.=` and store your parameters in an array.

This challenge is optional. We'll practise combining multiple conditions again in Lesson 16.

### Test Your Work

| Test | Expected Result |
|---|---|
| Search by city name | Matching cities appear |
| Search by province | Cities in matching provinces appear |
| Search with a partial word | Partial matches appear |
| Search for something nonexistent | A no-results message appears |
| Submit an empty search | All cities appear |
| Click View Details | The correct city opens |
| Change pagination page (challenge) | Search keyword remains applied |
| Search with minimum population (challenge) | Only cities meeting the population requirement appear |
| Combine keyword and population (challenge) | Both conditions are applied |
