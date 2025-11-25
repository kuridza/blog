@extends('layouts.app', ['header' => $title])

@section('content')
    <div class="flex text-white py-12 p-5 max-w-7xl mx-auto">
        @include('ads.sidebar', ['categories' => $categories, 'activeCategory' => request()->get('category_id')])

        <main class="">
            @if(! isset($type))
                <form method="GET" action="{{ route('ads.index') }}">
                    <div class="flex items-start mb-2">
                        <x-text-input name="search" placeholder="{{ __('Pretraga') }}" class="mr-2 w-48"
                                      :value="request()->get('search')"/>
                        <select name="category_id" id="category_id"
                                class="dark:bg-gray-900 rounded-lg dark:border-gray-700 mr-2 w-48">
                            <option value="">-- {{ __('Kategorija') }} --</option>
                            @foreach($categories as $category)
                                @include('categories.options', ['category' => $category, 'level' => 0, 'selectedCategoryId' => request()->get('category_id') ])
                            @endforeach
                        </select>
                        @inject('locations', \App\Services\Locations::class)
                        <select name="location" id="location" class="dark:bg-gray-900 rounded-lg dark:border-gray-700 w-48">
                            <option value="">-- {{ __('Grad') }} --</option>
                            @foreach($locations->getLocations() as $city)
                                <option {{ ( request()->get('location') === $city) ? 'selected' : '' }}>{{ $city  }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-start">
                        <x-text-input type="number" name="price_from" class="mr-2 w-48"
                                      placeholder="{{ __('Cena od') }}" :value="request()->get('price_from')"/>
                        <x-text-input type="number" name="price_to" class="mr-2 w-48" placeholder="{{ __('Cena do') }}"
                                      :value="request()->get('price_to')"/>


                        <select name="sort" id="sort" class="dark:bg-gray-900 rounded-lg dark:border-gray-700 w-48">
                            <option value="">{{ __('Novije') }}</option>
                            <option
                                {{ ( request()->get('sort') === 'asc') ? 'selected' : '' }} value="asc">{{ __('Jeftinije') }}</option>
                            <option
                                {{ ( request()->get('sort') === 'desc') ? 'selected' : '' }} value="desc">{{ __('Skuplje') }}</option>
                        </select>

                        <x-primary-button type="submit" class="ml-4 mt-1">{{ __('Trazi') }}</x-primary-button>
                    </div>
                </form>
            @else
                <h1 class="text-3xl">{{ __('Moji oglasi') }}</h1>
            @endif
            @forelse ($ads as $ad)
                <div class="p-6 mt-3 bg-gray-800 rounded-lg">
                    <a href="{{ route('ads.show', $ad) }}" class="">
                        <div class="flex">
                            @if($ad->image)
                                <img src="/images/{{ $ad->image }}" class="w-48">
                            @endif
                            <p class="flex-1 ml-4 mr-6">
                                {{ $ad->title }}<br><br>
                                <span class="text-gray-500">{{ \Illuminate\Support\Str::limit($ad->content, 100) }}</span>
                            </p>
                            <p class="flex-1 ml-12">
                                <span class="text-red-600">{{ $ad->price }} RSD</span><br><br>
                                <span>{{ $ad->location }}</span>
                            </p>
                            <p class="flex-1 text-gray-400">{{ __('Postavio/la') }} {{ $ad->user->name }} <br> {{ $ad->created_at->format('d/m/Y') }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="text-gray-600 mt-4">{{ __('Nema oglasa') }}</div>
            @endforelse

            <div class="mt-6">
                {{ $ads->links() }}
            </div>
        </main>
    </div>
@endsection
