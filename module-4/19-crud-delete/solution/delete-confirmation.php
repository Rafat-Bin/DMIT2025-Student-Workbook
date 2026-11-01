
<?php
$title = 'Confirm Deletion';
$introduction = 'Review the city below before deleting it.';

// Include database files.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';

$conn = db_connect();

// Get city ID from GET or POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = $_POST['cid'] ?? '';
} else {
    $cid = $_GET['cid'] ?? '';
}

// Validate city ID.
if (!ctype_digit((string) $cid) || (int) $cid < 1) {
    $cid = 0;
}

// Retrieve the selected city.
$city = null;

if ((int) $cid > 0) {
    $sql = 'SELECT * FROM cities WHERE cid = ?';

    $result = execute_prepared_statement(
        $sql,
        [(int) $cid],
        'i'
    );

    $city = $result->fetch_assoc();
}

// Delete only after confirmation.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $city) {

    $sql = 'DELETE FROM cities WHERE cid = ?';

    execute_prepared_statement(
        $sql,
        [(int) $cid],
        'i'
    );

    // Redirect after deletion.
    header('Location: delete.php?deleted=1');
    exit();
}

include 'includes/header.php';
?>

<?php if (!$city): ?>

    <div class="alert alert-warning" role="alert">
        The selected city could not be found.
    </div>

    <a href="delete.php" class="btn btn-secondary">
        Back to Cities
    </a>

<?php else: ?>

    <div class="alert alert-warning" role="alert">
        Are you sure you want to permanently delete

        <strong>
            <?= htmlspecialchars($city['city_name']) ?>,
            <?= htmlspecialchars($city['province']) ?>
        </strong>?
    </div>

    <div class="alert alert-danger" role="alert">
        Warning: This action cannot be undone.
    </div>

    <form method="POST">

        <input
            type="hidden"
            name="cid"
            value="<?= (int) $city['cid'] ?>"
        >

        <button type="submit" class="btn btn-danger">
            Permanently Delete City
        </button>

        <a href="delete.php" class="btn btn-secondary">
            Cancel
        </a>

    </form>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
