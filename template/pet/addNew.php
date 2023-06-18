<?php declare(strict_types=1);

use Marski\Middlewares\XCsrf;
use Marski\Rules\RouterRules;
use MArski\Views\{View};

XCsrf::setNewToken('ADD_NEW');

$viewValues = View::values();
$request = $viewValues['request'];
$errors = $viewValues['errors'];
?>

<section class="container">
    <div class="row mt-3">
        <h1 class="col-sm-12 col-center">Add a new pet to the store</h1>
    </div>
    <div class="row mt-3">
        <form class="col-sm-7" action="<?= RouterRules::viewLink('pet', 'add-new-pets') ?>" method="post" name="addNew"
              enctype="multipart/form-data">
            <div class="form-group mt-1">
                <label for="pet_category">Category</label>
                <input type="text" class="form-control" id="pet_category" name="pet_category"
                       value="<?= $request['pet_category'] ?? '' ?>" required>
                <small class="form-text <?= $errors['message']['pet_category'] ?? 'text-muted' ?>">The category can only
                    contain alphabetic characters and numbers.</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_name">Name</label>
                <input type="text" class="form-control" id="pet_name" name="pet_name"
                       value="<?= $request['pet_name'] ?? '' ?>" required>
                <small class=" form-text <?= $errors['message']['pet_name'] ?? 'text-muted' ?>">The name of the animal
                    can only consist of alphabetic characters and numbers.</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_tag">Tag</label>
                <input type="text" class="form-control" id="pet_tag" name="pet_tag"
                       value="<?= $request['pet_tag'] ?? '' ?>" required>
                <small class=" form-text <?= $errors['message']['pet_tag'] ?? 'text-muted' ?>">The tag can only contain
                    alphabetic characters and numbers. Comma separated.</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_photo_url">URL to photo</label>
                <textarea class="form-control" id="pet_photo_url" name="pet_photo_url"
                          rows="3"><?= $request['pet_photo_url'] ?? '' ?></textarea>
                <small class="form-text <?= $errors['message']['pet_photo_url'] ?? 'text-muted' ?>">URL to the images.
                    Comma separated. Finished: .bmp, .jpg, .jpeg, .png</small>
            </div>

            <div class="form-group mt-2">
                <label for="pet_photo_file">A photo file of your pet</label>
                <input type="file" accept="image/*" class="form-control" id="pet_photo_file" name="pet_image">
                <small class="form-text <?= $errors['message']['pet_photo_file'] ?? 'text-muted' ?>">Select an image
                    file.</small>
            </div>

            <input type="hidden" name="csrf" value="<?= XCsrf::getToken('ADD_NEW') ?>">
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