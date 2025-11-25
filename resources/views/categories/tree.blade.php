<style>
    .category-tree ul {
        list-style-type: none;
        padding-left: 20px;
    }
    .category-tree li {
        margin: 5px 0;
    }
</style>

<li>
    @php
        $isSelected = isset($activeCategory) && $activeCategory == $category->id;
    @endphp
    <a href="{{ $route }}" class="p-1 hover:underline @if($isSelected)text-blue-500 @endif">{{ $category->name }}</a>

    @if(request()->routeIs('categories.index'))
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button class="p-1 text-red-600 ml-4" onclick="return confirm('{{ __('Da li ste sigurni?') }}')">x</button>
        </form>
    @endif

    @if(count($category->children) > 0)
        <ul>
            @foreach($category->children as $subcategory)
                @if(request()->routeIs('ads.index') || request()->routeIs('ads.show') || request()->routeIs('dashboard'))
                    @php $route = route('ads.index', ['category_id' => $subcategory->id]) @endphp
                @else
                    @php $route = route('categories.edit', $subcategory, ['category_id' => $subcategory->id]) @endphp
                @endif

                @include('categories.tree', ['category' => $subcategory, 'route' => $route])
            @endforeach
        </ul>
    @endif
</li>


