<?php

namespace App\Livewire\Customer;

use App\Models\Category;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceBrowser extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category']);
        $this->resetPage();
    }

    public function render()
    {
        $services = Service::query()
            ->with(['category', 'provider'])
            ->active()
            ->search($this->search)
            ->category($this->category ? (int) $this->category : null)
            ->latest()
            ->paginate(6);

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('livewire.customer.service-browser', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}