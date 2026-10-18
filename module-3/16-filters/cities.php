
<?php
$title = 'City Search and Filters';
include 'includes/header.php';

// --------------------------------------------------
// 1. Get the available provinces from the database.
// --------------------------------------------------

$province_sql = "
    SELECT DISTINCT province
    FROM cities
    WHERE province IS NOT NULL AND province <> ''
    ORDER BY province
";

$province_result = $conn->query($province_sql);

$available_provinces = [];

while ($row = $province_result->fetch_assoc()) {
    $available_provinces[] = $row['province'];
}

// --------------------------------------------------
// 2. Read the keyword and selected provinces.
// --------------------------------------------------

$keyword = trim($_GET['keyword'] ?? '');

$selected_provinces = $_GET['provinces'] ?? [];

if (!is_array($selected_provinces)) {
    $selected_provinces = [];
}

// Keep only province values that exist in the database.
$selected_provinces = array_values(array_intersect(
    $selected_provinces,
    $available_provinces
));

// --------------------------------------------------
// 3. Build the search and filter conditions.
// --------------------------------------------------

$where = ' WHERE 1 = 1';
$parameters = [];
$datatypes = '';

// Search city name OR province.
if ($keyword !== '') {
    $where .= ' AND (city_name LIKE ? OR province LIKE ?)';

    $search_term = '%' . $keyword . '%';

    $parameters[] = $search_term;
    $parameters[] = $search_term;

    $datatypes .= 'ss';
}

// Filter by one or more provinces.
if (!empty($selected_provinces)) {
    $placeholders = implode(
        ', ',
        array_fill(0, count($selected_provinces), '?')
    );

    $where .= " AND province IN ($placeholders)";

    foreach ($selected_provinces as $province) {
        $parameters[] = $province;
        $datatypes .= 's';
    }
}

// --------------------------------------------------
// 4. Count the matching cities for pagination.
// --------------------------------------------------

$count_sql = "SELECT COUNT(*) AS total FROM cities $where";

$count_statement = $conn->prepare($count_sql);

if ($datatypes !== '') {
    $count_statement->bind_param($datatypes, ...$parameters);
}

$count_statement->execute();

$count_result = $count_statement->get_result();
$total_records = (int) $count_result->fetch_assoc()['total'];

$count_statement->close();

// --------------------------------------------------
// 5. Calculate pagination values.
// --------------------------------------------------

$per_page = 10;

$total_pages = max(1, (int) ceil($total_records / $per_page));

$page = filter_input(
    INPUT_GET,
    'page',
    FILTER_VALIDATE_INT
);

if ($page === false || $page === null || $page < 1) {
    $page = 1;
}

$page = min($page, $total_pages);

$offset = ($page - 1) * $per_page;

// --------------------------------------------------
// 6. Get the matching cities for the current page.
// --------------------------------------------------

$sql = "
    SELECT cid, city_name, province, population
    FROM cities
    $where
    ORDER BY city_name, cid
    LIMIT ? OFFSET ?
";

$statement = $conn->prepare($sql);

// Add the pagination parameters.
$query_parameters = $parameters;
$query_parameters[] = $per_page;
$query_parameters[] = $offset;

$query_datatypes = $datatypes . 'ii';

$statement->bind_param(
    $query_datatypes,
    ...$query_parameters
);

$statement->execute();
$result = $statement->get_result();

// --------------------------------------------------
// 7. Create a helper function for pagination URLs.
// --------------------------------------------------

function cityPageUrl($page_number, $keyword, $selected_provinces) {
    $query = [
        'page' => $page_number
    ];

    if ($keyword !== '') {
        $query['keyword'] = $keyword;
    }

    if (!empty($selected_provinces)) {
        $query['provinces'] = $selected_provinces;
    }

    return 'cities.php?' . http_build_query($query);
}
?>

<h2 class="display-5 my-3">City Search and Filters</h2>

<p class="lead">
    Search for cities and filter the results by province.
</p>

<!-- Search and filter form -->

<form action="cities.php" method="GET"
      class="border rounded p-4 mb-4">

    <div class="mb-3">
        <label for="keyword" class="form-label">
            Search by City or Province
        </label>

        <input
            type="text"
            class="form-control"
            id="keyword"
            name="keyword"
            value="<?= htmlspecialchars($keyword) ?>"
            placeholder="Enter a city or province"
        >
    </div>

    <fieldset>
        <legend class="fs-5">Filter by Province</legend>

        <?php foreach ($available_provinces as $index => $province): ?>

            <div class="form-check">

                <input
                    type="checkbox"
                    class="form-check-input"
                    id="province-<?= $index ?>"
                    name="provinces[]"
                    value="<?= htmlspecialchars($province) ?>"
                    <?= in_array($province, $selected_provinces, true)
                        ? 'checked' : '' ?>
                >

                <label
                    class="form-check-label"
                    for="province-<?= $index ?>"
                >
                    <?= htmlspecialchars($province) ?>
                </label>

            </div>

        <?php endforeach; ?>
    </fieldset>

    <button type="submit" class="btn btn-success mt-3">
        Apply Search and Filters
    </button>

    <a href="cities.php"
       class="btn btn-outline-secondary mt-3">
        Clear Filters
    </a>

</form>

<!-- Search results -->

<h3>Results</h3>

<p>
    <?= $total_records ?>
    <?= $total_records === 1 ? 'city' : 'cities' ?> found.
</p>

<?php if ($total_records > 0): ?>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>City</th>
                <th>Province</th>
                <th>Population</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($row['city_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['province']) ?>
                    </td>

                    <td>
                        <?= number_format((int) $row['population']) ?>
                    </td>

                    <td>
                        <a href="city.php?cid=<?= urlencode((string) $row['cid']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <!-- Pagination -->

    <?php if ($total_pages > 1): ?>

        <?php
        // Display a maximum of five numbered page links.
        $window_size = 5;
        $start_page = max(1, $page - 2);
        $end_page = min(
            $total_pages,
            $start_page + $window_size - 1
        );

        $start_page = max(
            1,
            $end_page - $window_size + 1
        );
        ?>

        <nav aria-label="City results pagination">
            <ul class="pagination flex-wrap">

                <!-- Previous -->

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">

                    <?php if ($page > 1): ?>
                        <a
                            class="page-link"
                            href="<?= htmlspecialchars(cityPageUrl(
                                $page - 1,
                                $keyword,
                                $selected_provinces
                            )) ?>"
                        >
                            Previous
                        </a>
                    <?php else: ?>
                        <span class="page-link">Previous</span>
                    <?php endif; ?>

                </li>

                <!-- Numbered page links -->

                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>

                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">

                        <a
                            class="page-link"
                            href="<?= htmlspecialchars(cityPageUrl(
                                $i,
                                $keyword,
                                $selected_provinces
                            )) ?>"
                        >
                            <?= $i ?>
                        </a>

                    </li>

                <?php endfor; ?>

                <!-- Next -->

                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">

                    <?php if ($page < $total_pages): ?>
                        <a
                            class="page-link"
                            href="<?= htmlspecialchars(cityPageUrl(
                                $page + 1,
                                $keyword,
                                $selected_provinces
                            )) ?>"
                        >
                            Next
                        </a>
                    <?php else: ?>
                        <span class="page-link">Next</span>
                    <?php endif; ?>

                </li>

            </ul>
        </nav>

    <?php endif; ?>

<?php else: ?>

    <p class="alert alert-info">
        No cities match your search and selected provinces.
        Try a different keyword or clear the filters.
    </p>

<?php endif; ?>

<?php
$statement->close();
include 'includes/footer.php';
?>
