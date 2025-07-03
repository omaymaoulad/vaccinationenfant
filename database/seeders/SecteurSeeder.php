<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Secteur;

use function Ramsey\Uuid\v1;

class SecteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Secteur::create(['nom'=>'CSU2 B.Anzarn','zone'=>'urbain','niveau'=>'2']);
        Secteur::create(['nom'=>'CSU1 El Harchi','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSU1  A.Hammou','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSU1 Ajdir','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 A.Y.ou Ali','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 Louta','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Azghar','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Imzouren','zone'=>'DR','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Tamassint','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSU2 B.Bouayach','zone'=>'urbain','niveau'=>'2']);
        Secteur::create(['nom'=>'CSU1 B.Bouayach','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 Nekkour','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 Tifarouine','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'DR Tazourakht','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 A.Taourirt','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Chakran','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 Izammouren','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 A.Kamra','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Tazaghine','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 Talayoussef','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 Rouadi','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'DR Taouassart','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 B.Abdellah','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'DR Bousalah','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 B.Hadifa','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Z.S.Abdelkader','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 B.G.Mestassa','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 B.G.Maksouline','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'DR Ibaranen','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Hamri Omar','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 B.Boufrah','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 Snada','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Agni','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Toufist','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSU1 Targuist','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSU1 Homane El Fatouaki','zone'=>'urbain','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 B.Ammart','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 S.Bouzineb','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 Sidi Boutmime','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 Zerkat','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR1 B.Bechir','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 B.Bounssar','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 B.A.Imougzen','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Issaguen','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 My Ahmed Cherif','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Azila','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 Tlat Ketama','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR1 Tamssaout','zone'=>'rural','niveau'=>'1']);
        Secteur::create(['nom'=>'DR Attout','zone'=>'DR','niveau'=>'1']);
        Secteur::create(['nom'=>'CSR2 Ikkaouen','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 B.Ahmed Ikkaouen','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Tabarant','zone'=>'rural','niveau'=>'2']);
        Secteur::create(['nom'=>'CSR2 Taghzoute','zone'=>'rural','niveau'=>'2']);

    
    }
}
