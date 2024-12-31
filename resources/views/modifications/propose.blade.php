@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Proposer une Modification</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('proposals.propose', '') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="type" class="block text-gray-700 font-bold mb-2">Type de Modification</label>
            <select name="type" id="type" required class="w-full px-3 py-2 border rounded">
                <option value="">-- Sélectionner --</option>
                <option value="update_person">Mettre à Jour les Informations</option>
                <option value="add_relationship">Ajouter une Relation de Parenté</option>
            </select>
        </div>
         <div class="mb-4">
            <label for="person_id" class="block text-gray-700 font-bold mb-2">Sélectionner la Personne Associée</label>
            <select name="person_id" id="person_id" required class="w-full px-3 py-2 border rounded">
                <option value="">-- Sélectionner --</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="data" class="block text-gray-700 font-bold mb-2">Données de Modification (JSON)</label>
            <textarea name="data" id="data" rows="6" required class="w-full px-3 py-2 border rounded" placeholder='Exemples :
{
    "first_name": "Nouveau Prénom",
    "last_name": "Nouveau Nom"
}
Ou pour ajouter une relation :
{
    "parent_id": 1,
    "child_id": 2
}'>{{ old('data') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Proposer la Modification</button>
    </form>
</div>
@endsection