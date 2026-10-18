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
