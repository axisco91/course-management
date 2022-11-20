<div class="card-body row">
    <h4>Unidades Formativas</h4>
    <div class="" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Horas Tutorías</th>
                <th>Horas Examen</th>
                <th>Horas Presenciales</th>
                <th>Horas Teleformaicón</th>
                <th>Horas Totales</th>
                <th>Horas</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($training_units))
                @foreach($training_units as $row)
                    <tr>
                        <td>{{$row['formative_unit']}} {{$row['name']}}</td>
                        <td>{{$row['tutoring_hours']}}</td>
                        <td>{{$row['exam_hours']}}</td>
                        <td>{{$row['face_to_face_hours']}}</td>
                        <td>{{$row['teletraining_hours']}}</td>
                        <td>{{$row['total_hours']}}</td>
                        <td>
                            <a href="{{url('/training_units/view/'.$row->id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
