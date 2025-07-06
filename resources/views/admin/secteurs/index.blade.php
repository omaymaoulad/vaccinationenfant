@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Liste des secteurs</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.secteurs.create') }}" class="btn btn-primary mb-3">➕ Ajouter un secteur</a>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Zone</th>
                <th>Niveau</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($secteurs as $secteur)
                <tr>
                    <td>{{ $secteur->nom }}</td>
                    <td>{{ $secteur->zone }}</td>
                    <td>{{ $secteur->niveau }}</td>
                    <td>
                        <form action="{{ route('admin.secteurs.destroy', $secteur) }}" method="POST" onsubmit="return confirm('Supprimer ce secteur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
