# CRUD Applications: Add

Allowing our user to add, edit, or delete things in our database via a webform is inherently risky. This is largely due to the way that SQL is syntactically structured -- and how this predictable structure can be easily exploited.

The number one way that a bad actor (either a user with malicious intentions or a script or bot that executes arbitrary code) can ruin our database is through SQL Injections. We know from previous lessons that the best way to prevent SQL Injections is by sanitising inputs, validating user-provided data, and to use prepared statements. 

For this lesson, we will be building a form that a user can fill out to add an entry into our database. On the back end, we'll also be starting a series of functions for preparing and executing prepared statements. 


## Getting Started  

Before beginning today's lesson, follow these steps:  

1. **Copy and Paste Previous Files**  
   - Locate the `private` and `public` directories from the previous lesson.  
   - Copy and paste them into your working directory for this lesson.  

2. **Upload Files to the Server**  
   - Use an FTP client (e.g., FileZilla) to upload the copied files to the server.  
   - Verify that all files are successfully transferred before proceeding.  

### Files to Modify Today  

Today, we'll be working with the following files:  

- **`private/functions.php`** – Add a data validation function, plus an array for provinces and territories.  
- **`private/prepared.php`** – Add an `INSERT` function.  
- **New File:** `public/add.php`