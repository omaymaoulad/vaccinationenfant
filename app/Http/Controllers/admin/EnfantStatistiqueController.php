<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnfantStatistique;
use App\Models\Secteur;
use Illuminate\Http\Request;

class EnfantStatistiqueController extends Controller
{
     public function create()
    {
        $secteurs = Secteur::all();
        return view('admin.enfants_statistiques.create', compact('secteurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_secteur' => 'required|exists:secteurs,id',
            'annee' => 'required|integer',
            'nb_moins_1_an' => 'required|integer',
            'nb_18_mois' => 'required|integer',
            'nb_5_ans' => 'required|integer',
        ]);
         $exists = EnfantStatistique::where('id_secteur', $request->id_secteur)
        ->where('annee', $request->annee)
        ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'duplicate' => 'Les données pour ce secteur et cette année existent déjà.',
        ]);
    }
        EnfantStatistique::create($request->all());

        return redirect()->back()->with('success', 'Données enregistrées avec succès.');
    }
}

