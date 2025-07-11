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

    public function storeMultiple(Request $request)
{
    $annee = $request->input('annee');
    $data = $request->input('data');

    foreach ($data as $row) {
        $exists = EnfantStatistique::where('id_secteur', $row['id_secteur'])
            ->where('annee', $annee)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['duplicate' => 'Des données pour un secteur et une année existent déjà.']);
        }

        EnfantStatistique::create([
            'id_secteur' => $row['id_secteur'],
            'annee' => $annee,
            'naissances_attendues' => $row['naissances_attendues'],
            'nb_moins_1_an' => $row['nb_moins_1_an'],
            'nb_12_mois' => $row['nb_12_mois'],
            'nb_18_mois' => $row['nb_18_mois'],
            'nb_5_ans' => $row['nb_5_ans'],
        ]);
    }

        return redirect()->back()->with('success', 'Données enregistrées avec succès.');
    }
}

