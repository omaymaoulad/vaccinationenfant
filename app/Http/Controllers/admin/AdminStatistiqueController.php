<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaccinStatistique;
use App\Models\EnfantStatistique;
use App\Models\Secteur;

class AdminStatistiqueController extends Controller
{
    public function index(Request $request)
    {
    $annee = $request->input('annee', date('Y'));
    $secteur_id = $request->input('secteur');
    $mode = $request->input('mode', 'semaine');

    $secteurs = Secteur::all();

    $query = VaccinStatistique::query()->where('annee', $annee);
    if ($secteur_id) {
        $query->where('id_secteur', $secteur_id);
    }

    $data = $query->get();
    $colonnes = $mode === 'mois' ? range(1, 12) : range(1, 5);
    $records = [];
    foreach ($data as $row) {
    $vaccin = $row->nom_vaccin;
    $tranche = $row->tranche_age;
    $cleCol = $mode === 'mois' ? ceil($row->semaine / 4) : $row->semaine;

    if (!isset($records[$vaccin][$tranche])) {
        $records[$vaccin][$tranche] = array_fill_keys($colonnes, ['vaccines' => 0, 'cibles' => 0]);
    }

    if (isset($records[$vaccin][$tranche][$cleCol])) {
        $records[$vaccin][$tranche][$cleCol]['vaccines'] += $row->enfants_vaccines;
        $records[$vaccin][$tranche][$cleCol]['cibles'] += $row->enfants_cibles;
    }
}


foreach ($records as $vaccin => &$tranches) {
    if ($vaccin === 'Rappel 2') {
        $tranches = array_filter($tranches, fn($key) => in_array($key, ['5ans', '5 ans']), ARRAY_FILTER_USE_KEY);
    } elseif ($vaccin === 'Rappel 1') {
        $tranches = array_filter($tranches, fn($key) => in_array($key, ['18 mois', '18mois']), ARRAY_FILTER_USE_KEY);
    } else {
        $tranches = array_filter($tranches, fn($key) => in_array($key, ['0-11mois', '0-11 mois', '12-59mois', '12-59 mois']), ARRAY_FILTER_USE_KEY);
    }
}

    
    $ciblesParTranche = [
        '0-11mois' => 0,
        '12-59mois' => 0,
        '18 mois' => 0,
        '5 ans' => 0,
    ];

    if ($secteur_id) {
        $stat = EnfantStatistique::where('annee', $annee)->where('id_secteur', $secteur_id)->first();
        if ($stat) {
            $ciblesParTranche = [
                '0-11mois' => $stat->nb_moins_1_an,
                '12-59mois' => $stat->nb_12_mois,
                '18 mois' => $stat->nb_18_mois,
                '5 ans' => $stat->nb_5_ans,
            ];
        }
    }

    return view('admin.stats.globales', compact('records', 'mode', 'secteurs', 'secteur_id', 'annee', 'colonnes', 'ciblesParTranche'));
}
}