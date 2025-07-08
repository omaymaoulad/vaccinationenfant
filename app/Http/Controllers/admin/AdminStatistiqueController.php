<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaccinStatistique;
use App\Models\Secteur;
class AdminStatistiqueController extends Controller
{
    public function index(Request $request){
        $periode= $request->input('periode','mois');
        $data = VaccinStatistique::with('secteur')
            ->selectRaw('id_secteur,semaine,nom_vaccin,tranche_age,SUM(enfant_vaccines) as total_vaccines,SUM(enfants_cibles) as total_cibles')
            ->groupBy('id_secteur','semaine','nom_vaccin','tranche_age')
            ->orderBy('id_secteur')
            ->orderBy('nom_vaccin')
            ->orderBy('tranche_age')
            ->orderBy('semaine')
            ->get();
        return view('admin.stats.globales', compact('data','periode'));
    }
}
