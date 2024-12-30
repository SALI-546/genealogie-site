@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des Personnes</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('people.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Ajouter une Personne</a>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Prénom</th>
                    <th class="py-2 px-4 border-b">Nom</th>
                    <th class="py-2 px-4 border-b">Créé par</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($people as $person)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $person->first_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $person->last_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $person->creator->name }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('people.show', $person->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $people->links() }}
    </div>
</div>
@endsection
