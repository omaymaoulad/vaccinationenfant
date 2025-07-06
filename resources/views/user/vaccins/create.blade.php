@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ajouter les données de vaccination</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.vaccins.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Année :</label>
            <select name="annee" class="form-control" required>
                @foreach($annees as $a)
                    <option value="{{ $a }}">{{ $a }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Semaine :</label>
            <select name="semaine" class="form-control" required>
                @for($i = 1; $i <= 52; $i++)
                    <option value="{{ $i }}">Semaine {{ $i }}</option>
                @endfor
            </select>
        </div>

        <table class="table table-bordered">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Vaccin</th>
                    @foreach($tranches as $tranche)
                        <th>{{ $tranche }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($vaccins as $vaccin)
                    <tr>
                        <td><strong>{{ $vaccin }}</strong></td>
                        @foreach($tranches as $tranche)
                            <td>
                                <input type="number" min="0" class="form-control"
                                    name="data[{{ $vaccin }}][{{ $tranche }}]">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</div>
@endsection
