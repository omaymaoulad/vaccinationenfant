<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EnfantStatistique;
use App\Models\VaccinStatistique;

class VaccinStatistiqueController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $secteur = $user->secteur;
        $vaccins = [
        'Hep.B' => ['Fait dans les 24 heures', 'Fait avec le BCG'],
        'BCG' => ['0-11mois', '12-59mois'],
        'Penta1' => ['0-11mois', '12-59mois'],
        'Penta3' => ['0-11mois', '12-59mois'],
        'VPI 1er prise' => ['0-11mois', '12-59mois'],
        'VPI 2eme prise' => ['0-11mois', '12-59mois'],
        'Rotavirus  3 doses 1er prise' => ['0-11mois', '12-59mois'],
        'Rotavirus  3 doses 3eme prise' => ['0-11mois', '12-59mois'],
        'VPC13 1er prise' => ['0-11mois', '12-59mois'],
        'VPC13 3eme prise' => ['0-11mois', '12-59mois'],
        'RR 9mois' => ['0-11mois', '12-59mois'],
        'RR 18mois' =>['18mois'],
        'Rappel 1' => ['18mois'],
        'Rappel 2' => ['5ans'],
    ];

        $annees = range(date('Y'), date('Y') + 5);

        return view('user.vaccins.create', compact('vaccins', 'annees', 'secteur'));
    }

    public function duplicateLastEntry(Request $request)
    {
    $user = Auth::user();
    $secteurId = $user->secteur->id;

    $mode = $request->input('mode');
    $annee = $request->input('annee', date('Y'));
    $semaine = $request->input('semaine');
    $mois = $request->input('mois');
    $query = \App\Models\VaccinStatistique::where('id_secteur', $secteurId)
        ->where('annee', $annee);

    if ($mode === 'semaine' && is_numeric($semaine)) {
        $query->whereNotNull('semaine')->where('semaine', '<=', $semaine)->orderByDesc('semaine');
    } elseif ($mode === 'mois' && is_numeric($mois)) {
        $query->whereNotNull('mois')->where('mois', '<=', $mois)->orderByDesc('mois');
    } else {
        return redirect()->back()->withErrors(['duplicate' => 'Aucune donnée précédente trouvée à dupliquer.']);
    }
    $lastEntries = $query->orderByDesc('id')->get();
    if ($lastEntries->isEmpty()) {
    return redirect()->back()
        ->withErrors(['duplicate' => 'Aucune donnée précédente trouvée à dupliquer.'])
        ->withInput();
    }
    $data = [];
    foreach ($lastEntries as $entry) {
        $data[$entry->nom_vaccin][$entry->tranche_age] = $entry->enfants_vaccines;
    }

    $vaccins = [
        'Hep.B' => ['Fait dans les 24 heures', 'Fait avec le BCG'],
        'BCG' => ['0-11mois', '12-59mois'],
        'Penta1' => ['0-11mois', '12-59mois'],
        'Penta3' => ['0-11mois', '12-59mois'],
        'VPI 1er prise' => ['0-11mois', '12-59mois'],
        'VPI 2eme prise' => ['0-11mois', '12-59mois'],
        'Rotavirus  3 doses 1er prise' => ['0-11mois', '12-59mois'],
        'Rotavirus  3 doses 3eme prise' => ['0-11mois', '12-59mois'],
        'VPC13 1er prise' => ['0-11mois', '12-59mois'],
        'VPC13 3eme prise' => ['0-11mois', '12-59mois'],
        'RR 9mois' => ['0-11mois', '12-59mois'],
        'RR 18mois' =>['18mois'],
        'Rappel 1' => ['18mois'],
        'Rappel 2' => ['5ans'],
    ];
    $annees = range(date('Y'), date('Y') + 5);
    $secteur = $user->secteur;

    return view('user.vaccins.create', compact('vaccins', 'annees', 'secteur', 'data', 'mode', 'semaine', 'mois'));
    }


    public function store(Request $request)
{
    $request->validate([
        'mode' => 'required|in:semaine,mois',
        'semaine' => 'required_if:mode,semaine|nullable|integer|min:1|max:52',
        'mois' => 'required_if:mode,mois|nullable|integer|min:1|max:12',
        'annee' => 'required|integer',
        'enfants_nes' => 'nullable|integer|min:0',
    ]);

    $user = Auth::user();
    $secteurId = $user->secteur->id;
    $annee = $request->annee;
    $mode = $request->mode;
    $semaine = $mode === 'semaine' ? $request->semaine : null;
    $mois = $mode === 'mois' ? $request->mois : null;
    $enfantsNes = $request->input('enfants_nes');

    $penta1Total = 0;
    if (!empty($request->data['Penta1'])) {
        foreach ($request->data['Penta1'] as $nb) {
            $penta1Total += (int)$nb;
        }
    }

    $totalHepB = 0;
    if (!empty($request->data['Hep.B'])) {
        foreach ($request->data['Hep.B'] as $tranche => $nombre) {
            if ($nombre === null || $nombre === '') continue;
            $totalHepB += (int)$nombre;
        }
    }

    if ($enfantsNes !== null && $enfantsNes > $penta1Total) {
        return redirect()->back()->withErrors([
            'enfants_nes' => "Le nombre d'enfants nés protégés ne peut pas être supérieur au nombre de vaccinés par Penta1.",
        ]);
    }

    // 🔹 Enregistrement des données Hep.B
    if (!empty($request->data['Hep.B'])) {
        foreach ($request->data['Hep.B'] as $tranche => $nombre) {
            if ($nombre === null || $nombre === '') continue;

            VaccinStatistique::create([
                'id_secteur' => $secteurId,
                'annee' => $annee,
                'semaine' => $semaine,
                'mois' => $mois,
                'nom_vaccin' => 'Hep.B',
                'tranche_age' => $tranche,
                'enfants_cibles' => 0,
                'enfants_vaccines' => $nombre,
                'enfants_nes' => null,
                'pourcentage_vaccination' => 0,
            ]);
        }
    }

    // 🔹 Enregistrement des autres vaccins
    foreach ($request->data as $vaccin => $tranches) {
        if ($vaccin == 'Hep.B') continue;

        foreach ($tranches as $tranche => $vaccines) {
            if ($vaccines === null || $vaccines === '') continue;

            $stats = EnfantStatistique::where([
                'id_secteur' => $secteurId,
                'annee' => $annee,
            ])->first();

            $colMap = [
                '0-11mois' => 'nb_moins_1_an',
                '12-59mois' => 'nb_12_mois',
                '18mois' => 'nb_18_mois',
                '5ans' => 'nb_5_ans'
            ];
            $cibles = ($stats && isset($colMap[$tranche])) ? ($stats->{$colMap[$tranche]} ?? 0) : 0;

            VaccinStatistique::create([
                'id_secteur' => $secteurId,
                'annee' => $annee,
                'semaine' => $semaine,
                'mois' => $mois,
                'nom_vaccin' => $vaccin,
                'tranche_age' => $tranche,
                'enfants_cibles' => $cibles,
                'enfants_vaccines' => $vaccines,
                'enfants_nes' => $enfantsNes,
                'pourcentage_vaccination' => $cibles > 0 ? round(($vaccines / $cibles) * 100, 2) : 0,
            ]);
        }
    }

    return redirect()->back()->with('success', 'Données enregistrées avec succès.');
}

}