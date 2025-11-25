<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create($data);

        return redirect()->route('categories.edit', $category)->with('status', 'category-created');
    }

    public function create()
    {
        return view('categories.form', ['title' => __('Dodavanje kategorije')]);
    }

    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        $title = __('Izmena kategorije') . ': ' . $category->getAllParentNames() . ' > ' . $category->name;

        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('categories.form', compact('category', 'categories', 'title'));
    }

    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('categories.index', compact('categories'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('update', $category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'child' => ['string', 'max:255'],
        ]);

        $category->update([
            'name' => $data['name'],
        ]);

        if ($request->has('child')) {
            Category::create([
                'name' => $data['child'],
                'parent_id' => $category->id,
            ]);
        }

        return redirect()->route('categories.edit', $category)->with('status', 'category-updated');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        $category->delete();

        return back()->with('status', 'category-deleted');
    }
}
