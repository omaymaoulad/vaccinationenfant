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
        $dernieresAnnees = range(date('Y'), date('Y')- 5);
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
        $vaccinesParMois = VaccinStatistique::selectRaw("
                CEIL(semaine /4) as mois,
                SUM(enfants_vaccines) as total_vax
            ")
            ->where('annee' , $anneeChoisie)
            ->groupBy('mois')
            ->pluck('total_vax','mois');
        $cibles = EnfantStatistique::where('annee', $anneeChoisie)->sum(DB::raw('nb_moins_1_an + nb_18_mois + nb_5_ans'));
        $pourcentageMensuel = [];
        foreach(range(1,12) as $mois){
            $vaccines = $vaccinesParMois[$mois] ?? 0 ;
            $pourcentageMensuel[$mois] = $cibles > 0 ? round(($vaccines/$cibles)* 100,2) :0;
        }    
        return view('admin.stats.charts',[
            'anneeChoisie'=> $anneeChoisie,
            'annees' => $dernieresAnnees,
            'parVaccinParAnnee' => $parVaccinParAnnee,
            'parSemestre' => $parSemestre,
            'parTrimestre' =>$parTrimestre,
            'pourcentageMensuel' =>$pourcentageMensuel,
        ]);
    }
}
