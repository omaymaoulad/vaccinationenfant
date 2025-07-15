@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📋 Tableau de vaccination par zone</h2>

    <form method="GET" action="{{ route('admin.stats.zones') }}" class="row mb-4">
        <div class="col-md-3">
            <label>Année</label>
            <select name="annee" class="form-control">
                @foreach($annees as $year)
                    <option value="{{ $year }}" {{ $annee == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Afficher</button>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" name="show_total" value="1" class="btn btn-success w-100">
                Afficher le total
            </button>
        </div>
    </form>

    <!-- Affichage des cibles -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Enfants cibles - Zone Urbaine
                </div>
                <div class="card-body">
                    <p>
                        - Moins de 1 an : <strong>{{ $urbain['cibles']['0-11mois'] ?? 0 }}</strong> enfants<br>
                        - 12-59 mois : <strong>{{ $urbain['cibles']['12-59mois'] ?? 0 }}</strong> enfants<br>
                        - 18 mois : <strong>{{ $urbain['cibles']['18mois'] ?? 0 }}</strong> enfants<br>
                        - 5 ans : <strong>{{ $urbain['cibles']['5ans'] ?? 0 }}</strong> enfants
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Enfants cibles - Zone Rurale
                </div>
                <div class="card-body">
                    <p>
                        - Moins de 1 an : <strong>{{ $rural['cibles']['0-11mois'] ?? 0 }}</strong> enfants<br>
                        - 12-59 mois : <strong>{{ $rural['cibles']['12-59mois'] ?? 0 }}</strong> enfants<br>
                        - 18 mois : <strong>{{ $rural['cibles']['18mois'] ?? 0 }}</strong> enfants<br>
                        - 5 ans : <strong>{{ $rural['cibles']['5ans'] ?? 0 }}</strong> enfants
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des vaccinations -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            Vaccinations - Zone Urbaine
        </div>
        <div class="card-body">
            @if(isset($urbain['data']) && !empty($urbain['data']))
                @include('admin.stats.partials.zone-table', ['data' => $urbain['data']])
            @else
                <div class="alert alert-warning">Aucune donnée de vaccination disponible pour la zone urbaine</div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            Vaccinations - Zone Rurale
        </div>
        <div class="card-body">
            @if(isset($rural['data']) && !empty($rural['data']))
                @include('admin.stats.partials.zone-table', ['data' => $rural['data']])
            @else
                <div class="alert alert-warning">Aucune donnée de vaccination disponible pour la zone rurale</div>
            @endif
        </div>
    </div>

    @if($showTotal)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            Total (Urbain + Rural)
        </div>
        <div class="card-body">
            @if(isset($total['data']) && !empty($total['data']))
                @include('admin.stats.partials.zone-table', ['data' => $total['data']])
            @else
                <div class="alert alert-warning">Aucune donnée disponible pour le total</div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection