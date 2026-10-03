<?php

use App\Livewire\Customer\ServiceBrowser;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createProviderForServiceBrowser(): User
{
    return User::factory()->create([
        'role' => 'provider',
    ]);
}

function createCategoryForServiceBrowser(string $name): Category
{
    return Category::create([
        'name' => $name,
        'description' => $name . ' services',
        'is_active' => true,
    ]);
}

function createServiceForBrowser(
    User $provider,
    Category $category,
    string $name,
    bool $active = true
): Service {
    return Service::create([
        'provider_id' => $provider->id,
        'category_id' => $category->id,
        'name' => $name,
        'description' => $name . ' description',
        'duration_minutes' => 60,
        'price' => 2500,
        'is_active' => $active,
    ]);
}

test('service browser component renders successfully', function () {
    Livewire::test(ServiceBrowser::class)
        ->assertStatus(200)
        ->assertSee('Find a Service');
});

test('customer can search active services dynamically', function () {
    $provider = createProviderForServiceBrowser();
    $category = createCategoryForServiceBrowser('Beauty');

    createServiceForBrowser($provider, $category, 'Hair Styling');
    createServiceForBrowser($provider, $category, 'Makeup Session');

    Livewire::test(ServiceBrowser::class)
        ->set('search', 'Hair')
        ->assertSee('Hair Styling')
        ->assertDontSee('Makeup Session');
});

test('customer can filter services by category dynamically', function () {
    $provider = createProviderForServiceBrowser();

    $beauty = createCategoryForServiceBrowser('Beauty');
    $fitness = createCategoryForServiceBrowser('Fitness');

    createServiceForBrowser($provider, $beauty, 'Hair Styling');
    createServiceForBrowser($provider, $fitness, 'Personal Training');

    Livewire::test(ServiceBrowser::class)
        ->set('category', (string) $beauty->id)
        ->assertSee('Hair Styling')
        ->assertDontSee('Personal Training');
});

test('inactive services are not displayed', function () {
    $provider = createProviderForServiceBrowser();
    $category = createCategoryForServiceBrowser('Beauty');

    createServiceForBrowser(
        $provider,
        $category,
        'Active Hair Service'
    );

    createServiceForBrowser(
        $provider,
        $category,
        'Hidden Hair Service',
        false
    );

    Livewire::test(ServiceBrowser::class)
        ->assertSee('Active Hair Service')
        ->assertDontSee('Hidden Hair Service');
});

test('customer can clear service filters', function () {
    $provider = createProviderForServiceBrowser();
    $category = createCategoryForServiceBrowser('Beauty');

    createServiceForBrowser($provider, $category, 'Hair Styling');
    createServiceForBrowser($provider, $category, 'Makeup Session');

    Livewire::test(ServiceBrowser::class)
        ->set('search', 'Hair')
        ->assertSee('Hair Styling')
        ->assertDontSee('Makeup Session')
        ->call('clearFilters')
        ->assertSet('search', '')
        ->assertSet('category', '')
        ->assertSee('Hair Styling')
        ->assertSee('Makeup Session');
});

test('service browser paginates services', function () {
    $provider = createProviderForServiceBrowser();
    $category = createCategoryForServiceBrowser('Beauty');

    foreach (range(1, 7) as $number) {
        createServiceForBrowser(
            $provider,
            $category,
            'Service ' . $number
        );
    }

    Livewire::test(ServiceBrowser::class)
        ->assertViewHas('services', function ($services) {
            return $services->perPage() === 6
                && $services->total() === 7;
        });
});