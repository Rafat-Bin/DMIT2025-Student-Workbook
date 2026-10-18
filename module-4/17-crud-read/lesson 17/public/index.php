<?php
$title = 'Welcome!';
$introduction = 'Welcome to the Canadian Cities Online Database. Browse the cities currently stored in our database.';

include 'includes/header.php';
?>

<h2 class="fw-light mb-3">Current Cities in Our Database</h2>

<?php
generate_table();

include 'includes/footer.php';
?>
