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
