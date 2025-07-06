@extends('layouts.admin')

@section('content')
<h2>Liste des utilisateurs</h2>

<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Ajouter un utilisateur</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Secteur</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->secteur->nom ?? 'Aucun' }}</td>
            <td>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
