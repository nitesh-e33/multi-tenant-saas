<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Company</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('companies.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block">Name</label>
                <input type="text" name="name" class="border p-2 w-full" required value="{{ old('name') }}">
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block">Industry</label>
                <input type="text" name="industry" class="border p-2 w-full" value="{{ old('industry') }}">
            </div>
            <div class="mb-4">
                <label class="block">Address</label>
                <input type="text" name="address" class="border p-2 w-full" value="{{ old('address') }}">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 rounded">Cancel</a>
        </form>
    </div>
</x-app-layout>
