# PHP Environment Setup

In this lesson, we'll be setting up everything we need in order to develop web applications with PHP/MySQL. 

But before we get there, let's go over all of the tools and terminology we'll be using in the rest of the course.

---

## The LAMP Stack & Related Tools

### What is a LAMP Stack?

**LAMP** is an acronym for a popular open-source software stack used to build dynamic websites and web applications. 

| Letter | Stands For            | Description                                                                     |
|--------|-----------------------|---------------------------------------------------------------------------------|
| **L**  | **Linux**             | The operating system that runs the server.                                      |
| **A**  | **Apache**            | The web server software that handles HTTP requests and serves web pages.        |
| **M**  | **MySQL**/**MariaDB** | The database engine used to store and retrieve data.                            |
| **P**  | **PHP**               | The server-side scripting language used to build dynamic, data-driven websites. |

All of these components work together to serve full-stack web applications. 

Now, let's go through the programming languages, software, and tools we'll be using in this course. 


### 🐘 PHP
- A **server-side scripting language** used to create dynamic web pages.
- Embedded in HTML and processed by the server before the page is sent to the browser.
- Connects to databases, processes forms, manages sessions, and more.
- Files end with `.php`.


### 🗄️ SQL (Structured Query Language)
- A **language**, not a software program.
- Used to create, read, update, and delete data in **relational databases**.
- Standardized, but specific database systems (like MySQL or MariaDB) have their own "flavour" or dialect.


### 🐬 MySQL
- An **open-source relational database management system (RDBMS)**.
- Developed by Oracle Corporation.
- Uses SQL to store and retrieve data.
- Once the most popular database in the world (now only second to Oracle Database), especially with PHP-based applications.


### 🦭 MariaDB
- A **drop-in replacement for MySQL** — uses the same SQL syntax and commands.
- Created by the original developers of MySQL after Oracle bought it.
- **Open-source and community-driven**, with some performance and feature improvements over MySQL.
- Many hosting environments (including our own) now use MariaDB instead of MySQL.


For this course, we will technically be using **MariaDB** (as it is open source).

### 🧮 phpMyAdmin
- A **web-based interface** for managing MySQL/MariaDB databases.
- phpMyAdmin lets us:
  - Create tables and databases
  - Run SQL queries
  - Import/export data
  - Browse and edit records
- Great for beginners who aren’t ready to write raw SQL in a terminal.


### Other Libraries, Tools, & Languages

We will be using a variety of other libraries, tools, and languages in this course. Many of them will already be familiar to you, while we will be learning others together. These include:

- Hypertext Markup Language (HTML)
- Cascading Stylesheets (CSS)
- JavaScript (JS)
- Bootstrap Front-End Framework
- Valitron Library
- PHP Mailer Library

---

### How It All Fits Together

Here’s what happens when you visit a PHP webpage that uses a database:

1. You visit `mywebsite.com/search.php` in a browser. 
2. Apache receives your request.
3. PHP is executed on the server.
4. PHP code connects to MariaDB and runs an SQL query.
5. The results are turned into HTML.
6. Apache sends the final HTML page to your browser.

---

### tl;dr: What Thing Does What?

| Tool                    | Role                              | Interface Type         |
|-------------------------|-----------------------------------|------------------------|
| **PHP**                 | Server-side logic (backend)       | Code editor            |
| **SQL**                 | Talks to the database             | phpMyAdmin or script   |
| **MySQL** / **MariaDB** | Stores the data                   | phpMyAdmin or terminal |
| **phpMyAdmin**          | GUI for managing the database     | Web browser            |