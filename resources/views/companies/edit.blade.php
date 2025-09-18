<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Company</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('companies.update', $company->id) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name',$company->name) }}" class="border p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label>Industry</label>
                <input type="text" name="industry" value="{{ old('industry',$company->industry) }}" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address',$company->address) }}" class="border p-2 w-full">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </form>
    </div>
</x-app-layout>
