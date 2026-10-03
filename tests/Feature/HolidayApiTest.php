<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('customer can check a public holiday', function () {
    Http::fake([
        '*' => Http::response([
            [
                'date' => '2026-12-25',
                'title' => [
                    'en' => 'Christmas Day',
                    'original' => 'Christmas Day',
                ],
                'description' => [
                    'en' => 'Christian celebration of the birth of Jesus Christ.',
                ],
                'countryCode' => 'LK',
            ],
        ], 200),
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this
        ->actingAs($customer)
        ->getJson(route('customer.holidays.check', [
            'date' => '2026-12-25',
        ]));

    $response
        ->assertOk()
        ->assertJson([
            'available' => true,
            'holiday' => [
                'is_holiday' => true,
                'name' => 'Christmas Day',
            ],
        ]);
});


test('customer can check a date that is not a public holiday', function () {
    Http::fake([
        '*' => Http::response([
            [
                'date' => '2026-12-25',
                'title' => [
                    'en' => 'Christmas Day',
                ],
                'description' => [
                    'en' => 'Christmas holiday.',
                ],
            ],
        ], 200),
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this
        ->actingAs($customer)
        ->getJson(route('customer.holidays.check', [
            'date' => '2026-10-10',
        ]));

    $response
        ->assertOk()
        ->assertJson([
            'available' => true,
            'holiday' => [
                'is_holiday' => false,
                'name' => null,
                'description' => null,
            ],
        ]);
});


test('holiday api failure is handled safely', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $response = $this
        ->actingAs($customer)
        ->getJson(route('customer.holidays.check', [
            'date' => '2026-12-25',
        ]));

    $response
        ->assertStatus(503)
        ->assertJson([
            'available' => false,
            'message' => 'Holiday information is temporarily unavailable.',
        ]);
});