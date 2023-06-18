<?php declare(strict_types=1);

use Marski\Middlewares\XCsrf;
use Marski\Rules\RouterRules;
use MArski\Views\{View};

XCsrf::setNewToken('UPDATE_PET_STORE_FORM_DATA');

$viewValues = View::values();
$request = $viewValues['request'];
$errors = $viewValues['errors'];
?>

<section class="container">

    <div class="row mt-3">
        <h1 class="col-center">Updates a pet in the store with form data</h1>
    </div>

    <div class="row mt-3">
        <form class="col-sm-7" action="<?= RouterRules::viewLink('pet', 'updates-pet-store-form-data') ?>" method="post" name="updatesPetStoreFormData">

            <div class="form-group mt-2">
                <div class="form-group mt-2">
                    <label for="pet_id">Pet ID</label>
                    <input type="text" class="form-control" id="pet_id" name="pet_id" value="<?= $request['pet_id'] ?? '' ?>" required>
                    <small class="form-text <?= $errors['message']['pet_id'] ?? 'text-muted' ?>">The pet ID must be a positive number.</small>
                </div>
            </div>

            <div class="form-group mt-1">
                <label for="pet_category">Category</label>
                <input type="text" class="form-control" id="pet_category" name="pet_category" value="<?= $request['pet_category'] ?? '' ?>" required>
                <small class="form-text <?= $errors['message']['pet_category'] ?? 'text-muted' ?>">The category can only contain alphabetic characters and numbers. At least two characters.</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_name">Name</label>
                <input type="text" class="form-control" id="pet_name" name="pet_name" value="<?= $request['pet_name'] ?? '' ?>" required>
                <small class="form-text <?= $errors['message']['pet_name'] ?? 'text-muted' ?>">The name of the animal can only consist of alphabetic characters and numbers.</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_status">Status</label>
                <select class="form-control" name="pet_status" id="pet_status">
                    <option <?= ($request['pet_status'] ?? '') === 'available' ? 'selected' : '' ?> value="available">Available</option>
                    <option <?= ($request['pet_status'] ?? '') === 'pending' ? 'selected' : '' ?> value="pending">Pending</option>
                    <option <?= ($request['pet_status'] ?? '') === 'sold' ? 'selected' : '' ?> value="sold">Sold</option>
                </select>
                <small class="form-text text-muted">Pet status.</small>
            </div>

            <input type="hidden" name="csrf" value="<?= XCsrf::getToken('UPDATE_PET_STORE_FORM_DATA') ?>">
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>

        <aside class="col-sm-5">
            <?php if (count($viewValues['message']) > 0): ?>
                <?php foreach ($viewValues['message'] as $status => $message): ?>

                    <ol class="alert alert-<?= $status ?> mt-4 ol-wrap" role="<?= $status ?>">
                        <?php foreach ($viewValues['message'][$status] as $text): ?>
                            <li><?= $text ?></li>
                        <?php endforeach; ?>
                    </ol>

                <?php endforeach; ?>
            <?php endif; ?>
        </aside>
    </div>

</section>