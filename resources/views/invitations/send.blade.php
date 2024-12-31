@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Envoyer une Invitation</h1>

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

    <form action="{{ route('invitations.send') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="invitee_email" class="block text-gray-700 font-bold mb-2">Adresse E-mail de l'Invité(e)</label>
            <input type="email" name="invitee_email" id="invitee_email" value="{{ old('invitee_email') }}" required class="w-full px-3 py-2 border rounded">
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

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Envoyer l'Invitation</button>
    </form>
</div>
@endsection
