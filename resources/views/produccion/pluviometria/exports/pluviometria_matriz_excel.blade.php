<table>
    <thead>
        <tr>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Sector</th>
            @foreach($fechas as $fecha)
                <th style="background-color: #eaecf4; font-weight: bold; text-align: center;">
                    {{ \Carbon\Carbon::parse($fecha)->format('d/m') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($sectores as $sector)
            <tr>
                <td style="font-weight: bold;">{{ $sector->nombre }}</td>
                @foreach($fechas as $fecha)
                    <td style="text-align: center;">
                        {{ $registros[$sector->id][$fecha]->cantidad_mm ?? 0 }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>