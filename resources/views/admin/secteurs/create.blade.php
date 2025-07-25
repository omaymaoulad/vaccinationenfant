@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Ajouter un secteur</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.secteurs.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="zone">Zone</label>
            <select name="zone" class="form-select" required>
                <option value="urbain">Urbain</option>
                <option value="rural">Rural</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="niveau">Niveau</label>
            <select name="niveau" class="form-select" required>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">✅ Enregistrer</button>
    </form>
</div>
@endsection
