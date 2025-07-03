@extends('layouts.admin')

@section('content')
    <h2>Ajouter les statistiques des enfants par secteur</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->has('duplicate'))
        <div class="alert alert-danger">
            {{ $errors->first('duplicate') }}
        </div>
    @endif

    <form method="POST" action="{{ route('enfants.store') }}">
        @csrf

        <div class="mb-3">
            <label>Secteur</label>
            <select name="id_secteur" class="form-control" required>
                @foreach($secteurs as $secteur)
                    <option value="{{ $secteur->id }}">{{ $secteur->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Année</label>
            <input type="number" name="annee" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Moins de 1 an</label>
            <input type="number" name="nb_moins_1_an" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>18 mois</label>
            <input type="number" name="nb_18_mois" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>5 ans</label>
            <input type="number" name="nb_5_ans" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
@endsection
