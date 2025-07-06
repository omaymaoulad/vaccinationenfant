@extends('layouts.app')

@section('content')
<style>
.card-animated {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.card-animated:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}
</style>
<div class="container">
    <h2>Bienvenue {{ $user->name }}</h2>

    <div class="row">
        <!-- Carte du secteur -->
        <div class="col-md-4 mb-3">
            <div class="card card-animated text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Secteur concerné</h5>
                    <h2 class="card-text">{{ $secteur->nom }}</h2>
                </div>
            </div>
        </div>

        <!-- Carte des vaccinations de la semaine -->
        <div class="col-md-4 mb-3">
            <div class="card card-animated text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Vaccinés cette semaine ({{ $currentSemaine }})</h5>
                    <p class="card-text">{{ $cetteSemaine }}</p>
                </div>
            </div>
        </div>

        <!-- Carte bouton ajouter -->
        <div class="col-md-4 mb-3">
            <div class="card card-animated text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Ajouter des données</h5>
                    <a href="#" class="btn btn-light">Ajouter</a>
                </div>
            </div>
        </div>
    </div>
    <form method="GET" action="{{ route('dashboard.user') }}" class="mb-3">
    <div class="form-group">
        <label for="annee">Choisir une année :</label>
        <select name="annee" id="annee" class="form-control w-25">
            @for ($y = 2020; $y <= date('Y'); $y++)
                <option value="{{ $y }}" {{ $y == $annee ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Afficher</button>
</form>

<div class="row" id="ageCards">
    <div class="col-md-4">
        <div class="card card-animated bg-secondary text-dark">
            <div class="card-body text-center">
                <h5 class="card-title">Moins de 1 an</h5>
                <h3 class="card-text"><strong id="moins1">{{ $enfantsStats->moins1 ?? 0 }}</strong> enfants</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-animated bg-secondary text-dark">
            <div class="card-body text-center">
                <h5 class="card-title">18 mois</h5>
                <h3 class="card-text"><strong id="mois18">{{ $enfantsStats->mois18 ?? 0 }}</strong> enfants</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-animated bg-secondary text-dark">
            <div class="card-body text-center">
                <h5 class="card-title">5 ans</h5>
                <h3 class="card-text"><strong id="ans5">{{ $enfantsStats->ans5 ?? 0 }}</strong> enfants</h3>
            </div>
        </div>
    </div>
  </div>
</div>
    <!-- Historique -->
    <div class="card mt-4 card-animated">
        <div class="card-header">Historique des 5 dernières semaines</div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Semaine</th>
                        <th>Vaccinés</th>
                        <th>Cibles</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historique as $item)
                        <tr>
                            <td>{{ $item->semaine }}</td>
                            <td>{{ $item->enfants_vaccines }}</td>
                            <td>{{ $item->enfants_cibles }}</td>
                            <td>
                                @php
                                    $pourcentage = $item->enfants_cibles ? round(($item->enfants_vaccines / $item->enfants_cibles) * 100, 2) : 0;
                                @endphp
                                {{ $pourcentage }} %
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cards = document.querySelectorAll('.card-animated');
        cards.forEach((card, index) => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = 1;
                card.style.transform = 'translateY(0)';
            }, index * 150); // décalage progressif
        });
    });
</script>
<script>
document.getElementById('anneeSelect').addEventListener('change', function () {
    const annee = this.value;
    fetch(`/user/enfants-par-age/${annee}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('moins1').innerText = data.moins1 ?? 0;
            document.getElementById('mois18').innerText = data.mois18 ?? 0;
            document.getElementById('ans5').innerText = data.ans5 ?? 0;
        })
        .catch(error => {
            console.error("Erreur lors du chargement des données :", error);
        });
});
</script>


@endsection
