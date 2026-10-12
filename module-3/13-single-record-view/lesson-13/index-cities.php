<?php
$title = 'Cities';
include 'includes/header.php';

$sql = 'SELECT cid, city_name FROM cities';
$result = $conn->query($sql);
?>

<h2 class="display-5 my-3">Explore Cities</h2>
<p class="lead mb-5">
    Explore the cities in our database.
    Select View Details to see more information.
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

<?php include 'includes/footer.php'; ?>