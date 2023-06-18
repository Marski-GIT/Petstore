<?php declare(strict_types=1);

use Marski\Middlewares\XCsrf;
use Marski\Rules\RouterRules;
use MArski\Views\{View};

XCsrf::setNewToken('FINDS_BY_STATUS');

$viewValues = View::values();
$request = $viewValues['request'];
?>

<section class="container">
    <div class="row mt-3">
        <h1 class="col-sm-12 col-center">Finds Pets by status</h1>
    </div>
    <div class="row mt-3">
        <form class="col-sm-7" action="<?= RouterRules::viewLink('pet', 'finds-pets-by-status') ?>" method="post" name="findsPetsStatus">

            <div class="form-group mt-2">
                <label for="pet_status">Status</label>
                <select class="form-control" name="pet_status" id="pet_status">
                    <option <?= ($request['pet_status'] ?? '') === 'available' ? 'selected' : '' ?> value="available">Available</option>
                    <option <?= ($request['pet_status'] ?? '') === 'pending' ? 'selected' : '' ?> value="pending">Pending</option>
                    <option <?= ($request['pet_status'] ?? '') === 'sold' ? 'selected' : '' ?> value="sold">Sold</option>
                </select>
                <small class="form-text text-muted">Pet status.</small>
            </div>

            <input type="hidden" name="csrf" value="<?= XCsrf::getToken('FINDS_BY_STATUS') ?>">
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
                <?php foreach ($viewValues['list'] as $key => $row): ?>
                    <tr>
                        <th scope="row"><?= $key ?></th>
                        <td><?= $row['name'] ?? '' ?></td>
                        <td><?= $row['category']['name'] ?? '' ?></td>
                        <td><?= ucfirst($row['status'] ?? '') ?></td>
                        <td>
                            <?php foreach (explode(', ', $row['photoUrls'][0] ?? '') as $link): ?>
                                <?php if (filter_var($link, FILTER_VALIDATE_URL)): ?>
                                    <span> <a href="<?= $link ?>">Link</a></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($row['tags'] as $tag): ?>
                                <span><?= $tag['name'] ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td><?= $row['id'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</section>