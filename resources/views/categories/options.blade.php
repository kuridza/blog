@php
    $hasChildren = count($category->children) > 0;
    $indent = str_repeat('-', $level * 2);
    $isSelected = ($category->id == $selectedCategoryId) ? 'selected' : '';
@endphp

<option value="{{ $category->id }}" {{ $isSelected }}>
    {{ $indent }} {{ $category->name }}
</option>

@if($hasChildren)
    @foreach($category->children as $subcategory)
        @include('categories.options', [
            'category' => $subcategory,
            'level' => $level + 1,
            'selectedCategoryId' => $selectedCategoryId
        ])
    @endforeach
@endif
