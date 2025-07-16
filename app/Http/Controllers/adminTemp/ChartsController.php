<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaccinStatistique;
use App\Models\EnfantStatistique;
use Illuminate\Support\Facades\DB;
class ChartsController extends Controller
{
    public function index(Request $request){
        $anneeChoisie = $request ->input('annee',date('Y'));
        $dernieresAnnees = range(date('Y'), date('Y')+ 5);
        //Vaccinés vaccin par année:
        $data = VaccinStatistique::selectRaw('annee, nom_vaccin, SUM(enfants_vaccines) as total_vaccines')
        ->whereIn('annee', $dernieresAnnees)
        ->groupBy('annee', 'nom_vaccin')
        ->orderBy('annee', 'asc')
        ->get();

// 2. Calculer les totaux par année
$totauxParAnnee = EnfantStatistique::select('annee', DB::raw('SUM(nb_moins_1_an + nb_18_mois + nb_12_mois + nb_5_ans) as total_enfants'))
        ->whereIn('annee', $dernieresAnnees)
        ->groupBy('annee')
        ->pluck('total_enfants', 'annee');

// 3. Calculer les pourcentages
$parVaccinParAnnee = $data->map(function ($item) use ($totauxParAnnee) {
        $item->pourcentage = $totauxParAnnee[$item->annee] > 0 
            ? ($item->total_vaccines / $totauxParAnnee[$item->annee]) * 100 
            : 0;
        return $item;
    })->groupBy('nom_vaccin');
        //Par semestre:
        $parSemestre = VaccinStatistique::selectRaw("
                nom_vaccin,
                CASE
                    WHEN semaine <= 26 THEN 'Semestre 1'
                    ELSE 'Semestre 2'
                END AS semestre,
                SUM(enfants_vaccines) AS total
            ")
            ->where('annee' , $anneeChoisie)
            ->groupBy('nom_vaccin', 'semestre')
            ->get()
            ->groupBy('nom_vaccin');
        //par trimestre
        $parTrimestre = VaccinStatistique::selectRaw("
                nom_vaccin,
                FLOOR((semaine-1)/13)+1 as trimestre,
                SUM(enfants_vaccines) as total
            ")
            ->where('annee' , $anneeChoisie)
            ->groupBy('nom_vaccin', 'trimestre')
            ->get()
            ->groupBy('nom_vaccin');
        //mensuelle
       // Cible totale annuelle
    $totalCibles = EnfantStatistique::where('annee', $anneeChoisie)
        ->sum(DB::raw('COALESCE(nb_moins_1_an, 0) + COALESCE(nb_12_mois, 0) + COALESCE(nb_18_mois, 0) + COALESCE(nb_5_ans, 0)'));

    // Cible cumulée par mois
    $cibleCumul = [];
    for ($m = 1; $m <= 12; $m++) {
        $cibleCumul[] = round(($totalCibles / 12) * $m);
    }

    // Vaccins à analyser
    $vaccins = ['Penta1', 'Penta3', 'RR 9mois', 'RR 18mois'];
    $dataCumul = [];
    $mensuelVaccins = [];

    foreach ($vaccins as $vaccin) {
        // Regrouper les vaccinations par mois
        $mensuel = VaccinStatistique::selectRaw("CEIL(semaine / 4) as mois, SUM(enfants_vaccines) as total")
            ->where('nom_vaccin', $vaccin)
            ->where('annee', $anneeChoisie)
            ->groupBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        // Générer les cumul mensuel
        $cumul = 0;
        for ($m = 1; $m <= 12; $m++) {
            $mensuelVaccins[$vaccin][$m] = $mensuel[$m] ?? 0;
            $cumul += $mensuelVaccins[$vaccin][$m];
            $dataCumul[$vaccin][] = $cumul;
        }
    }

    // Calcul des abandons
    $totalPenta1 = array_sum($mensuelVaccins['Penta1']);
    $totalPenta3 = array_sum($mensuelVaccins['Penta3']);
    $totalRR9 = array_sum($mensuelVaccins['RR 9mois']);
    $totalRR18 = array_sum($mensuelVaccins['RR 18mois']);

    $abandonP1P3 = $totalPenta1 - $totalPenta3;
    $tauxAbandonP1P3 = $totalPenta1 > 0 ? round(($abandonP1P3 / $totalPenta1) * 100, 2) : 0;
    $abandonP1RR9 = $totalPenta1 - $totalRR9;
    $tauxAbandonP1RR9 = $totalPenta1 > 0 ? round(($abandonP1RR9 / $totalPenta1) * 100, 2) : 0;
    $abandonP1RR18 = $totalPenta1 - $totalRR18;
    $tauxAbandonP1RR18 = $totalPenta1 > 0 ? round(($abandonP1RR18 / $totalPenta1) * 100, 2) : 0;

        return view('admin.stats.charts',[
            'anneeChoisie'=> $anneeChoisie,
            'annees' => $dernieresAnnees,
            'parVaccinParAnnee' => $parVaccinParAnnee,
            'parSemestre' => $parSemestre,
            'parTrimestre' =>$parTrimestre,
            'totalCibles' => $totalCibles,
            'cibleCumul' => $cibleCumul,
            'dataCumul' => $dataCumul,
            'mensuelVaccins' => $mensuelVaccins,
            'abandonP1P3' => $abandonP1P3,
            'tauxAbandonP1P3' => $tauxAbandonP1P3,
            'abandonP1RR9' => $abandonP1RR9,
            'tauxAbandonP1RR9' => $tauxAbandonP1RR9,
            'abandonP1RR18' => $abandonP1RR18,
            'tauxAbandonP1RR18' => $tauxAbandonP1RR18
        ]);
    }
}
