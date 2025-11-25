<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'search', 'price_from', 'price_to', 'location', 'sort']);

        $ads = Ad::query()
            ->with(['user'])
            ->withFilters($filters)
            ->paginate(10)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->get();

        $title = __('Svi oglasi');
        if ($request->get('category_id')) {
            $category = Category::find($request->get('category_id'));
            $parents = $category->getAllParentNames();
            $title = ($parents ? $parents . ' > ' : '') . $category->name;
        }

        return view('ads.index', compact('ads', 'title', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('ads.form', ['title' => __('Dodavanje oglasa'), 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_new' => ['required', 'boolean'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact_phone' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $imageName = time().'.'. $data['image']->getClientOriginalExtension();
        $data['image']->move(public_path('images'), $imageName);

        $ad = Ad::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'price' => $data['price'],
            'content' => $data['content'],
            'category_id' => $data['category_id'],
            'is_new' => $data['is_new'],
            'image' => $imageName,
            'contact_phone' => $data['contact_phone'],
            'location' => $data['location'],
        ]);

        return redirect()->route('ads.show', $ad)->with('status', 'ad-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ad $ad)
    {
        $ad->load(['user']);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $parents = $ad->category->getAllParentNames();
        $categoryPath = ($parents ? $parents . ' > ' : '') . $ad->category->name;

        return view('ads.show', compact('ad', 'categories', 'categoryPath'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('ads.form', ['ad' => $ad, 'title' => __('Izmena oglasa') . ': ' . $ad->title, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_new' => ['required', 'boolean'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact_phone' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'. $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('images'), $imageName);
            $ad->update(['image' => $imageName]);
        }

        $ad->update([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'price' => $data['price'],
            'content' => $data['content'],
            'category_id' => $data['category_id'],
            'is_new' => $data['is_new'],
            'contact_phone' => $data['contact_phone'],
            'location' => $data['location'],
        ]);

        return redirect()->route('ads.show', $ad)->with('status', 'ad-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        Gate::authorize('delete', $ad);

        $ad->delete();

        return redirect()->route('ads.index')->with('status', 'ad-deleted');
    }

    public function dashboard()
    {
        $ads = Ad::query()
            ->with(['user'])
            ->where('user_id', Auth::id())
            ->paginate(8)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->get();

        $type = 'dashboard';

        $title = __('Moji oglasi');

        return view('ads.index', compact('ads', 'title', 'categories', 'type'));
    }

}
