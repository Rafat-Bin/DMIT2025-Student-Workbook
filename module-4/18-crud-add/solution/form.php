
<form action="<?= htmlspecialchars($form_action) ?>" method="POST">

    <p class="text-muted">
        Fields marked with * are required.
    </p>

    <!-- City Name -->
    <div class="mb-3">
        <label for="city_name" class="form-label">
            City Name *
        </label>

        <input
            type="text"
            class="form-control"
            id="city_name"
            name="city_name"
            maxlength="36"
            value="<?= htmlspecialchars($city_name) ?>"
        >
    </div>

    <!-- Province or Territory -->
    <div class="mb-3">
        <label for="province" class="form-label">
            Province or Territory *
        </label>

        <select
            class="form-select"
            id="province"
            name="province"
        >
            <option value="">
                Choose a province or territory
            </option>

            <?php foreach ($provinces as $code => $name): ?>
                <option
                    value="<?= htmlspecialchars($code) ?>"
                    <?= $province === $code ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Population -->
    <div class="mb-3">
        <label for="population" class="form-label">
            Population *
        </label>

        <input
            type="number"
            class="form-control"
            id="population"
            name="population"
            min="0"
            step="1"
            value="<?= htmlspecialchars($population) ?>"
        >
    </div>

    <!-- Capital City -->
    <fieldset class="mb-3">
        <legend class="fs-6">
            Is this a provincial or territorial capital? *
        </legend>

        <div class="form-check">
            <input
                type="radio"
                class="form-check-input"
                id="capital_yes"
                name="is_capital"
                value="1"
                <?= $is_capital === '1' ? 'checked' : '' ?>
            >

            <label class="form-check-label" for="capital_yes">
                Yes
            </label>
        </div>

        <div class="form-check">
            <input
                type="radio"
                class="form-check-input"
                id="capital_no"
                name="is_capital"
                value="0"
                <?= $is_capital === '0' ? 'checked' : '' ?>
            >

            <label class="form-check-label" for="capital_no">
                No
            </label>
        </div>
    </fieldset>

    <!-- Trivia -->
    <div class="mb-3">
        <label for="trivia" class="form-label">
            Trivia (Optional)
        </label>

        <textarea
            class="form-control"
            id="trivia"
            name="trivia"
            rows="3"
            maxlength="255"
        ><?= htmlspecialchars($trivia) ?></textarea>

        <div class="form-text">
            Share an interesting fact about this city,
            or leave this field blank.
        </div>
    </div>

    <!-- Form Buttons -->
    <button type="submit" class="btn btn-success">
        <?= htmlspecialchars($submit_label) ?>
    </button>

    <a href="index.php" class="btn btn-outline-secondary">
        Cancel
    </a>

</form>
