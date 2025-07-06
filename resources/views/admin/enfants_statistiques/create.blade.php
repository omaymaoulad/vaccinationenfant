@extends('layouts.admin')

@section('content')
    <h2>Ajouter les statistiques des enfants par secteur</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->has('duplicate'))
        <div class="alert alert-danger">{{ $errors->first('duplicate') }}</div>
    @endif

    <form method="POST" action="{{ route('enfants.storeMultiple') }}">
        @csrf

        <div class="mb-3 ">
            <label>Année</label>
            <input type="number" name="annee" class="form-control" required>
        </div>

        <table class="table table-bordered">
            <thead class="table-info">
                <tr>
                    <th>Secteur</th>
                    <th>Moins de 1 an</th>
                    <th>18 mois</th>
                    <th>5 ans</th>
                </tr>
            </thead>
            <tbody>
                @foreach($secteurs as $index => $secteur)
                    <tr>
                        <td>
                            {{ $secteur->nom }}
                            <input type="hidden" name="data[{{ $index }}][id_secteur]" value="{{ $secteur->id }}">
                        </td>
                        <td><input type="number" name="data[{{ $index }}][nb_moins_1_an]" class="form-control" required></td>
                        <td><input type="number" name="data[{{ $index }}][nb_18_mois]" class="form-control" required></td>
                        <td><input type="number" name="data[{{ $index }}][nb_5_ans]" class="form-control" required></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-info">Enregistrer</button>
    </form>
@endsection
