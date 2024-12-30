@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Détails de la Personne</h1>

    <div class="mb-6">
        <p><strong>Prénom :</strong> {{ $person->first_name }}</p>
        <p><strong>Nom :</strong> {{ $person->last_name }}</p>
        <p><strong>Nom de naissance :</strong> {{ $person->birth_name ?? 'N/A' }}</p>
        <p><strong>Prénoms :</strong> {{ $person->middle_names ?? 'N/A' }}</p>
        <p><strong>Date de naissance :</strong> {{ $person->date_of_birth ?? 'N/A' }}</p>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold">Parents</h2>
        @if($person->parents->isEmpty())
            <p>Aucun parent enregistré.</p>
        @else
            <ul class="list-disc list-inside">
                @foreach($person->parents as $parent)
                    <li>{{ $parent->first_name }} {{ $parent->last_name }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold">Enfants</h2>
        @if($person->children->isEmpty())
            <p>Aucun enfant enregistré.</p>
        @else
            <ul class="list-disc list-inside">
                @foreach($person->children as $child)
                    <li>{{ $child->first_name }} {{ $child->last_name }}</li>
                @endforeach
            </ul>
        @endif
    </div>

   <!-- Section pour afficher le degré de parenté -->
   <div class="mb-6">
    <h2 class="text-xl font-semibold">Degré de Parenté avec Personne {{ $targetPersonId }}</h2>
    @if($degree !== false)
        <p>Le degré de parenté est : {{ $degree }}</p>
        {{-- Optionnel : Afficher le chemin si disponible --}}
    @else
        <p>Le degré de parenté est supérieur à 25 ou aucune connexion trouvée.</p>
    @endif
</div>

    <a href="{{ route('people.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Retour à la Liste</a>
</div>
@endsection
