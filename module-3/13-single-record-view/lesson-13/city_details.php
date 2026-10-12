<?php
$title = 'City Details';
include 'includes/header.php';

$cid = filter_var($_GET['cid'] ?? null, FILTER_VALIDATE_INT);
?>

<h2 class="display-5 my-3">City Details</h2>

<?php if (!$cid || $cid < 1): ?>

    <p class="lead">Invalid city ID.</p>

<?php else: ?>

    <?php
    $query = 'SELECT * FROM cities WHERE cid = ?';

    $statement = $conn->prepare($query);
    $statement->bind_param('i', $cid);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    $statement->close();
    ?>

    <?php if ($row): ?>

        <div class="card">
            <div class="card-header text-bg-dark">
                <h3 class="fs-5 mb-0">
                    <?= htmlspecialchars($row['city_name']) ?>
                </h3>
            </div>

            <div class="card-body">
                <p>
                    <strong>City ID:</strong>
                    <?= htmlspecialchars((string) $row['cid']) ?>
                </p>

                <p>
                    <strong>Province:</strong>
                    <?= htmlspecialchars($row['province']) ?>
                </p>

                <p>
                    <strong>Population:</strong>
                    <?= number_format($row['population']) ?>
                </p>

                <p>
                    <strong>Provincial Capital:</strong>
                    <?= $row['is_capital'] ? 'Yes' : 'No' ?>
                </p>

                <p>
                    <strong>Trivia:</strong>
                    <?= htmlspecialchars($row['trivia'] ?? '') ?>
                </p>
            </div>
        </div>

    <?php else: ?>

        <p class="lead">City not found.</p>

    <?php endif; ?>

<?php endif; ?>

<a href="cities.php" class="btn btn-dark mt-4">
    Back to Cities
</a>

<?php include 'includes/footer.php'; ?>