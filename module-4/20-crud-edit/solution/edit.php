
<?php
$title = 'Edit a City';
$introduction = 'Select a city to update its information.';

// Include required files.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

// Provinces and territories.
$provinces = [
    'AB' => 'Alberta',
    'BC' => 'British Columbia',
    'MB' => 'Manitoba',
    'NB' => 'New Brunswick',
    'NL' => 'Newfoundland and Labrador',
    'NS' => 'Nova Scotia',
    'NT' => 'Northwest Territories',
    'NU' => 'Nunavut',
    'ON' => 'Ontario',
    'PE' => 'Prince Edward Island',
    'QC' => 'Quebec',
    'SK' => 'Saskatchewan',
    'YT' => 'Yukon'
];

$submit_label = 'Save Changes';
$errors = [];
$city = null;

// Get city ID from URL.
$cid = $_GET['cid'] ?? '';

if (!ctype_digit((string) $cid) || (int) $cid < 1) {
    $cid = 0;
} else {
    $cid = (int) $cid;
}

// Retrieve selected city.
if ($cid > 0) {
    $sql = 'SELECT * FROM cities WHERE cid = ?';

    $result = execute_prepared_statement(
        $sql,
        [$cid],
        'i'
    );

    $city = $result->fetch_assoc();
}

// Fill form with existing information.
if ($city) {
    $city_name = $city['city_name'];
    $province = $city['province'];
    $population = (string) $city['population'];
    $is_capital = (string) $city['is_capital'];
    $trivia = $city['trivia'] ?? '';
}

// Keep city ID when submitting form.
$form_action = 'edit.php?cid=' . $cid;

// Process form submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $city) {

    $city_name = trim($_POST['city_name'] ?? '');
    $province = $_POST['province'] ?? '';
    $population = trim($_POST['population'] ?? '');
    $is_capital = $_POST['is_capital'] ?? '';
    $trivia = trim($_POST['trivia'] ?? '');

    // Validate city name.
    if ($city_name === '' || strlen($city_name) > 36) {
        $errors[] = 'Enter a city name (36 characters or fewer).';
    }

    // Validate province.
    if (!array_key_exists($province, $provinces)) {
        $errors[] = 'Choose a valid province or territory.';
    }

    // Validate population.
    if (
        $population === '' ||
        !ctype_digit($population) ||
        (float) $population > 2147483647
    ) {
        $errors[] = 'Enter a valid population.';
    }

    // Validate capital city.
    if ($is_capital !== '0' && $is_capital !== '1') {
        $errors[] = 'Select Yes or No for capital city.';
    }

    // Validate trivia.
    if (strlen($trivia) > 255) {
        $errors[] = 'Trivia must be 255 characters or fewer.';
    }

    // Update database if no errors.
    if (empty($errors)) {

        $sql = 'UPDATE cities
                SET city_name = ?,
                    province = ?,
                    population = ?,
                    is_capital = ?,
                    trivia = ?
                WHERE cid = ?';

        execute_prepared_statement(
            $sql,
            [
                $city_name,
                $province,
                (int) $population,
                (int) $is_capital,
                $trivia,
                $cid
            ],
            'ssiisi'
        );

        header('Location: edit.php?updated=1');
        exit();
    }
}

include 'includes/header.php';
?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
        City updated successfully.
    </div>
<?php endif; ?>

<?php if ($city): ?>

    <h2>Edit <?= htmlspecialchars($city['city_name']) ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php include 'includes/form.php'; ?>

<?php elseif ($cid > 0): ?>

    <div class="alert alert-warning">
        The selected city could not be found.
    </div>

    <a href="edit.php" class="btn btn-secondary">
        Back to City List
    </a>

<?php else: ?>

    <h2>Select a City to Edit</h2>

    <?php $cities = get_all_cities(); ?>

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
                <?php foreach ($cities as $item): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['city_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item['province']) ?>
                        </td>

                        <td>
                            <a
                                href="edit.php?cid=<?= (int) $item['cid'] ?>"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
