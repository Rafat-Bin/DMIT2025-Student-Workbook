<?php
$title = 'Cities';
include 'includes/header.php';

// Count all cities.
$count_sql = "SELECT COUNT(*) AS total FROM cities";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];

// Calculate the number of pages.
$per_page = 10;
$total_pages = ceil($total_records / $per_page);

// Read and validate the current page.
$current_page = filter_var(
    $_GET['page'] ?? 1,
    FILTER_VALIDATE_INT
);

if (!$current_page || $current_page < 1 || $current_page > $total_pages) {
    $current_page = 1;
}

// Calculate how many records to skip.
$offset = ($current_page - 1) * $per_page;

// Retrieve cities for the current page.
$sql = "SELECT cid, city_name
        FROM cities
        ORDER BY city_name
        LIMIT ? OFFSET ?";

$statement = $conn->prepare($sql);
$statement->bind_param("ii", $per_page, $offset);
$statement->execute();

$result = $statement->get_result();
?>

<h2 class="display-5 my-3">Cities</h2>

<p class="lead mb-4">
    Explore cities in our database.
    Select View Details to learn more about a city.
</p>

<?php if ($result->num_rows > 0): ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>City ID</th>
                <th>City Name</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $row['cid']) ?></td>

                    <td><?= htmlspecialchars($row['city_name']) ?></td>

                    <td>
                        <a class="link-success"
                           href="city.php?cid=<?= urlencode($row['cid']) ?>">
                            View Details
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No cities found.</p>

<?php endif; ?>

<?php if ($total_pages > 1): ?>

    <nav aria-label="City pagination">
        <ul class="pagination">

            <!-- Previous -->
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="cities.php?page=<?= $current_page - 1 ?>">
                        Previous
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <li class="page-item <?= $page == $current_page ? 'active' : '' ?>">
                    <a class="page-link"
                       href="cities.php?page=<?= $page ?>">
                        <?= $page ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link"
                       href="cities.php?page=<?= $current_page + 1 ?>">
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