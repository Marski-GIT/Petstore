<?php declare(strict_types=1);

use Marski\Middlewares\XCsrf;
use Marski\Rules\RouterRules;
use MArski\Views\{View};

XCsrf::setNewToken('FIND_PET_ID');

$viewValues = View::values();
$request = $viewValues['request'];
$errors = $viewValues['errors'];
?>

<section class="container">
    <div class="row mt-3">
        <h1 class="col-sm-12 col-center">Find pet by ID</h1>
    </div>
    <div class="row mt-3">
        <form class="col-sm-7" action="<?= RouterRules::viewLink('pet', 'find-pet-by-id') ?>" method="post" name="findPetID">

            <div class="form-group mt-2">
                <div class="form-group mt-2">
                    <label for="pet_id">Pet ID</label>
                    <input type="text" class="form-control" id="pet_id" name="pet_id" value="<?= $request['pet_id'] ?? '' ?>" required>
                    <small class="form-text <?= $errors['message']['pet_id'] ?? 'text-muted' ?>">The pet ID must be a positive number.</small>
                </div>
            </div>

            <input type="hidden" name="csrf" value="<?= XCsrf::getToken('FIND_PET_ID') ?>">
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

    <div class="row mt-3">
        <?php if (count($viewValues['list']) > 0): ?>
            <table class="table table-hover mb-3">
                <thead class="table-thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Status</th>
                    <th scope="col">Photos</th>
                    <th scope="col">Tags</th>
                    <th scope="col">ID</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"><?= 1 ?></th>
                    <td><?= $viewValues['list']['name'] ?? '' ?></td>
                    <td><?= $viewValues['list']['category']['name'] ?? '' ?></td>
                    <td><?= ucfirst($viewValues['list']['status'] ?? '') ?></td>
                    <td>
                        <?php foreach (explode(',', $viewValues['list']['photoUrls'][0] ?? '') as $link): ?>
                            <?php if (filter_var($link, FILTER_VALIDATE_URL)): ?>
                                <span> <a href="<?= $link ?>">Link</a></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($viewValues['list']['tags'] as $tag): ?>
                            <span><?= $tag['name'] ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td><?= $viewValues['list']['id'] ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

</section>