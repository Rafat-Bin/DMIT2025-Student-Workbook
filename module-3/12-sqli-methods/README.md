# MySQLi Methods

In the previous lesson, we learned how to write SQL queries and run them using phpMyAdmin.

Now, we are going to learn how to run those same SQL queries using **PHP**.

This allows our PHP applications to retrieve information from a MySQL database and display it on a webpage.

---

## PHP Database Interaction

There are a few basic steps when working with a database using PHP:

1. Connect to the database.
2. Write an SQL query.
3. Send the query to MySQL.
4. Work with the returned data.
5. Close the database connection.

The general process looks like this:

```text
PHP
 ↓
Connect to MySQL
 ↓
Send SQL query
 ↓
MySQL runs the query
 ↓
PHP receives the result
 ↓
Display the data
```

In this lesson, we will use **MySQLi** to do this.

---

## What Is MySQLi?

**MySQLi** stands for **MySQL Improved**.

It is a PHP extension that provides functions for communicating with a MySQL database.

For example, MySQLi gives us functions that allow us to:

- connect to MySQL;
- execute SQL queries;
- check how many records were returned;
- retrieve records from a query result; and
- close the database connection.

PHP provides different ways of communicating with databases, but we will use **MySQLi** throughout this course.

---

## Creating a Database Connection

Before PHP can send a query to MySQL, it must establish a connection.

MySQLi provides the `mysqli_connect()` function:

```php
mysqli_connect($host, $username, $password, $database);
```

The function requires information about the MySQL server and the database we want to use.

The returned connection is usually stored in a variable:

```php
$conn = mysqli_connect($host, $username, $password, $database);
```

We can then use `$conn` whenever PHP needs to communicate with MySQL.

---

### Checking the Connection

A database connection can fail.

For example:

- the database server may not be running;
- the username or password may be incorrect; or
- the database may not exist.

We can check for a connection error using:

```php
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
```

`mysqli_connect_errno()` checks whether a connection error occurred.

If there is an error, `mysqli_connect_error()` gives us information about what went wrong.

---

## Setting Up Our Database Connection

Instead of writing our database connection information on every PHP page, we can keep it in a separate file.

In our project, we will use a file called:

```text
connect.php
```

Our Docker environment already provides the MySQL server and database that we need.

A connection function can look like this:

```php
<?php

function db_connect() {

    $host = 'mysql';
    $username = 'student';
    $password = 'student';
    $database = 'dmit2025';

    $conn = mysqli_connect(
        $host,
        $username,
        $password,
        $database
    );

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    return $conn;
}
```

The function creates the database connection and returns it.

This means that our other PHP pages do not need to repeat all of the connection information.

---
## Using `connect.php`

To use our database connection on another PHP page, we first load `connect.php`.

For this lesson, our files are organised like this:

```text
lesson12/
├── data/
│   └── connect.php
│
└── test.php
```

Because `data` is inside the same directory as `test.php`, we can write:

```php
require_once __DIR__ . '/data/connect.php';
```

`__DIR__` represents the directory containing the current PHP file.

We then call our connection function:

```php
$conn = db_connect();
```

Now:

```php
$conn
```

represents our connection to MySQL.

The complete setup is:

```php
<?php

require_once __DIR__ . '/data/connect.php';

$conn = db_connect();
```

We only need to establish the connection once on a page.

> **Note:** The path to `connect.php` depends on your project's folder structure. If the PHP file is moved to another directory, the path in `require_once` may also need to change.

---

## Writing an SQL Query in PHP

In Lesson 11, we ran SQL directly through phpMyAdmin.

For example:

```sql
SELECT city_name, province
FROM cities
LIMIT 5;
```

PHP can send the same SQL to MySQL.

First, we store the SQL statement inside a PHP string:

```php
$sql = "SELECT city_name, province FROM cities LIMIT 5";
```

At this point, PHP has **not run the query**.

`$sql` is simply a string containing our SQL statement.

Think of it like this:


The next step is to send that SQL to MySQL.

---

## Running the Query

To send the SQL statement to MySQL, we use `mysqli_query()`:

```php
$result = mysqli_query($conn, $sql);
```

`mysqli_query()` needs two things:

1. the database connection;
2. the SQL query.


```php
$sql = "SELECT city_name, province FROM cities LIMIT 5";

$result = mysqli_query($conn, $sql);
```

Think of it like this:

```text
$sql
 ↓
mysqli_query()
 ↓
MySQL
 ↓
$result
```


---

## Checking for Results

After running a `SELECT` query, we can check how many records were returned.

For this, we use:

```php
mysqli_num_rows($result);
```

For example:

```php
if (mysqli_num_rows($result) > 0) {
    // Records were found.
} else {
    // No records were found.
}
```

This allows us to make sure records exist before trying to display them.

For example:

```php
$sql = "SELECT city_name FROM cities WHERE province = 'AB'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<p>Cities were found.</p>";
} else {
    echo "<p>No cities were found.</p>";
}
```

At this point, however, we still haven't retrieved the individual records from `$result`.

---

## Fetching a Record

The query result contains the records returned by MySQL.

To retrieve a record from the result, we can use:

```php
mysqli_fetch_assoc($result);
```

For example:

```php
$row = mysqli_fetch_assoc($result);
```

`mysqli_fetch_assoc()` returns the record as an **associative array**.

If the database returned this record:

```text
city_name   province
Edmonton    AB
```

PHP could access the values using:

```php
$row['city_name'];
$row['province'];
```

For example:

```php
$city_name = $row['city_name'];
$province = $row['province'];

echo "<p>$city_name, $province</p>";
```

This works because the column names returned by our SQL query become the keys in the associative array.

