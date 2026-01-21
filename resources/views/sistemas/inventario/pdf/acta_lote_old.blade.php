<div class="section">
    <p>Por medio de la presente, se hace entrega formal de los <strong>activos informáticos y/o administrativos</strong> detallados en el siguiente listado a: <strong>{{ $user->nombre_completo }}</strong>.</p>
</div>

<div class="section">
    <div class="section-title">EQUIPOS ENTREGADOS</div>
    <table class="table">
        <thead>
            <tr>
                <th>Cod. Activo</th>
                <th>Descripción</th>
                <th>Serial</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $asig)
            <tr>
                <td>{{ $asig->item->asset_tag }}</td>
                <td>{{ $asig->item->name }} ({{ $asig->item->brand }} {{ $asig->item->model }})</td>
                <td>{{ $asig->item->serial }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Accesorios Globales / Observaciones</div>
    <p style="font-style: italic; font-size: 10px;">
        {{-- Aquí puedes concatenar las notas de todas las asignaciones o dejar un espacio en blanco para escribir a mano --}}
        Se entrega el conjunto de equipos con sus respectivos cables de poder y periféricos estándar.
    </p>
</div>