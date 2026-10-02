# SQL & MySQL Introduction

So far, the data in our PHP applications has mostly existed inside variables, arrays, forms, and files. This works for smaller examples, but most web applications need a way to store and manage larger amounts of data permanently.

A **database** allows us to store, organise, retrieve, update, and delete data.

In this lesson, we will learn how databases are organised, how MySQL stores our data, and how to use SQL to interact with a database.

We will use **phpMyAdmin** to execute our SQL queries and view the results.

> **Note:** If you are unclear about Docker, Apache, PHP, or the other tools in our development environment, refer back to the `README.md` for [Lesson 1: PHP Environment Setup](https://github.com/Rafat-Bin/DMIT2025-Student-Workbook/tree/main/module-0/01-local-environment-setup).

---

## 1. Terminology

Before we begin writing SQL, let's look at some important database terminology.

### Database

A **database** is an organised collection of data. A database can contain multiple tables.

For example, an online store might have a database containing tables for:

- products
- customers
- orders

In our Docker environment, we already have a database named:

```text
dmit2025
```

We will use this database throughout this lesson.

---

### Table

A **table** stores related data using columns and rows.

Each table should normally represent one concept.

For example:

```text
dmit2025
│
├── cities
├── products
└── customers
```

In this lesson, we will create and work with a table called `cities`.

---

### Column

A **column** represents one piece of information that we want to store.

For our `cities` table, we will have columns such as:

```text
cid
city_name
province
population
is_capital
trivia
```

Each column also has a **data type**, which determines what kind of value can be stored in it.

For example:

```text
city_name   → text
population  → whole number
is_capital  → true or false
```

---

### Row / Record

A **row**, also called a **record**, represents one complete item stored in a table.

For example:

```text
cid | city_name | province | population
----|-----------|----------|-----------
1   | Toronto   | ON       | 2731571
2   | Ottawa    | ON       | 1013242
```

Toronto is one record and Ottawa is another record.

---

### Field

A **field** is the value found where a particular row and column meet.

For example, in this record:

```text
city_name | province
----------|---------
Edmonton  | AB
```

`Edmonton` is the value stored in the `city_name` field.

---

### Primary Key

A **primary key** is a column that uniquely identifies each record in a table.

Our `cities` table will use:

```text
cid
```

as its primary key.

For example:

```text
cid | city_name
----|----------
1   | Toronto
2   | Ottawa
3   | Edmonton
```

Even if two records contained similar information, their primary key values would still be different.

---

### Foreign Key

A **foreign key** is a column that references a record in another table.

Foreign keys allow us to create relationships between tables.

For example, an `orders` table could contain a `customer_id` that identifies which customer placed an order.

We will work more with relationships between tables later. For now, it is important to understand that databases can contain multiple related tables.

---

### Index

An **index** helps MySQL locate records more efficiently.

You can think of it like the index at the back of a textbook. Instead of searching every page for a topic, the index helps you find where that information is located.

We will not be creating our own indexes in this lesson, but you may encounter them when working with databases.

---

## 2. CRUD

Most of the work we perform with databases falls into four basic operations:

**CRUD** stands for:

```text
Create
Read
Update
Delete
```

Later in this lesson, we will see that these operations correspond to SQL commands:

```text
CRUD        SQL

Create  →   INSERT
Read    →   SELECT
Update  →   UPDATE
Delete  →   DELETE
```

Understanding these four operations will be important throughout the rest of this module.

---

## 3. What Is MySQL?

**MySQL** is a relational database management system, or **DBMS**.

A DBMS is software that manages databases and allows us to store, retrieve, modify, and delete data.

Our Docker development environment already includes MySQL, so you do **not** need to install MySQL separately.

Our environment looks roughly like this:

```text
Docker
│
├── PHP / Apache
│
├── MySQL
│
└── phpMyAdmin
```

MySQL is the software that actually manages our databases.

In our environment, MySQL contains the database:

```text
MySQL
└── dmit2025
```

Soon, we will create a `cities` table inside that database:

```text
MySQL
└── dmit2025
      └── cities
```

---

## 4. What Is phpMyAdmin?

Although MySQL manages our database, we need a convenient way to interact with it while learning SQL.

For this lesson, we will use **phpMyAdmin**.

phpMyAdmin is a web-based graphical interface for working with MySQL.

The relationship looks like this:

```text
You
 ↓
phpMyAdmin
 ↓
MySQL
 ↓
dmit2025
 ↓
cities
```

When we execute an SQL query in phpMyAdmin, phpMyAdmin sends that command to MySQL.

MySQL executes the command and returns the result.

For example:

```sql
SELECT * FROM cities;
```

phpMyAdmin sends this query to MySQL, MySQL retrieves the requested records, and phpMyAdmin displays the results.

> In the next lesson, we will learn how PHP can communicate with MySQL directly using MySQLi. For now, we will use phpMyAdmin so that we can concentrate on learning SQL.

---

## 5. Opening phpMyAdmin

Before opening phpMyAdmin, make sure that Docker Desktop is running.

In VS Code, open a terminal in the project containing your `compose.yml` file and start the containers:

```bash
docker compose up -d
```

Then open your browser and go to:

```text
http://localhost:8081
```

Log in using the MySQL student account configured for our development environment.

```text
Username: student
Password: student
```

After logging in, you should see several databases listed on the left side.

One of them should be:

```text
dmit2025
```

You may also see databases such as:

```text
information_schema
performance_schema
```

These are system databases used by MySQL. We will not modify them.

Click:

```text
dmit2025
```

This is the database we will use for our exercises.

---

## 6. Working with SQL Files

We will write and save our SQL in `.sql` files using **VS Code**.

This lesson contains several SQL files:

```text
11-sql-introduction/
│
├── init.sql
├── selects.sql
├── inserts.sql
├── updates.sql
└── delete.sql
```

Each file has a different purpose:

```text
init.sql      → creates our table and starter data
selects.sql   → retrieves data
inserts.sql   → adds new records
updates.sql   → modifies records
delete.sql    → removes records
```

During this lesson, our general workflow will be:

```text
VS Code
   ↓
Write or open SQL
   ↓
Copy the query
   ↓
phpMyAdmin
   ↓
SQL tab
   ↓
Paste the query
   ↓
Click Go
   ↓
MySQL executes the query
   ↓
phpMyAdmin displays the result
```

You can also type SQL directly into phpMyAdmin when experimenting, but keeping your queries in `.sql` files means that you have a saved copy of your work.

---

## 7. Creating the Cities Table

Let's create our first table.

Open `init.sql` in VS Code.

The first query creates a table named `cities`:

```sql
CREATE TABLE cities (
    cid SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(36) NOT NULL,
    province ENUM(
        'AB', 'BC', 'MB', 'NB', 'NL', 'NS',
        'ON', 'PE', 'QC', 'SK', 'NT', 'NU', 'YT'
    ) NOT NULL,
    population INT UNSIGNED NOT NULL,
    is_capital BOOLEAN NOT NULL DEFAULT FALSE,
    trivia VARCHAR(255) NULL
);
```

To execute this query:

1. Copy the `CREATE TABLE` statement from `init.sql`.
2. Open phpMyAdmin.
3. Select the `dmit2025` database.
4. Click the **SQL** tab.
5. Paste the query into the SQL editor.
6. Click **Go**.

After the query runs successfully, you should see a new table named:

```text
cities
```

Our database now looks like this:

```text
MySQL
└── dmit2025
      └── cities
```

---

## 8. Understanding the Cities Table

Let's look more closely at the columns we just created.

```text
Column        Purpose

cid           Unique identifier for each city
city_name     Name of the city
province      Province or territory
population    Population of the city
is_capital    Whether the city is a capital
trivia        Optional information about the city
```

Each column also defines rules about the type of data that it can contain.

We will look at these rules next.
