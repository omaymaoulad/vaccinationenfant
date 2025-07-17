<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Secteur;
use App\Models\EnfantStatistique;
use App\Models\VaccinStatistique;
use Illuminate\Support\Facades\DB;
class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $secteur = $user->secteur;

        // Dernière semaine enregistrée
        $currentSemaine = VaccinStatistique::where('id_secteur', $secteur->id)
                            ->latest('semaine')
                            ->value('semaine');

        // Nombre d’enfants vaccinés cette semaine
        $cetteSemaine = VaccinStatistique::where('id_secteur', $secteur->id)
                            ->where('semaine', $currentSemaine)
                            ->sum('enfants_vaccines');
        $annee = request('annee') ?? date('Y');
        // Historique des 5 dernières semaines
        $historiqueParAge = VaccinStatistique::where('id_secteur', $secteur->id)
                                ->where('annee', $annee)
                                ->select(['mois',
        DB::raw("SUM(CASE WHEN tranche_age = '0-11mois' THEN enfants_vaccines ELSE 0 END) as vaccines_m1"),
        DB::raw("SUM(CASE WHEN tranche_age = '12-59mois' THEN enfants_vaccines ELSE 0 END) as vaccines_12"),
        DB::raw("SUM(CASE WHEN tranche_age = '18mois' THEN enfants_vaccines ELSE 0 END) as vaccines_18"),
        DB::raw("SUM(CASE WHEN tranche_age = '5ans' THEN enfants_vaccines ELSE 0 END) as vaccines_5"),

        DB::raw("SUM(CASE WHEN tranche_age = '0-11mois' THEN enfants_cibles ELSE 0 END) as cibles_m1"),
        DB::raw("SUM(CASE WHEN tranche_age = '12-59mois' THEN enfants_cibles ELSE 0 END) as cibles_12"),
        DB::raw("SUM(CASE WHEN tranche_age = '18mois' THEN enfants_cibles ELSE 0 END) as cibles_18"),
        DB::raw("SUM(CASE WHEN tranche_age = '5ans' THEN enfants_cibles ELSE 0 END) as cibles_5"),
    ])
        ->groupBy('mois')
        ->orderByDesc('mois')
        ->get();

        $annee = request('annee') ?? date('Y');

        $anneesDisponibles = EnfantStatistique::where('id_secteur', $secteur->id)
                        ->distinct()
                        ->orderByDesc('annee')
                        ->pluck('annee');
    $enfantsStats = EnfantStatistique::where('id_secteur', $secteur->id)
                    ->where('annee', $annee)
                    ->selectRaw('SUM(nb_moins_1_an) as moins1, SUM(nb_18_mois) as mois18, SUM(nb_12_mois) as mois12, SUM(nb_5_ans) as ans5, SUM(naissances_attendues) as nes')
                    ->first();

    return view('user.dashboard', compact(
        'user',
        'secteur',
        'cetteSemaine',
        'currentSemaine',
        'historiqueParAge',
        'annee',
        'enfantsStats',
        'anneesDisponibles'
    ));
    }
    public function getStatsByAnnee($annee)
{
    $user = Auth::user();
    $secteur = $user->secteur;

    $stats = EnfantStatistique::where('id_secteur', $secteur->id)
        ->where('annee', $annee)
        ->selectRaw("SUM(nb_moins_1_an) as moins1,
            SUM(nb_12_mois) as mois12,
            SUM(nb_18_mois) as mois18,
            SUM(nb_5_ans) as ans5,
            SUM(naissances_attendues) as naissances")
        ->first();

    return response()->json([
        'moins1' => $stats->moins1 ?? 0,
        'mois12' => $stats->mois12 ?? 0,
        'mois18' => $stats->mois18 ?? 0,
        'ans5' => $stats->ans5 ?? 0,
        'naissances' => $stats->naissances ?? 0,
    ]);
}
}
