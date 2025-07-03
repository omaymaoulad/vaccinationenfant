<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Secteur extends Model
{
    use HasFactory;
    protected $table = 'secteurs';
    protected $fillable = ['nom','zone','niveau'];
    
    public function enfantsStatistiques(){
        return $this->hasMany(EnfantStatistique::class, 'id_secteur');
    }

    public function vaccinstatiques(){
        return $this->hasMany(VaccinStatistique::class, 'id_secteur');
    }
}
