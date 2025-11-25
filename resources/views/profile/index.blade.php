@extends('layouts.app', ['header' => 'Korisnici'])

@section('content')
<div class="text-white p-5 py-12 max-w-7xl mx-auto">
    @forelse ($profiles as $profile)
        <div class="p-4 mb-2 bg-gray-800 rounded-lg">
            <a href="{{ route('profile.edit', ['user_id' => $profile->id]) }}" class="hover:underline">
                <div class="">
                    {{ $profile->name }}
                    <span class="text-gray-400 text-sm">{{ $profile->role }}</span>
                </div>
            </a>
        </div>
    @empty
        <div class="text-gray-600">No profiles found.</div>
    @endforelse

    <div class="mt-6">
            {{ $profiles->links() }}
    </div>
</div>
@endsection
