@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4 ">📊 Statistiques de vaccination</h2>
    <form method="GET" class="row mb-2">
        <div class="col-md-8">
            <label for="annee">Année</label>
            <select name="annee" id="annee" class="form-control">
                @foreach(range(date('Y'), date('Y') - 5) as $year)
                    <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Afficher</button>
        </div>
    </form>

    <!--  Vaccinés par vaccin sur 5 ans -->
    <div class="mb-5">
        <h4>Vaccinés par vaccin (5 dernières années)</h4>
        <canvas id="vaccinParAnneeChart"></canvas>
    </div>

    <!-- Graphique 2 : Vaccinés par semestre -->
    <div class="mb-5">
        <h4>Vaccinés par semestre - Année {{ $anneeChoisie }}</h4>
        <canvas id="semestreChart"></canvas>
    </div>

    <!-- Graphique 3 : Vaccinés par trimestre -->
    <div class="mb-5">
        <h4>Vaccinés par trimestre - Année {{ $anneeChoisie }}</h4>
        <canvas id="trimestreChart"></canvas>
    </div>

    <!-- Graphique 4 : Suivi de la couverture vaccinale mensuelle -->
    <div class="mb-5">
        <h4>Suivi de la couverture vaccinale mensuelle - Année {{ $anneeChoisie }}</h4>
        <canvas id="couvertureChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
window.onload = function () {
    // Graphique 1: par vaccin / 5 dernières années
    const vaccinParAnneeCtx = document.getElementById('vaccinParAnneeChart').getContext('2d');
    new Chart(vaccinParAnneeCtx, {
        type: 'bar',
         data: {
        labels: @json($annees),
        datasets: [
            @foreach($parVaccinParAnnee as $vaccin => $donnees)
            {
                label: '{{ $vaccin }}',
                data: [
                    @foreach($annees as $annee)
                        {{ $donnees->firstWhere('annee', $annee)->total ?? 0 }},
                    @endforeach
                ],
                borderWidth: 1
            },
            @endforeach
        ]
    },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique 2: par semestre
    const semestreCtx = document.getElementById('semestreChart').getContext('2d');
    new Chart(semestreCtx, {
        type: 'bar',
        data: {
            labels: ['Semestre 1', 'Semestre 2'],
            datasets: [
                @foreach($parSemestre as $vaccin => $semestres)
                {
                    label: '{{ $vaccin }}',
                    data: [
                        {{ $semestres->firstWhere('semestre', 'Semestre 1')->total ?? 0 }},
                        {{ $semestres->firstWhere('semestre', 'Semestre 2')->total ?? 0 }}
                    ]
                },
                @endforeach
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique 3: par trimestre
    const trimestreCtx = document.getElementById('trimestreChart').getContext('2d');
    new Chart(trimestreCtx, {
        type: 'bar',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4'],
            datasets: [
                @foreach($parTrimestre as $vaccin => $trimestres)
                {
                    label: '{{ $vaccin }}',
                    data: [
                        {{ $trimestres->firstWhere('trimestre', 1)->total ?? 0 }},
                        {{ $trimestres->firstWhere('trimestre', 2)->total ?? 0 }},
                        {{ $trimestres->firstWhere('trimestre', 3)->total ?? 0 }},
                        {{ $trimestres->firstWhere('trimestre', 4)->total ?? 0 }}
                    ]
                },
                @endforeach
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique 4: couverture vaccinale mensuelle
    const couvertureCtx = document.getElementById('couvertureChart').getContext('2d');
    new Chart(couvertureCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: '% de couverture vaccinale',
                data: @json(array_values($pourcentageMensuel)),
                fill: false,
                borderColor: 'blue',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
};
</script>
@endsection