@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        --info-gradient: linear-gradient(135deg, #3498db 0%, #74b9ff 100%);
        --warning-gradient: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
        --danger-gradient: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --card-hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        --border-radius: 15px;
        --transition: all 0.3s ease;
    }

    body {
        background: var(--primary-gradient) !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        color: #2c3e50 !important;
        min-height: 100vh;
    }

    .container-fluid {
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 20px !important;
        padding: 40px !important;
        margin: 20px auto !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 1400px;
    }

    .container-fluid h1 {
        text-align: center !important;
        font-size: 2.5rem !important;
        font-weight: 700 !important;
        color: #2c3e50 !important;
        margin-bottom: 40px !important;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1) !important;
        position: relative;
    }

    .container-fluid h1::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    .container-fluid h1::before {
        content: '💉';
        margin-right: 15px;
    }

    /* Cards styling */
    .card {
        border: none !important;
        border-radius: var(--border-radius) !important;
        box-shadow: var(--card-shadow) !important;
        transition: var(--transition) !important;
        overflow: hidden !important;
        background: white !important;
        position: relative;
        margin-bottom: 20px;
    }

    .card:hover {
        transform: translateY(-5px) !important;
        box-shadow: var(--card-hover-shadow) !important;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: #ddd;
        z-index: 1;
    }

    /* Primary card */
    .bg-primary {
        background: var(--primary-gradient) !important;
    }

    .bg-primary::before {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    /* Success card */
    .bg-success {
        background: var(--success-gradient) !important;
    }

    .bg-success::before {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    /* Info card */
    .bg-info {
        background: var(--info-gradient) !important;
    }

    .bg-info::before {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    /* Warning card */
    .bg-warning {
        background: var(--warning-gradient) !important;
        color: white !important;
    }

    .bg-warning::before {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    .bg-warning .card-title {
        color: white !important;
    }

    /* Danger card */
    .bg-danger {
        background: var(--danger-gradient) !important;
    }

    .bg-danger::before {
        background: rgba(255, 255, 255, 0.3) !important;
    }

    /* Secondary card */
    .bg-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
    }

    .card-body {
        padding: 30px !important;
        position: relative;
        z-index: 2;
    }

    .card-header {
        padding: 25px 30px !important;
        border-bottom: none !important;
        font-weight: 600 !important;
        font-size: 1.1rem !important;
        position: relative;
        z-index: 2;
    }

    .card-title {
        margin: 0 !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 1rem !important;
    }

    .display-4 {
        font-size: 3rem !important;
        font-weight: 700 !important;
        line-height: 1 !important;
        margin-bottom: 15px !important;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .card-text {
        opacity: 0.9 !important;
        font-weight: 500 !important;
        font-size: 0.9rem !important;
    }

    /* Progress bars */
    .progress {
        height: 25px !important;
        border-radius: 12px !important;
        background: rgba(255, 255, 255, 0.2) !important;
        overflow: hidden !important;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .progress-bar {
        border-radius: 12px !important;
        position: relative;
        transition: width 0.8s ease !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-bar.bg-success {
        background: var(--success-gradient) !important;
    }

    .progress-bar.bg-danger {
        background: var(--danger-gradient) !important;
    }

    /* Table styling */
    .table {
        background: white !important;
        border-radius: var(--border-radius) !important;
        overflow: hidden !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
        border: none !important;
    }

    .table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
        border: none !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        padding: 20px 15px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem !important;
    }

    .table tbody td {
        padding: 20px 15px !important;
        border-color: #f1f3f4 !important;
        vertical-align: middle !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: rgba(102, 126, 234, 0.02) !important;
    }

    .table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05) !important;
        transform: scale(1.01);
        transition: var(--transition);
    }

    /* Badges */
    .badge {
        padding: 8px 16px !important;
        border-radius: 20px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem !important;
        border: none !important;
    }

    .badge-danger {
        background: var(--danger-gradient) !important;
        color: white !important;
    }

    .badge-success {
        background: var(--success-gradient) !important;
        color: white !important;
    }

    /* Alert */
    .alert {
        border: none !important;
        border-radius: var(--border-radius) !important;
        padding: 25px !important;
        font-weight: 600 !important;
        box-shadow: var(--card-shadow) !important;
    }

    .alert-success {
        background: var(--success-gradient) !important;
        color: white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px;
    }

    .alert-success::before {
        content: '✅';
        font-size: 1.2rem;
    }

    /* Icons for cards */
    .card-header.bg-warning::before {
        content: '🏆';
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .card-header.bg-secondary::before {
        content: '📊';
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .card-header.bg-danger::before {
        content: '⚠️';
        margin-right: 10px;
        font-size: 1.2rem;
    }

    /* Animation for cards */
    .card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .row .col-md-4:nth-child(1) .card { animation-delay: 0.1s; }
    .row .col-md-4:nth-child(2) .card { animation-delay: 0.2s; }
    .row .col-md-4:nth-child(3) .card { animation-delay: 0.3s; }
    .row .col-md-6:nth-child(1) .card { animation-delay: 0.4s; }
    .row .col-md-6:nth-child(2) .card { animation-delay: 0.5s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Chart container */
    #ageDistributionChart {
        height: 300px !important; 
        padding: 20px !important;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .container-fluid {
            margin: 10px !important;
            padding: 20px !important;
        }

        .container-fluid h1 {
            font-size: 2rem !important;
        }

        .display-4 {
            font-size: 2.5rem !important;
        }

        .card-body {
            padding: 20px !important;
        }

        .card-header {
            padding: 20px !important;
        }
    }

    /* Table responsive wrapper */
    .table-responsive {
        border-radius: var(--border-radius) !important;
        box-shadow: var(--card-shadow) !important;
    }
</style>

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
                        <p class="text-muted">Aucune donnée disponible</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Nombre total d'enfants cibles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="card-title">Répartition des Enfants Cibles</h4>
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
                        '#FF6B6B',
                        '#4ECDC4',
                        '#45B7D1',
                        '#96CEB4'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 30,
                            usePointStyle: true,
                            font: {
                                size: 14,
                                weight: 600
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Répartition des enfants cibles par tranche d\'âge',
                        font: {
                            size: 14,
                            weight: 600
                        },
                        padding: 20
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>
@endsection