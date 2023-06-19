<?php declare(strict_types=1);

use Marski\Rules\RouterRules;
use MArski\Views\{View};

$viewData = View::data();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $viewData['title'] ?> Petstore API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= $viewData['url'] ?>">
    <meta name="description" content="An example of using the PetStore API. Written in the php programming language.">
    <meta name="author" content="marski.pl | Mariusz Kępski">
    <meta name="copyright" content="Copyright owner">
    <link rel="icon" type="image/png" href="<?= $viewData['url'] ?>public/image/favicon.png"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $viewData['url'] ?>public/style/style.css" type="text/css" media="all">
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse">
        <div class="navbar-nav">
            <a class="nav-item nav-link <?= ($viewData['action'] === 'mainPets' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'main-pets') ?>">Main</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'addNew' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'add-new-pets') ?>">Add a new pet to the store</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'updateExisting' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'update-existing-pet') ?>">Update an existing pet</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'findsPetsStatus' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'finds-pets-by-status') ?>">Finds Pets by status</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'findPetID' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'find-pet-by-id') ?>">Find pet by ID</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'updatesPetStoreFormData' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'updates-pet-store-form-data') ?>">Updates a pet in the store with form data</a>
            <a class="nav-item nav-link <?= ($viewData['action'] === 'deletesPet' ? 'active' : '') ?>" href="<?= RouterRules::viewLink('pet', 'deletes-pet') ?>">Deletes a pet</a>
        </div>
    </div>
</nav>
