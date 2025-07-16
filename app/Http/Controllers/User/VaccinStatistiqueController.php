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

    public function store(Request $request)
    {
    $user = Auth::user();
    $secteurId = $user->secteur->id;
    $annee = $request->annee;
    $semaine = $request->semaine;
    $enfantsNes = $request->input('enfants_nes');
    $penta1Total = 0;
    $totalHepB = 0;
     // Récupérer les données Penta1
        if (!empty($request->data['Penta1'])) {
            foreach ($request->data['Penta1'] as $nb) {
                $penta1Total += (int) $nb;
            }
        }
      // Récupérer les données Hep.B
        if (!empty($request->data['Hep.B'])) {
            foreach ($request->data['Hep.B'] as $tranche => $nombre) {
                if ($nombre === null || $nombre === '') continue;
                $totalHepB += (int)$nombre;
            }
        }

        // Validation: enfants nés protégés >= max(Penta1, Hep.B)
        if ($enfantsNes !== null && $enfantsNes > $penta1Total) {
            return redirect()->back()->withErrors([
                'enfants_nes' => "Le nombre d'enfants nés protégés ne peut pas être supérieur au nombre de vaccinés par Penta1 .",
            ]);
        }
    // Enregistrement spécial pour Hep.B
        if (!empty($request->data['Hep.B'])) {
            foreach ($request->data['Hep.B'] as $tranche => $nombre) {
                if ($nombre === null || $nombre === '') continue;

                VaccinStatistique::create([
                    'id_secteur' => $secteurId,
                    'annee' => $annee,
                    'semaine' => $semaine,
                    'nom_vaccin' => 'Hep.B',
                    'tranche_age' => $tranche,
                    'enfants_cibles' => 0,
                    'enfants_vaccines' => $nombre,
                    'enfants_nes' => null,
                    'pourcentage_vaccination' => 0,
                ]);
            }
        }

    // Enregistrement pour les autres vaccins
    foreach ($request->data as $vaccin => $tranches) {
            if ($vaccin == 'Hep.B') continue; // Déjà traité ci-dessus

            foreach ($tranches as $tranche => $vaccines) {
                if ($vaccines === null || $vaccines === '') continue;

                // Vérifier s'il existe déjà
                $exists = VaccinStatistique::where([
                    'id_secteur' => $secteurId,
                    'annee' => $annee,
                    'semaine' => $semaine,
                    'nom_vaccin' => $vaccin,
                    'tranche_age' => $tranche,
                ])->exists();

                if ($exists) continue;

            // Récupérer les enfants cibles
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