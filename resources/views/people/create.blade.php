@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Ajouter une Nouvelle Personne</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Erreurs :</strong>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('people.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label for="birth_name" class="block text-sm font-medium text-gray-700">Nom de Naissance</label>
            <input type="text" name="birth_name" id="birth_name" value="{{ old('birth_name') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label for="middle_names" class="block text-sm font-medium text-gray-700">Prénoms</label>
            <input type="text" name="middle_names" id="middle_names" value="{{ old('middle_names') }}" placeholder="Séparez les prénoms par des virgules"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
