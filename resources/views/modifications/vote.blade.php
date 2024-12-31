@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4">Propositions de Modifications en Attente</h1>

    @if($proposals->isEmpty())
        <p>Aucune proposition en attente.</p>
    @else
        <ul>
            @foreach($proposals as $proposal)
                <li class="mb-6">
                    <div class="border p-4 rounded">
                        <h2 class="text-xl font-semibold">Proposition #{{ $proposal->id }}</h2>
                        <p><strong>Proposé par :</strong> {{ $proposal->proposer->name }}</p>
                        <p><strong>Type :</strong> {{ ucfirst(str_replace('_', ' ', $proposal->type)) }}</p>
                        <p><strong>Données :</strong></p>
                        <pre class="bg-gray-100 p-2 rounded">{{ json_encode($proposal->data, JSON_PRETTY_PRINT) }}</pre>

                        <form action="{{ route('proposals.vote', $proposal->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" name="vote" value="approve" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Approuver</button>
                            <button type="submit" name="vote" value="reject" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Rejeter</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
