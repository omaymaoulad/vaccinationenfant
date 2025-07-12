@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tableau de Bord de Vaccination</h1>
    
    <div class="row mb-4">
        <!-- Carte: Pourcentage global -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Taux de Vaccination Global</h5>
                    <h2 class="display-4">{{ number_format($globalPercentage, 2) }}%</h2>
                    <p class="card-text">Total enfants vaccinés / Total enfants cibles</p>
                </div>
            </div>
        </div>
        
        <!-- Carte: Nombre total d'enfants vaccinés -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Enfants Vaccinés (Année en cours)</h5>
                    <h2 class="display-4">{{ number_format($totalVaccines) }}</h2>
                    <p class="card-text">Sur {{ number_format($totalEnfantsCibles) }} enfants cibles</p>
                </div>
            </div>
        </div>
        
        <!-- Carte: Vaccination cette semaine -->
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Vaccinations cette semaine</h5>
                    <h2 class="display-4">{{ number_format($vaccinationThisWeek) }}</h2>
                    <p class="card-text">Semaine {{ date('W') }} de {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Secteur le plus performant -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Secteur le plus performant</h3>
                </div>
                <div class="card-body">
                    @if($bestSector)
                        <h4>Secteur #{{ $bestSector->id_secteur }}</h4>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $bestSector->avg_percentage }}%" 
                                 aria-valuenow="{{ $bestSector->avg_percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($bestSector->avg_percentage, 2) }}%
                            </div>
                        </div>
                    @else
                        <p>Aucune donnée disponible</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Nombre total d'enfants cibles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title">Répartition des Enfants Cibles</h3>
                </div>
                <div class="card-body">
                    <canvas id="ageDistributionChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Secteurs à surveiller -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Secteurs à Surveiller (taux < 50%)</h3>
        </div>
        <div class="card-body">
            @if($weakSectors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Secteur</th>
                                <th>Taux Moyen</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weakSectors as $sector)
                            <tr>
                                <td>Secteur #{{ $sector->id_secteur }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: {{ $sector->avg_percentage }}%" 
                                             aria-valuenow="{{ $sector->avg_percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($sector->avg_percentage, 2) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-danger">Critique</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success">
                    Aucun secteur en dessous de 50% de vaccination. Bonne performance globale!
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique de répartition par âge
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ageDistributionChart').getContext('2d');
        const ageChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Moins de 1 an', '12 mois', '18 mois', '5 ans'],
                datasets: [{
                    data: [
                        {{ $enfantsData->nb_moins_1_an ?? 0 }},
                        {{ $enfantsData->nb_12_mois ?? 0 }},
                        {{ $enfantsData->nb_18_mois ?? 0 }},
                        {{ $enfantsData->nb_5_ans ?? 0 }}
                    ],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Répartition des enfants cibles par tranche d\'âge'
                    }
                }
            }
        });
    });
</script>
@endsection