<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VaccinStatistique;
use App\Models\EnfantStatistique;
use App\Models\Secteur;

class ZoneStatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $showTotal = $request->has('show_total');

        // Récupération des données pour urbain et rural
        $urbain = $this->getZoneData($annee, 'urbain');
        $rural = $this->getZoneData($annee, 'rural');

        // Calcul du total si demandé
        $total = $showTotal ? $this->calculateTotal($urbain, $rural) : null;

        return view('admin.stats.zones', [
            'annee' => $annee,
            'urbain' => $urbain,
            'rural' => $rural,
            'total' => $total,
            'showTotal' => $showTotal,
            'annees' => range(date('Y'), date('Y') + 5),
        ]);
    }

    protected function getZoneData($year, $zone)
    {
        // Données de vaccination groupées par vaccin + tranche
        $vaccinations = VaccinStatistique::with('secteur')
            ->where('annee', $year)
            ->whereHas('secteur', function ($q) use ($zone) {
                $q->where('zone', $zone);
            })
            ->get()
            ->groupBy(['nom_vaccin', 'tranche_age']);

        // Récupération des cibles
        $cibles = EnfantStatistique::where('annee', $year)
            ->whereHas('secteur', function ($q) use ($zone) {
                $q->where('zone', $zone);
            })
            ->get();

        // Initialisation
        $data = [
            'data' => [],
            'cibles' => [
                '0-11mois' => $cibles->sum('nb_moins_1_an'),
                '12-59mois' => $cibles->sum('nb_12_mois'),
                '18mois' => $cibles->sum('nb_18_mois'),
                '5ans' => $cibles->sum('nb_5_ans')
            ]
        ];

        foreach ($vaccinations as $vaccin => $tranches) {
            foreach ($tranches as $tranche => $items) {
                // Initialisation des mois
                $moisData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $moisData[$i] = [
                        'vaccines' => 0,
                        'cibles' => 0,
                        'non_vaccines' => 0,
                        'pourcentage' => 0
                    ];
                }

                // Données mensuelles
                foreach ($items as $item) {
                    $mois = $item->mois;
                    if ($mois >= 1 && $mois <= 12) {
                        $moisData[$mois]['vaccines'] += $item->enfants_vaccines;
                        $moisData[$mois]['cibles'] += $item->enfants_cibles;
                        $moisData[$mois]['non_vaccines'] = max(0, $moisData[$mois]['cibles'] - $moisData[$mois]['vaccines']);
                        $moisData[$mois]['pourcentage'] = $moisData[$mois]['cibles'] > 0
                            ? round(($moisData[$mois]['vaccines'] / $moisData[$mois]['cibles']) * 100, 2)
                            : 0;
                    }
                }

                // Totaux par tranche (basés sur les cibles globales)
                $totalVaccines = array_sum(array_column($moisData, 'vaccines'));
                $trancheCible = $data['cibles'][$tranche] ?? 0;
                $totalNonVaccines = max(0, $trancheCible - $totalVaccines);
                $pourcentage = $trancheCible > 0 ? round(($totalVaccines / $trancheCible) * 100, 2) : 0;

                $data['data'][$vaccin][$tranche] = [
                    'mois' => $moisData,
                    'total' => [
                        'vaccines' => $totalVaccines,
                        'non_vaccines' => $totalNonVaccines,
                        'pourcentage' => $pourcentage
                    ]
                ];
            }
        }

        return $data;
    }

    protected function calculateTotal($urbain, $rural)
    {
        $total = [];

        $allVaccins = array_unique(array_merge(array_keys($urbain['data']), array_keys($rural['data'])));

        foreach ($allVaccins as $vaccin) {
            $tranches = array_unique(array_merge(
                array_keys($urbain['data'][$vaccin] ?? []),
                array_keys($rural['data'][$vaccin] ?? [])
            ));

            foreach ($tranches as $tranche) {
                $moisData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $u = $urbain['data'][$vaccin][$tranche]['mois'][$i] ?? ['vaccines' => 0];
                    $r = $rural['data'][$vaccin][$tranche]['mois'][$i] ?? ['vaccines' => 0];

                    $vaccines = ($u['vaccines'] ?? 0) + ($r['vaccines'] ?? 0);

                    $moisData[$i] = [
                        'vaccines' => $vaccines,
                        'cibles' => 0, // on n’utilise plus ici
                        'non_vaccines' => 0, // sera calculé globalement
                        'pourcentage' => 0 // sera calculé globalement
                    ];
                }

                // Total vaccinés
                $totalVaccines = array_sum(array_column($moisData, 'vaccines'));
                $totalCibles = ($urbain['cibles'][$tranche] ?? 0) + ($rural['cibles'][$tranche] ?? 0);
                $totalNonVaccines = max(0, $totalCibles - $totalVaccines);
                $pourcentage = $totalCibles > 0 ? round(($totalVaccines / $totalCibles) * 100, 2) : 0;

                $total['data'][$vaccin][$tranche] = [
                    'mois' => $moisData,
                    'total' => [
                        'vaccines' => $totalVaccines,
                        'non_vaccines' => $totalNonVaccines,
                        'pourcentage' => $pourcentage
                    ]
                ];
            }
        }

        $total['cibles'] = [
            '0-11mois' => ($urbain['cibles']['0-11mois'] ?? 0) + ($rural['cibles']['0-11mois'] ?? 0),
            '12-59mois' => ($urbain['cibles']['12-59mois'] ?? 0) + ($rural['cibles']['12-59mois'] ?? 0),
            '18mois' => ($urbain['cibles']['18mois'] ?? 0) + ($rural['cibles']['18mois'] ?? 0),
            '5ans' => ($urbain['cibles']['5ans'] ?? 0) + ($rural['cibles']['5ans'] ?? 0)
        ];

        return $total;
    }
}
