@if(empty($data))
<div class="alert alert-warning">Aucune donnée disponible pour cette zone.</div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="table-light">
            <tr>
                <th>Vaccin</th>
                <th>Tranche d'âge</th>
                @for($i = 1; $i <= 12; $i++)
                    <th>Mois {{ $i }}</th>
                @endfor
                <th>Total vaccinés</th>
                <th>Non vaccinés</th>
                <th>% Vaccination</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $vaccin => $tranches)
                @if($vaccin === 'Hep.B')
                    @continue
                @endif
                @foreach($tranches as $tranche => $values)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ count($tranches) }}">{{ $vaccin }}</td>
                        @endif
                        <td>{{ $tranche }}</td>
                        @for($i = 1; $i <= 12; $i++)
                            <td>{{ $values['mois'][$i]['vaccines'] ?? 0 }}</td>
                        @endfor
                        <td>{{ $values['total']['vaccines'] ?? 0 }}</td>
                        <td>{{ $values['total']['non_vaccines'] ?? 0 }}</td>
                        <td>{{ $values['total']['pourcentage'] ?? 0 }}%</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endif
