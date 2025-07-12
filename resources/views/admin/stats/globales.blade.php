@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📋 Tableau de vaccination par secteur</h2>

    <!-- Formulaire de filtre -->
    <form method="GET" action="{{ route('admin.stats.globales') }}" class="row mb-4">
        <div class="col-md-3">
            <label for="annee">Année</label>
            <select name="annee" id="annee" class="form-control">
                @foreach(range(date('Y'), date('Y') + 5) as $year)
                    <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="secteur">Secteur</label>
            <select name="secteur" id="secteur" class="form-control">
                @foreach($secteurs as $secteur)
                    <option value="{{ $secteur->id }}" {{ request('secteur') == $secteur->id ? 'selected' : '' }}>
                        {{ $secteur->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="mode">Mode</label>
            <select name="mode" id="mode" class="form-control">
                <option value="semaine" {{ request('mode') == 'semaine' ? 'selected' : '' }}>Par semaine</option>
                <option value="mois" {{ request('mode') == 'mois' ? 'selected' : '' }}>Par mois</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Afficher</button>
        </div>
    </form>

    @if(isset($ciblesParTranche))
        <div class="alert alert-info">
            <strong>Enfants cibles pour ce secteur ({{ $annee }}) :</strong><br>
            - Moins de 1 an : <strong>{{ $ciblesParTranche['0-11mois'] }}</strong> enfants |
            12 mois :<strong>{{$ciblesParTranche['12-59mois']}}</strong> enfants |
            18 mois : <strong>{{ $ciblesParTranche['18 mois'] }}</strong> enfants |
            5 ans : <strong>{{ $ciblesParTranche['5 ans'] }}</strong> enfants
        </div>
    @endif

    @if(empty($records))
        <div class="alert alert-warning">Aucune donnée disponible.</div>
    @else
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Vaccin</th>
                    <th>Tranche d'âge</th>
                    @foreach($colonnes as $col)
                        <th>{{ $mode === 'mois' ? "Mois $col" : "Semaine $col" }}</th>
                    @endforeach
                    <th>Total vaccinés</th>
                    <th>Non vaccinés</th>
                    <th>% Vaccination</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $vaccin => $tranches)
                    @php $first = true; $rowspan = count($tranches); @endphp
                    @foreach($tranches as $tranche => $valeurs)
                        @php
                            $trancheFormatee = $tranche;
                            if (in_array($tranche, ['0-11mois', '0-11 mois'])) $trancheFormatee = '0-11mois';
                            elseif (in_array($tranche, ['12-59mois'])) $trancheFormatee = '12-59mois';
                            elseif (in_array($tranche, ['18 mois', '18mois'])) $trancheFormatee = '18 mois';
                            elseif (in_array($tranche, ['5ans', '5 ans'])) $trancheFormatee = '5 ans';
                            $totalVax = collect($valeurs)->sum('vaccines');
                            $cibleTranche = $ciblesParTranche[$trancheFormatee] ?? 0;
                            $reste = max(0, $cibleTranche - $totalVax);
                            $pourcentage = $cibleTranche ? round(($totalVax / $cibleTranche) * 100, 2) : 0;
                        @endphp
                        <tr>
                            @if($first)
                                <td rowspan="{{ $rowspan }}">{{ $vaccin }}</td>
                                @php $first = false; @endphp
                            @endif
                            <td>{{ $trancheFormatee }}</td>
                            @foreach($colonnes as $col)
                                <td>{{ $valeurs[$col]['vaccines'] ?? 0 }}</td>
                            @endforeach
                            <td>{{ $totalVax }}</td>
                            <td>{{ $reste }}</td>
                            <td>{{ $pourcentage }}%</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
