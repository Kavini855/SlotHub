<?php

use App\Models\Service;

test('service formatted price accessor returns formatted price', function () {

    $service = new Service([
        'price' => 2500,
    ]);

    expect($service->formatted_price)
        ->toBe('LKR 2,500.00');
});

test('service name mutator removes unnecessary spaces', function () {

    $service = new Service();

    $service->name = '   Test Service Name   ';

    expect($service->name)
        ->toBe('Test Service Name');
});


//no need database here These are unit tests for the model behavior itself. We don't need to create providers, categories, or database records.