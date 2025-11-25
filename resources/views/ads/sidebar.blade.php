<aside class="bg-gray-800 p-4 rounded-lg mr-6">
    <h1>Sve Kategorije</h1>
    <hr>
    <div class="category-tree">
        <ul>
            @foreach($categories as $category)
                @include('categories.tree', ['category' => $category, 'activeCategory' => $activeCategory, 'route' => route('ads.index', ['category_id' => $category->id])])
            @endforeach
        </ul>
    </div>
</aside>
