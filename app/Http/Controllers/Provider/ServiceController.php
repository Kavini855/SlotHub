<?php

namespace App\Http\Controllers\Provider;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = Service::query()
            ->with('category')
            ->where('provider_id', $request->user()->id)
            ->search($request->input('search'))
            ->category($request->integer('category'))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('provider.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('provider.services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
        ]);

        $request->user()->services()->create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request, Service $service)
    {
        // Security: provider can only edit their own services

        abort_if($service->provider_id !== $request->user()->id, 403);

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('provider.services.edit', compact('service', 'categories'));
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Service $service)
    {
        // Prevent providers from updating another provider's service
        abort_if($service->provider_id !== $request->user()->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Service $service)
    {
        // Provider can only delete their own service
        abort_if($service->provider_id !== $request->user()->id, 403);

        $service->delete();

        return redirect()
            ->route('provider.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
