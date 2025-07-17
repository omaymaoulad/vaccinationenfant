@extends('layouts.user')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header" style="background-color: #501d80; color: white;">
            <h4 class="mb-0">Ajouter les données de vaccination</h4>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.vaccins.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Année :</label>
                        <select name="annee" class="form-select" required>
                            @foreach($annees as $a)
                                <option value="{{ $a }}">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Mode de saisie :</label>
                        <select name="mode" id="mode-select" class="form-select" required onchange="toggleModeFields()">
                            <option value="semaine" selected>Saisie par semaine</option>
                            <option value="mois">Saisie par mois</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="semaine-field">
                        <label class="form-label">Semaine :</label>
                        <select name="semaine" id="semaine" class="form-select">
                            @for($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}">Semaine {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4" id="mois-field" style="display: none;">
                        <label class="form-label">Mois :</label>
                        <select name="mois" id="mois" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">Mois {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Champ pour enfants nés protégés --}}
                <div class="mb-4">
                    <label class="form-label">Nombre d'enfants nés protégés :</label>
                    <input type="number" name="enfants_nes" class="form-control" placeholder="Saisir le nombre" min="0" required>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="30%">Vaccin</th>
                                <th width="30%">Tranche d'âge</th>
                                <th width="40%">Nombre d'enfants vaccinés</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Affichage de toutes les tranches pour tous les vaccins --}}
                            @foreach($vaccins as $vaccin => $tranches)
                                @php $rowspan = count($tranches); @endphp
                                @foreach($tranches as $index => $tranche)
                                    <tr>
                                        @if($index === 0)
                                            <td rowspan="{{ $rowspan }}"><strong>{{ $vaccin }}</strong></td>
                                        @endif
                                        <td>{{ $tranche }}</td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="data[{{ $vaccin }}][{{ $tranche }}]" placeholder="Saisir le nombre">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function toggleModeFields() {
        const mode = document.getElementById('mode-select').value;

        const semaineField = document.getElementById('semaine-field');
        const moisField = document.getElementById('mois-field');

        const semaineInput = document.querySelector('[name="semaine"]');
        const moisInput = document.querySelector('[name="mois"]');

        if (mode === 'semaine') {
            semaineField.style.display = 'block';
            semaineInput.disabled = false;

            moisField.style.display = 'none';
            moisInput.disabled = true;
        } else if (mode === 'mois') {
            moisField.style.display = 'block';
            moisInput.disabled = false;

            semaineField.style.display = 'none';
            semaineInput.disabled = true;
        }
    }

    // Appelle la fonction au chargement pour initialiser correctement les champs
    document.addEventListener('DOMContentLoaded', toggleModeFields);
</script>

@endsection
