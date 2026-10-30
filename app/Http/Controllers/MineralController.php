<?php
namespace App\Http\Controllers;

use App\Http\Requests\MineralRequest;
use App\Models\{Mineral, Category, Supplier};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MineralController extends Controller
{
    public function index(Request $request)
    {
        $minerals = Mineral::with('category', 'supplier')
            ->search($request->q)
            ->when($request->category, fn($q, $c) => $q->where('category_id', $c))
            ->when($request->low_stock, fn($q) => $q->lowStock())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        return view('minerals.index', compact('minerals', 'categories'));
    }

    public function create()
    {
        return view('minerals.create', [
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(MineralRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('minerals', 'public');
        }
        Mineral::create($data);
        return redirect()->route('minerals.index')->with('success', 'Mineral created.');
    }

    public function show(Mineral $mineral)
    {
        $mineral->load('category', 'supplier');
        $movements = $mineral->stockMovements()->with('user')->latest()->paginate(15);
        return view('minerals.show', compact('mineral', 'movements'));
    }

    public function edit(Mineral $mineral)
    {
        return view('minerals.edit', [
            'mineral'    => $mineral,
            'categories' => Category::orderBy('name')->get(),
            'suppliers'  => Supplier::orderBy('name')->get(),
        ]);
    }

    public function update(MineralRequest $request, Mineral $mineral)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($mineral->image) Storage::disk('public')->delete($mineral->image);
            $data['image'] = $request->file('image')->store('minerals', 'public');
        }
        $mineral->update($data);
        return redirect()->route('minerals.index')->with('success', 'Mineral updated.');
    }

    public function destroy(Mineral $mineral)
    {
        $mineral->delete();
        return redirect()->route('minerals.index')->with('success', 'Mineral deleted.');
    }
}