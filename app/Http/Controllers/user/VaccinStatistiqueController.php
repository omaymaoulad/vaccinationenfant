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
        $enfantsNes = $request->enfants_nés ?? [];
        $vaccins = ['Hep.B','BCG','VPI', 'Penta1', 'Penta2', 'Penta3', 'Rotavirus 3 doses','VPC13', 'RR','Rappel 1','Rappel 2']; // Ajoute les vrais noms
        $tranches = ['0-11mois', '12-59mois','18mois' ,'5ans'];
        $annees = range(date('Y'), date('Y') + 5);

        return view('user.vaccins.create', compact('vaccins', 'tranches', 'annees', 'secteur'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $secteurId = $user->secteur->id;
        $annee = $request->annee;
        $semaine = $request->semaine;

        foreach ($request->data as $vaccin => $tranches) {
            foreach ($tranches as $tranche => $vaccines) {
                // Vérifier duplication
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

                $cibles = 0;
                if ($stats) {
                    $colMap = ['0-11mois' => 'nb_moins_1_an','12-59mois' => 'nb_12_mois', '18mois' => 'nb_18_mois', '5ans' => 'nb_5_ans'];
                    $cibles = $stats->{$colMap[$tranche]} ?? 0;
                }
                $nes = $enfantsNes[$vaccin] ?? null;
                VaccinStatistique::create([
                    'id_secteur' => $secteurId,
                    'annee' => $annee,
                    'semaine' => $semaine,
                    'nom_vaccin' => $vaccin,
                    'tranche_age' => $tranche,
                    'enfants_cibles' => $cibles,
                    'enfants_vaccines' => $vaccines,
                    'enfants_nes' => $nes,
                    'pourcentage_vaccination' => $cibles > 0 ? round(($vaccines / $cibles) * 100, 2) : 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Données enregistrées avec succès.');
    }
}
