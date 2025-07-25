@extends('layouts.user')

@section('content')
<style>
/* Variables CSS pour une palette cohérente */
:root {
    --primary-color: #2563eb;
    --secondary-color: #64748b;
    --success-color: #059669;
    --info-color: #0891b2;
    --warning-color: #d97706;
    --danger-color: #dc2626;
    --light-bg: #f8fafc;
    --white: #ffffff;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Styles généraux */
.dashboard-container {
    background-color: var(--light-bg);
    min-height: 100vh;
    padding: 2rem 0;
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #35145e  100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-top: 0.5rem;
    font-weight: 300;
}

/* Cartes principales */
.stat-card {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--info-color));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.stat-card-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

/* Couleurs spécifiques des cartes */
.stat-card.primary .stat-card-icon {
    background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
    color: white;
}

.stat-card.success .stat-card-icon {
    background: linear-gradient(135deg, var(--success-color), #047857);
    color: white;
}

.stat-card.info .stat-card-icon {
    background: linear-gradient(135deg, var(--info-color), #0e7490);
    color: white;
}

.stat-card.secondary .stat-card-icon {
    background: linear-gradient(135deg, var(--secondary-color), #475569);
    color: white;
}

/* Bouton d'action */
.action-btn {
    background: linear-gradient(135deg, var(--info-color), #0e7490);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
    color: white;
    text-decoration: none;
}

/* Formulaire de sélection */
.filter-section {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.filter-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.form-select-custom {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    background: white;
}

.form-select-custom:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.btn-filter {
    background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

/* Tableau d'historique */
.history-section {
    background: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.history-header {
    background: linear-gradient(135deg, var(--info-color), #0e7490);
    color: white;
    padding: 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.history-table {
    margin: 0;
}

.history-table th {
    background: var(--light-bg);
    color: var(--text-dark);
    font-weight: 600;
    padding: 1rem;
    border: none;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.history-table td {
    padding: 1rem;
    border: none;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-dark);
    font-weight: 500;
}

.history-table tbody tr:hover {
    background: var(--light-bg);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem 0;
    }
    
    .dashboard-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .stat-card-value {
        font-size: 1.5rem;
    }
}
</style>

<div class="dashboard-container">
    <div class="container">
        <!-- En-tête du dashboard -->
        <div class="dashboard-header animate-fade-in">
            <h1 class="dashboard-title">Bienvenue {{ $user->name }}</h1>
            <p class="dashboard-subtitle">Tableau de bord de vaccination - Secteur {{ $secteur->nom }}</p>
        </div>

        <!-- Cartes principales -->
        <div class="row mb-4">
            <!-- Secteur -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stat-card primary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-card-title">Secteur concerné</div>
                    <h3 class="stat-card-value">{{ $secteur->nom }}</h3>
                </div>
            </div>

            <!-- Vaccinations semaine -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stat-card success animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-card-title">Vaccinés cette semaine</div>
                    <h3 class="stat-card-value">{{ $cetteSemaine }}</h3>
                    <div class="stat-card-label">Mois {{ $currentSemaine }}</div>
                </div>
            </div>

            <!-- Action -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="stat-card-title">Gestion des données</div>
                    <a href="{{route('user.vaccins.create')}}" class="action-btn">
                        <i class="fas fa-plus"></i>
                        Ajouter des données
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtre par année -->
        <div class="filter-section animate-fade-in">
            <form method="GET" action="{{ route('dashboard.user') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="annee" class="filter-title">Choisir une année d'analyse</label>
                        <select name="annee" id="annee" class="form-control form-select-custom">
                            @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                <option value="{{ $y }}" {{ $y == $annee ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-filter">
                            <i class="fas fa-filter"></i>
                            Afficher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques par tranche d'âge -->
        <div class="row mb-4" id="ageCards">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="stat-card-title">Naissances attendues</div>
                    <h3 class="stat-card-value" id="naissances">{{ $enfantsStats->nes ?? 0 }}</h3>
                    <div class="stat-card-label">enfants</div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-card-title">Moins de 1 an</div>
                    <h3 class="stat-card-value" id="moins1">{{ $enfantsStats->moins1 ?? 0 }}</h3>
                    <div class="stat-card-label">enfants</div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-card-title">12 mois</div>
                    <h3 class="stat-card-value" id="mois12">{{ $enfantsStats->mois12 ?? 0 }}</h3>
                    <div class="stat-card-label">enfants</div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-card-title">18 mois</div>
                    <h3 class="stat-card-value" id="mois18">{{ $enfantsStats->mois18 ?? 0 }}</h3>
                    <div class="stat-card-label">enfants</div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stat-card secondary animate-fade-in">
                    <div class="stat-card-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-card-title">5 ans</div>
                    <h3 class="stat-card-value" id="ans5">{{ $enfantsStats->ans5 ?? 0 }}</h3>
                    <div class="stat-card-label">enfants</div>
                </div>
            </div>
        </div>

        <!-- Historique -->
        <div class="history-section animate-fade-in">
            <div class="history-header">
                <i class="fas fa-chart-bar me-2"></i>
                Pourcentage de vaccination par tranche d'âge 
            </div>
            <div class="table-responsive">
                <table class="table history-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-alt me-2"></i>mois</th>
                            <th><i class="fas fa-baby me-2"></i>-1 an (%)</th>
                            <th><i class="fas fa-child me-2"></i>12 mois (%)</th>
                            <th><i class="fas fa-child me-2"></i>18 mois (%)</th>
                            <th><i class="fas fa-child me-2"></i>5 ans (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historiqueParAge as $item)
                            <tr>
                                <td><strong>{{ $item->mois }}</strong></td>
                                <td>
                                    <span class="badge badge-sm" style="background: {{ $item->cibles_m1 ? (($item->vaccines_m1 / $item->cibles_m1 * 100) >= 80 ? 'var(--success-color)' : (($item->vaccines_m1 / $item->cibles_m1 * 100) >= 50 ? 'var(--warning-color)' : 'var(--danger-color)')) : 'var(--secondary-color)' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        {{ $item->cibles_m1 ? round($item->vaccines_m1 / $item->cibles_m1 * 100, 1) : 0 }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-sm" style="background: {{ $item->cibles_12 ? (($item->vaccines_12 / $item->cibles_12 * 100) >= 80 ? 'var(--success-color)' : (($item->vaccines_12 / $item->cibles_12 * 100) >= 50 ? 'var(--warning-color)' : 'var(--danger-color)')) : 'var(--secondary-color)' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        {{ $item->cibles_12 ? round($item->vaccines_12 / $item->cibles_12 * 100, 1) : 0 }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-sm" style="background: {{ $item->cibles_18 ? (($item->vaccines_18 / $item->cibles_18 * 100) >= 80 ? 'var(--success-color)' : (($item->vaccines_18 / $item->cibles_18 * 100) >= 50 ? 'var(--warning-color)' : 'var(--danger-color)')) : 'var(--secondary-color)' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        {{ $item->cibles_18 ? round($item->vaccines_18 / $item->cibles_18 * 100, 1) : 0 }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-sm" style="background: {{ $item->cibles_5 ? (($item->vaccines_5 / $item->cibles_5 * 100) >= 80 ? 'var(--success-color)' : (($item->vaccines_5 / $item->cibles_5 * 100) >= 50 ? 'var(--warning-color)' : 'var(--danger-color)')) : 'var(--secondary-color)' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        {{ $item->cibles_5 ? round($item->vaccines_5 / $item->cibles_5 * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Animation séquentielle des cartes
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Gestion du changement d'année avec l'ID correct
document.getElementById('annee').addEventListener('change', function () {
    const annee = this.value;
    
    // Indicateur de chargement
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
    loadingIndicator.className = 'text-center p-3';
    
    fetch(`/user/enfants-par-age/${annee}`)
        .then(response => response.json())
        .then(data => {
            // Animation de mise à jour
            const elements = ['moins1', 'mois12', 'mois18', 'ans5', 'naissances'];
            elements.forEach(id => {
                const element = document.getElementById(id);
                element.style.transform = 'scale(1.1)';
                element.style.color = 'var(--primary-color)';
                
                setTimeout(() => {
                    element.innerText = data[id] ?? 0;
                    element.style.transform = 'scale(1)';
                    element.style.color = 'var(--text-dark)';
                }, 200);
            });
        })
        .catch(error => {
            console.error("Erreur lors du chargement des données :", error);
            // Afficher un message d'erreur à l'utilisateur
            alert("Erreur lors du chargement des données. Veuillez réessayer.");
        });
});
</script>

@endsection