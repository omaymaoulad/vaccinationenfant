<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnfantStatistique extends Model
{
    use HasFactory;
    protected $table ='enfants_statistiques';
    protected $fillable= ['id_secteur',
    'annee',
    'naissances_attendues',
    'nb_moins_1_an',
    'nb_12_mois',
    'nb_18_mois',
    'nb_5_ans'];
    public function secteur(){
        return $this->belongsTo(Secteur::class, 'id_secteur');
    }
}

