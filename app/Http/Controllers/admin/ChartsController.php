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
        $parVaccinParAnnee = VaccinStatistique::selectRaw('annee, nom_vaccin, SUM(enfants_vaccines) as total')
            ->whereIn('annee', $dernieresAnnees)
            ->groupBy('annee', 'nom_vaccin')
            ->orderBy('annee', 'asc')
            ->get()
            ->groupBy('nom_vaccin');
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
        $totalCibles = EnfantStatistique::where('annee',$anneeChoisie)
            ->sum(DB::raw('nb_moins_1_an','nb_18_mois','nb_12_mois','nb_5_ans'));
        $cibleCumul = [];
        $vaccins = ['Penta1','Penta3','RR'];
        $dataCumul = [];
        for($m = 1 ; $m <= 12 ;$m++){
            $cibleCumul[] = round(($totalCibles /12)* $m);
        }
        foreach($vaccins as $vaccin){
            $mensuel= VaccinStatistique::selectRaw("CEIL(semaine / 4) as mois, SUM(enfants_vaccines) as total")
                ->where('nom_vaccin',$vaccin)
                ->where('annee',$anneeChoisie)
                ->groupBy('mois')
                ->pluck('total','mois');
            $cumul=0;
            for($m =1; $m<= 12 ; $m++){
                $cumul += $mensuel[$m] ?? 0;
                $dataCumul[$vaccin][] = $cumul;
            }
        }

        return view('admin.stats.charts',[
            'anneeChoisie'=> $anneeChoisie,
            'annees' => $dernieresAnnees,
            'parVaccinParAnnee' => $parVaccinParAnnee,
            'parSemestre' => $parSemestre,
            'parTrimestre' =>$parTrimestre,
            'cibleCumul' => $cibleCumul,
            'dataCumul' =>$dataCumul,
            'totalCibles' => $totalCibles  
        ]);
    }
}
