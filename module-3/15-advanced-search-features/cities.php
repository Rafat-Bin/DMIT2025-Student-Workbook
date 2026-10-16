
<?php

$title = 'City Search';
include 'includes/header.php';

// Read the search keyword.
$keyword = trim($_GET['keyword'] ?? '');

// Prepare the keyword for SQL LIKE.
$search_term = '%' . $keyword . '%';

// Count matching cities.
$count_sql = "SELECT COUNT(*) AS total
              FROM cities
              WHERE city_name LIKE ?
                 OR province LIKE ?";

$count_statement = $conn->prepare($count_sql);
$count_statement->bind_param(
    "ss",
    $search_term,
    $search_term
);

$count_statement->execute();

$count_result = $count_statement->get_result();
$total_records = $count_result->fetch_assoc()['total'];

$count_statement->close();

// Calculate pagination.
$per_page = 10;
$total_pages = ceil($total_records / $per_page);

// Read and validate the current page.
$current_page = filter_var(
    $_GET['page'] ?? 1,
    FILTER_VALIDATE_INT
);

if (
    !$current_page ||
    $current_page < 1 ||
    $current_page > $total_pages
) {
    $current_page = 1;
}

// Calculate the offset.
$offset = ($current_page - 1) * $per_page;

// Retrieve matching cities for the current page.
$sql = "SELECT cid, city_name, province
        FROM cities
        WHERE city_name LIKE ?
           OR province LIKE ?
        ORDER BY city_name, cid
        LIMIT ? OFFSET ?";

$statement = $conn->prepare($sql);

$statement->bind_param(
    "ssii",
    $search_term,
    $search_term,
    $per_page,
    $offset
);

$statement->execute();
$result = $statement->get_result();

?>

<h2 class="display-5 my-3">City Search</h2>

<p class="lead mb-4">
    Search for cities by name or province.
</p>

<!-- Search form -->
<form action="cities.php" method="GET" class="mb-4">

    <label for="keyword" class="form-label">
        Search Cities
    </label>

    <input
        type="search"
        name="keyword"
        id="keyword"
        class="form-control"
        value="<?= htmlspecialchars($keyword) ?>"
        placeholder="Enter a city or province"
    >

    <button type="submit" class="btn btn-primary mt-2">
        Search
    </button>

    <a href="cities.php" class="btn btn-outline-secondary mt-2">
        Clear Search
    </a>

</form>

<!-- Search results -->
<?php if ($result->num_rows > 0): ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>City ID</th>
                <th>City Name</th>
                <th>Province</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars((string) $row['cid']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['city_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['province']) ?>
                    </td>

                    <td>
                        <a href="city.php?cid=<?= urlencode($row['cid']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No cities match your search.</p>

<?php endif; ?>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>

    <nav aria-label="City pagination">
        <ul class="pagination">

            <!-- Previous -->
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a
                        class="page-link"
                        href="cities.php?keyword=<?= urlencode($keyword) ?>&page=<?= $current_page - 1 ?>"
                    >
                        Previous
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>

                <li class="page-item <?= $page == $current_page ? 'active' : '' ?>">
                    <a
                        class="page-link"
                        href="cities.php?keyword=<?= urlencode($keyword) ?>&page=<?= $page ?>"
                    >
                        <?= $page ?>
                    </a>
                </li>

            <?php endfor; ?>

            <!-- Next -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a
                        class="page-link"
                        href="cities.php?keyword=<?= urlencode($keyword) ?>&page=<?= $current_page + 1 ?>"
                    >
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
