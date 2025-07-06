@extends('layouts.admin')

@section('content')
<h2>Ajouter un utilisateur</h2>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Secteur</label>
        <select name="secteur_id" class="form-control">
            @foreach(App\Models\Secteur::all() as $secteur)
                <option value="{{ $secteur->id }}">{{ $secteur->nom }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Enregistrer</button>
</form>
@endsection
