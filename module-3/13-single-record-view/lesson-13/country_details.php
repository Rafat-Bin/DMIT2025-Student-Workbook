<?php
$title = 'Country Statistics';
include 'includes/header.php';

$rank = filter_var($_GET['rank'] ?? null, FILTER_VALIDATE_INT);
?>

<h2>Country Statistics</h2>

<?php if (!$rank || $rank < 1): ?>
    <p>Invalid country.</p>
<?php else: ?>
    <?php
    $query = 'SELECT * FROM happiness_index WHERE `rank` = ?';

    $statement = $conn->prepare($query);
    $statement->bind_param('i', $rank);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    $statement->close();
    ?>

    <?php if ($row): ?>
        <h3><?= htmlspecialchars($row['country']) ?></h3>
        <p>Rank: <?= htmlspecialchars((string) $row['rank']) ?></p>
        <p>Life Expectancy: <?= htmlspecialchars((string) $row['life_expectancy']) ?></p>
        <p>Happy Planet Index: <?= htmlspecialchars((string) $row['hpi']) ?></p>
    <?php else: ?>
        <p>Country not found.</p>
    <?php endif; ?>

<?php endif; ?>

<a href="index-country.php">Back to Countries</a>

<?php include 'includes/footer.php'; ?>