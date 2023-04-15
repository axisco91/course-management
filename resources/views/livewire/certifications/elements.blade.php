<div class="card-body row">
    <h4>Unidades Formativas / Modulos</h4>
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
            @if(isset($certification_elements))
                @foreach($certification_elements as $row)
                    <tr>
                        @if ($row['training_unit_id'])
                        <td>{{$row['formative_unit']}} {{$row['training_unit_name']}}</td>
                        <td>{{$row['training_unit_tutoring_hours']}}</td>
                        <td>{{$row['training_unit_exam_hours']}}</td>
                        <td>{{$row['training_unit_face_to_face_hours']}}</td>
                        <td>{{$row['training_unit_teletraining_hours']}}</td>
                        <td>{{$row['training_unit_total_hours']}}</td>
                            <td>
                                <a href="{{url('/training_units/view/'.$row->training_unit_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                            </td>
                        @elseif ($row['module_id'])
                            <td>{{$row['formative_module']}} {{$row['module_name']}}</td>
                            <td>{{$row['module_tutoring_hours']}}</td>
                            <td>{{$row['module_exam_hours']}}</td>
                            <td>{{$row['module_face_to_face_hours']}}</td>
                            <td>{{$row['module_teletraining_hours']}}</td>
                            <td>{{$row['module_total_hours']}}</td>
                            <td>
                                <a href="{{url('/modules/view/'.$row->module_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
