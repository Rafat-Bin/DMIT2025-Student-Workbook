
<?php

$title = 'Add a City';
$introduction = 'Help us grow our Canadian Cities database by adding a city of your choice.';

// Load the existing Lesson 17 code.
require_once __DIR__ . '/../data/connect.php';
require_once __DIR__ . '/../private/prepared.php';
require_once __DIR__ . '/../private/functions.php';

$conn = db_connect();

// Form settings.
$form_action = 'add.php';
$submit_label = 'Save New City';

// Province and territory options.
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

// Default form values.
$city_name = '';
$province = '';
$population = '';
$is_capital = '';
$trivia = '';

$errors = [];

// Process the submitted form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve submitted values.
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
        $errors[] = 'Enter a whole-number population from 0 to 2147483647.';
    }

    // Validate capital selection.
    if ($is_capital !== '0' && $is_capital !== '1') {
        $errors[] = 'Select Yes or No for capital city.';
    }

    // Validate trivia.
    if (strlen($trivia) > 255) {
        $errors[] = 'Trivia must be 255 characters or fewer.';
    }

    // Insert the city if there are no errors.
    if (empty($errors)) {

        $sql = 'INSERT INTO cities
                (city_name, province, population, is_capital, trivia)
                VALUES (?, ?, ?, ?, ?)';

        execute_prepared_statement(
            $sql,
            [
                $city_name,
                $province,
                (int) $population,
                (int) $is_capital,
                $trivia
            ],
            'ssiis'
        );

        // Return to the city list.
        header('Location: index.php');
        exit();
    }
}

// Display the page.
include 'includes/header.php';

?>

<h2 class="fw-light mb-3">City Information</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <p class="fw-bold mb-2">
            Please correct these errors:
        </p>

        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php

include 'includes/form.php';
include 'includes/footer.php';

?>
