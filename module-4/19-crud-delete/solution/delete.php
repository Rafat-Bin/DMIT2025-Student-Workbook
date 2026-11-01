
<?php
$title = 'Delete a City';
$introduction = 'Choose a city from the list below to delete it.';

include 'includes/header.php';

$cities = get_all_cities();
?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success" role="alert">
        City deleted successfully.
    </div>
<?php endif; ?>

<h2 class="fw-light mb-3">Select a City to Delete</h2>

<?php if (empty($cities)): ?>

    <p>No cities were found.</p>

<?php else: ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>City</th>
                <th>Province</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($city['city_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($city['province']) ?>
                    </td>

                    <td>
                        <a href="delete-confirmation.php?cid=<?= (int) $city['cid'] ?>"
                           class="btn btn-danger btn-sm">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
