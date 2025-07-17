@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4 ">📊 Statistiques de vaccination</h2>
    <form method="GET" class="row mb-2">
        <div class="col-md-8">
            <label for="annee">Année</label>
            <select name="annee" id="annee" class="form-control">
                @foreach(range(date('Y'), date('Y') + 5) as $year)
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
        <h4>Vaccinés par vaccin (5 années)</h4>
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
    <table class="table table-bordered table-striped table-hover w-max mt-3 border border-gray-300  text-center  rounded ">
        <thead class="table-primary font-bold ">
            <tr>
                <th class ="px-3 py-2">Vaccin</th>
                @for ($m = 1; $m <= 12; $m++)
                    @php $moisNom = DateTime::createFromFormat('!m', $m)->format('M'); @endphp
                    <th class="px-3 py-2">{{ $moisNom }}  </th>
                    <th class="px-3 py-2">Cumul   </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach (['Penta1', 'Penta3', 'RR 9mois', 'RR 18mois'] as $vaccin)
                <tr class="hover:bg-gray-50">
                    <td class="font-medium bg-gray-50 px-2 py-1">{{ $vaccin }}  </td>
                    @php $cumul = 0; @endphp
                    @for ($m = 1; $m <= 12; $m++)
                        @php
                            $mensuel = $mensuelVaccins[$vaccin][$m] ?? 0;
                            $cumul += $mensuel;
                        @endphp
                        <td class="px-2 py-1">{{ $mensuel }}</td>
                        <td class="px-2 py-1 font-semibold">{{ $cumul }}</td>
                    @endfor
                </tr>
            @endforeach

            <!-- Ligne abandon Penta1 → Penta3 -->
            <tr class="bg-red-50 font-semibold">
                <td class="text-left px-2 py-1">Abandon P1 → P3</td>
                @php $cumulP1 = $cumulP3 = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $p3 = $mensuelVaccins['Penta3'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulP3 += $p3;
                        $abandon = $cumulP1 - $cumulP3;
                    @endphp
                    <td> -</td>
                    <td>{{ $abandon }}</td>
                @endfor
            </tr>

            <!-- Ligne taux abandon Penta1 → Penta3 -->
            <tr class="bg-red-100 text-sm italic">
                <td class="text-left px-2 py-1">Taux Abandon P1 → P3</td>
                @php $cumulP1 = $cumulP3 = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $p3 = $mensuelVaccins['Penta3'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulP3 += $p3;
                        $taux = $cumulP1 > 0 ? round((($cumulP1 - $cumulP3) / $cumulP1) * 100, 1) : 0;
                    @endphp
                    <td>-</td>
                    <td>{{ $taux }}%</td>
                @endfor
            </tr>

            <!-- Ligne abandon Penta1 → RR9 -->
            <tr class="table-yellow-50 font-semibold">
                <td class="text-left px-2 py-1">Abandon P1 → RR 9mois</td>
                @php $cumulP1 = $cumulRR = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $rr = $mensuelVaccins['RR 9mois'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulRR += $rr;
                        $abandon = $cumulP1 - $cumulRR;
                    @endphp
                    <td>-</td>
                    <td>{{ $abandon }}</td>
                @endfor
            </tr>

            <!-- Ligne taux abandon Penta1 → RR 9mois -->
            <tr class="bg-yellow-100 text-sm italic">
                <td class="text-left px-2 py-1">Taux Abandon P1 → RR</td>
                @php $cumulP1 = $cumulRR = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $rr = $mensuelVaccins['RR 9mois'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulRR += $rr;
                        $taux = $cumulP1 > 0 ? round((($cumulP1 - $cumulRR) / $cumulP1) * 100, 1) : 0;
                    @endphp
                    <td>-</td>
                    <td>{{ $taux }}%</td>
                @endfor
            </tr>
            <!-- Ligne abandon Penta1 → RR18 -->
            <tr class="table-yellow-50 font-semibold">
                <td class="text-left px-2 py-1">Abandon P1 → RR 9mois</td>
                @php $cumulP1 = $cumulRR18 = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $rr1 = $mensuelVaccins['RR 18mois'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulRR18 += $rr1;
                        $abandon = $cumulP1 - $cumulRR18;
                    @endphp
                    <td>-</td>
                    <td>{{ $abandon }}</td>
                @endfor
            </tr>
            <!-- Ligne taux abandon Penta1 → RR 18mois -->
            <tr class="bg-yellow-100 text-sm italic">
                <td class="text-left px-2 py-1">Taux Abandon P1 → RR 18mois</td>
                @php $cumulP1 = $cumulRR18 = 0; @endphp
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $p1 = $mensuelVaccins['Penta1'][$m] ?? 0;
                        $rr1 = $mensuelVaccins['RR 18mois'][$m] ?? 0;
                        $cumulP1 += $p1;
                        $cumulRR18 += $rr1;
                        $taux = $cumulP1 > 0 ? round((($cumulP1 - $cumulRR18) / $cumulP1) * 100, 1) : 0;
                    @endphp
                    <td>-</td>
                    <td>{{ $taux }}%</td>
                @endfor
            </tr>
        </tbody>
    </table>
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
                        @php
                            $data = $donnees->firstWhere('annee', $annee);
                            $value = $data ? $data->pourcentage : 0;
                        @endphp
                        {{ $value }},
                    @endforeach
                ],
                
                borderWidth: 1
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Pourcentage de vaccination par année'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw.toFixed(2) + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Pourcentage (%)'
                },
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Années'
                }
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
            datasets: [
                {
                    label: 'Cible cumulée estimée',
                    data: @json($cibleCumul),
                    borderColor: 'black',
                    borderWidth: 2,
                    fill: false,
                    pointStyle: 'rectRot',
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Penta1',
                    data: @json($dataCumul['Penta1'] ?? []),
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Penta3',
                    data: @json($dataCumul['Penta3'] ?? []),
                    borderColor: 'red',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'RR 9mois',
                    data: @json($dataCumul['RR 9mois'] ?? []),
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                   label: 'RR 18mois',
                    data: @json($dataCumul['RR 18mois'] ?? []),
                    borderColor: 'yellow',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    yAxisID: 'y' 
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    min: 0,
                    max: {{$totalCibles}},
                    title: {
                        display: true,
                        text: 'Population cible'
                    },
                    ticks: {
                        stepSize:Math.ceil({{$totalCibles}} /12)
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Pourcentage (%)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
};
</script>
@endsection