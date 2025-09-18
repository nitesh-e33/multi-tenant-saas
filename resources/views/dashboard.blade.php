<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Your Companies</h3>

                    {{-- Create Company Button --}}
                    <a href="{{ route('companies.create') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded mb-4 inline-block">
                        + Create Company
                    </a>

                    {{-- Company list --}}
                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2 text-left">Industry</th>
                                <th class="p-2 text-left">Address</th>
                                <th class="p-2 text-left">Active</th>
                                <th class="p-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(auth()->user()->companies as $company)
                                <tr class="border-t">
                                    <td class="p-2">{{ $company->name }}</td>
                                    <td class="p-2">{{ $company->industry }}</td>
                                    <td class="p-2">{{ $company->address }}</td>
                                    <td class="p-2">
                                        @if(auth()->user()->active_company_id == $company->id)
                                            <span class="text-green-600 font-semibold">Active</span>
                                        @else
                                            <form method="POST" action="{{ route('companies.switch') }}">
                                                @csrf
                                                <input type="hidden" name="company_id" value="{{ $company->id }}">
                                                <button class="text-blue-600 underline">Set Active</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="p-2 space-x-2">
                                        <a href="{{ route('companies.edit', $company->id) }}" class="text-yellow-600">Edit</a>
                                        <form action="{{ route('companies.destroy',$company->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this company?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">No companies yet. Create one above.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
