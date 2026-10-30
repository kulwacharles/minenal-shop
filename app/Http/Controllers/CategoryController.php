<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('minerals')->orderBy('name')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create() { return view('categories.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:20',
        ]);
        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category) { return view('categories.edit', compact('category')); }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:20',
        ]);
        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->minerals()->exists()) {
            return back()->with('error', 'Cannot delete category with minerals.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}