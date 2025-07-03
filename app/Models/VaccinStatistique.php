<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class VaccinStatistique extends Model
{
    use HasFactory;
    protected $table = 'vaccinsstatiques';
    protected $fillable= ['id_secteur','annee','semaine','nom_vaccin','tranche_age','enfants_cibles','enfants_vaccines','pourcentage_vaccination','enfants_non_vaccines'];
 
    public function secteur(){
        return $this->belongsTo(Secteur::class, 'id_secteur');
    }
}