```text
Database column       PHP array key

city_name      →      $row['city_name']
province       →      $row['province']
population     →      $row['population']
```

---

## Fetching Multiple Records

Most `SELECT` queries return more than one record.

We can use a `while` loop to retrieve the records one at a time.

```php
while ($row = mysqli_fetch_assoc($result)) {
    $city_name = $row['city_name'];
    $province = $row['province'];

    echo "<p>$city_name, $province</p>";
}
```

Each time the loop runs, `mysqli_fetch_assoc()` retrieves the next record.

The process looks like this:


We can combine this with `mysqli_num_rows()`:

```php
$sql = "SELECT city_name, province FROM cities LIMIT 5";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
        $city_name = $row['city_name'];
        $province = $row['province'];

        echo "<p>$city_name, $province</p>";
    }

} else {
    echo "<p>No cities were found.</p>";
}
```


---

## Displaying Results

Database records can be displayed using regular HTML.

The way we retrieve the data does not change. We simply change the HTML that we generate.

### Displaying a List

For example, we could create a list of cities in Alberta:

```php
$sql = "SELECT city_name FROM cities WHERE province = 'AB'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    echo "<ol>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>";
        echo $row['city_name'];
        echo "</li>";
    }

    echo "</ol>";
}
```

The SQL determines which records we retrieve.

The PHP loop retrieves each record.

The HTML determines how those records appear on the webpage.

---

### Displaying a Table

We can also display database records in a table.

```php
$sql = "SELECT city_name, province, population
        FROM cities
        WHERE city_name LIKE '%john%'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    echo "<table>";

    echo "<tr>
        <th>City Name</th>
        <th>Province</th>
        <th>Population</th>
    </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $city_name = $row['city_name'];
        $province = $row['province'];
        $population = $row['population'];

        echo "<tr>";
        echo "<td>$city_name</td>";
        echo "<td>$province</td>";
        echo "<td>$population</td>";
        echo "</tr>";
    }

    echo "</table>";
}
```

The MySQLi process does not change.

Only the HTML we generate changes.

---

## Working with Different `SELECT` Queries

The SQL that we learned in Lesson 11 can now be used with PHP.

We do not need to learn a different version of SQL for PHP.

We simply store the SQL inside a PHP string and send it to MySQL using `mysqli_query()`.

### Filtering Records

For example:

```php
$sql = "SELECT city_name
        FROM cities
        WHERE province = 'AB'";
```

---

### Searching for Text

We can use `LIKE`, just as we did in Lesson 11:

```php
$sql = "SELECT city_name, province
        FROM cities
        WHERE city_name LIKE '%john%'";
```

---

### Sorting Records

We can use `ORDER BY`:

```php
$sql = "SELECT city_name, population
        FROM cities
        ORDER BY population ASC";
```

---

### Limiting Records

We can use `LIMIT`:

```php
$sql = "SELECT city_name, province
        FROM cities
        LIMIT 5";
```

The SQL changes depending on the information we need, but the PHP process remains the same:

```php
$result = mysqli_query($conn, $sql);
```

Then we work with `$result`.

---

## Queries That Return One Record

Not every query returns multiple records.

For example, suppose we want to find the city with the smallest population:

```php
$sql = "SELECT city_name, population
        FROM cities
        ORDER BY population ASC
        LIMIT 1";
```

Because we used:

```sql
LIMIT 1
```

we expect a maximum of one record.

We can run the query:

```php
$result = mysqli_query($conn, $sql);
```

Then check whether one record was returned:

```php
if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_assoc($result);

    $city_name = $row['city_name'];
    $population = $row['population'];

    echo "<p>The smallest city is <b>$city_name</b> with a population of <b>$population</b>.</p>";
}
```

Notice that we do not need a `while` loop when we only need to fetch one record.

Compare the two patterns:

```text
Multiple records
$result
   ↓
while (...)
   ↓
fetch each record


One record
$result
   ↓
if (...)
   ↓
fetch the record once
```

In the next lesson, we will build on this idea and retrieve a particular record based on information provided in the URL.

---

## Closing the Database Connection

When we are finished working with the database, we can explicitly close the connection:

```php
mysqli_close($conn);
```

PHP automatically closes open database connections when the script finishes running.

However, `mysqli_close()` can also be used when we want to explicitly close the connection ourselves.


---

# Exercise

Using PHP and MySQLi, write queries that answer the following questions using the `cities` table.

For each question, display the result on the webpage.

1. Retrieve the names of all cities in the table.
2. Find the city with the highest population.
3. List the cities located in the province of `QC`.
4. Retrieve the city names and populations for cities with a population greater than `500000`.
5. Sort the cities alphabetically by their names.
6. Find the city with the smallest population.
7. List the cities located in provinces starting with the letter `N`.
8. Retrieve the city names and populations for cities with populations between `100000` and `500000`.

For each question, think about the same basic process:

```text
Write the SQL query
        ↓
Run it with mysqli_query()
        ↓
Check the result
        ↓
Fetch the record(s)
        ↓
Display the answer
```

Do not worry about allowing the user to enter search values yet.

For now, the values in our SQL queries are written directly into the query.

We will work with user-provided values in later lessons.

---

## Summary



The important MySQLi functions introduced in this lesson are:

- `mysqli_connect()`
- `mysqli_connect_errno()`
- `mysqli_connect_error()`
- `mysqli_query()`
- `mysqli_num_rows()`
- `mysqli_fetch_assoc()`
- `mysqli_close()`

The important variables we commonly use are:

```text
$conn      → database connection
$sql       → SQL query
$result    → result returned by MySQL
$row       → one record from the result
```

In this lesson, all of our SQL values were written directly into our queries.
