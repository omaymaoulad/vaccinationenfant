<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Secteur;
use App\Models\EnfantStatistique;
use App\Models\VaccinStatistique;

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

        // Historique des 5 dernières semaines
        $historique = VaccinStatistique::where('id_secteur', $secteur->id)
                            ->orderByDesc('semaine')
                            ->take(5)
                            ->get();

        $annee = request('annee') ?? date('Y');

        $anneesDisponibles = EnfantStatistique::where('id_secteur', $secteur->id)
                        ->distinct()
                        ->orderByDesc('annee')
                        ->pluck('annee');
    $enfantsStats = EnfantStatistique::where('id_secteur', $secteur->id)
                    ->where('annee', $annee)
                    ->selectRaw('SUM(nb_moins_1_an) as moins1, SUM(nb_18_mois) as mois18, SUM(nb_5_ans) as ans5')
                    ->first();

    return view('user.dashboard', compact(
        'user',
        'secteur',
        'cetteSemaine',
        'currentSemaine',
        'historique',
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
        ->selectRaw('SUM(nb_moins_1_an) as moins1, SUM(nb_18_mois) as mois18, SUM(nb_5_ans) as ans5')
        ->first();

    return response()->json([
        'moins1' => $stats->moins1 ?? 0,
        'mois18' => $stats->mois18 ?? 0,
        'ans5' => $stats->ans5 ?? 0,
    ]);
}
}
