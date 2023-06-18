<?php declare(strict_types=1);

use Marski\Middlewares\XCsrf;
use Marski\Rules\RouterRules;
use MArski\Views\{View};

XCsrf::setNewToken('DELETE_PET');

$viewValues = View::values();
$request = $viewValues['request'];
$errors = $viewValues['errors'];
?>

<section class="container">
    <div class="row mt-3">
        <h1 class="col-sm-12 col-center">Deletes a pet</h1>
    </div>
    <div class="row mt-3">
        <form class="col-sm-7" action="<?= RouterRules::viewLink('pet', 'deletes-pet') ?>" method="post" name="deletePet">

            <div class="form-group mt-2">
                <div class="form-group mt-2">
                    <label for="pet_id">Pet ID</label>
                    <input type="text" class="form-control" id="pet_id" name="pet_id" value="<?= $request['pet_id'] ?? '' ?>" required>
                    <small class="form-text <?= $errors['message']['pet_id'] ?? 'text-muted' ?>">The pet ID must be a positive number.</small>
                </div>
            </div>

            <input type="hidden" name="csrf" value="<?= XCsrf::getToken('DELETE_PET') ?>">
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