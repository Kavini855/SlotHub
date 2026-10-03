<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display active services for customers.
     */
    public function index(Request $request)
    {
        $services = Service::query()
            ->with(['category', 'provider'])
            ->active() // inactive provider services aren't shown
            ->search($request->input('search'))
            ->category($request->integer('category'))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('customer.services.index', compact('services', 'categories'));
    }

    /**
     * Display a single active service.
     */
    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        $service->load(['category', 'provider']);

        return view('customer.services.show', compact('service'));
    }
}

