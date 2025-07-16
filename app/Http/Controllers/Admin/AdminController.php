<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\EnfantStatistique;
use App\Models\VaccinStatistique;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; // On hérite du Controller principal

class AdminController extends Controller
{
   public function dashboard()
{
    $annee = date('Y');
    $semaine = date('W');

    // Total enfants vaccinés
    $totalVaccines = \App\Models\VaccinStatistique::where('annee', $annee)->sum('enfants_vaccines');
    
    $enfantsData =  \App\Models\EnfantStatistique::selectRaw('
    SUM(nb_moins_1_an) as nb_moins_1_an,
    SUM(nb_12_mois) as nb_12_mois,
    SUM(nb_18_mois) as nb_18_mois,
    SUM(nb_5_ans) as nb_5_ans
')->where('annee', $annee)->first();

   $totalEnfantsCibles = EnfantStatistique::where('annee', $annee)->sum(DB::raw(
    'nb_moins_1_an + nb_12_mois + nb_18_mois + nb_5_ans'));
    // Taux global
    $globalPercentage = $totalEnfantsCibles > 0
        ? ($totalVaccines / $totalEnfantsCibles) * 100
        : 0;

    // Vaccinations cette semaine
    $vaccinationThisWeek = \App\Models\VaccinStatistique::where('annee', $annee)
        ->where('semaine', $semaine)
        ->sum('enfants_vaccines');

    // Secteur le plus performant
    $bestSector = \App\Models\VaccinStatistique::select('id_secteur', DB::raw('AVG(enfants_vaccines) as avg_vaccines'))
        ->where('annee', $annee)
        ->groupBy('id_secteur')
        ->get()
        ->map(function ($item) use ($totalEnfantsCibles) {
            $item->avg_percentage = $totalEnfantsCibles > 0 ? ($item->avg_vaccines / $totalEnfantsCibles) * 100 : 0;
            return $item;
        })->sortByDesc('avg_percentage')->first();

    // Secteurs à surveiller
    $weakSectors = \App\Models\VaccinStatistique::select('id_secteur', DB::raw('AVG(enfants_vaccines) as avg_vaccines'))
        ->where('annee', $annee)
        ->groupBy('id_secteur')
        ->get()
        ->map(function ($item) use ($totalEnfantsCibles) {
            $item->avg_percentage = $totalEnfantsCibles > 0 ? ($item->avg_vaccines / $totalEnfantsCibles) * 100 : 0;
            return $item;
        })->filter(function ($item) {
            return $item->avg_percentage < 50;
        });

    return view('dashboard.admin', [
        'globalPercentage' => $globalPercentage,
        'totalVaccines' => $totalVaccines,
        'totalEnfantsCibles' => $totalEnfantsCibles,
        'vaccinationThisWeek' => $vaccinationThisWeek,
        'bestSector' => $bestSector,
        'weakSectors' => $weakSectors,
        'enfantsData'=>$enfantsData 
    ]);
}
}