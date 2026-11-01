
<form action="<?= htmlspecialchars($form_action) ?>" method="POST">

    <div class="mb-3">
        <label for="city_name" class="form-label">City Name</label>
        <input
            type="text"
            id="city_name"
            name="city_name"
            class="form-control"
            maxlength="36"
            value="<?= htmlspecialchars($city_name ?? '') ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="province" class="form-label">Province</label>
        <select id="province" name="province" class="form-select" required>
            <option value="">Select Province</option>

            <?php foreach ($provinces as $code => $name): ?>
                <option
                    value="<?= htmlspecialchars($code) ?>"
                    <?= ($province ?? '') === $code ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="population" class="form-label">Population</label>
        <input
            type="number"
            id="population"
            name="population"
            class="form-control"
            min="0"
            max="2147483647"
            value="<?= htmlspecialchars($population ?? '') ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="is_capital" class="form-label">Capital City?</label>
        <select id="is_capital" name="is_capital" class="form-select" required>
            <option value="">Select an option</option>
            <option value="1" <?= ($is_capital ?? '') === '1' ? 'selected' : '' ?>>
                Yes
            </option>
            <option value="0" <?= ($is_capital ?? '') === '0' ? 'selected' : '' ?>>
                No
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label for="trivia" class="form-label">Trivia</label>
        <textarea
            id="trivia"
            name="trivia"
            class="form-control"
            maxlength="255"
            rows="3"
        ><?= htmlspecialchars($trivia ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">
        <?= htmlspecialchars($submit_label) ?>
    </button>

    <a href="edit.php" class="btn btn-secondary">
        Cancel
    </a>
</form>
