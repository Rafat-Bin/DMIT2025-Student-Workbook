<?php
$title = 'Home';

include 'includes/header.php';
$sql = 'SELECT `rank`, `country` FROM happiness_index';
$result = $conn->query($sql);
?>

<h2 class="display-5 my-3">Welcome to the Happy Planet Index</h2>
<p class="lead mb-5">
    Explore the countries in the Happy Planet Index.
    Select View Stats to see more information.
</p>

<?php if ($result->num_rows > 0): ?>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>HPI Rank</th>
                <th>Country Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $row['rank']) ?></td>
                    <td><?= htmlspecialchars($row['country']) ?></td>
                    <td>
                        <a class="link-success"
                           href="country.php?rank=<?= urlencode($row['rank']) ?>">
                            View Stats
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>No countries found.</p>
<?php endif; ?>


<?php include 'includes/footer.php'; ?>