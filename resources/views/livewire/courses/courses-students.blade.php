 <div class="card-body row">
    <h4>Alumnos matriculados</h4>
    <div class="col-md-6 mb-1">
        <label class="form-label">Nombre:</label>
        <input wire:model="search_student_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
    </div>
    <div class="col-md-6 mb-1">
        <label class="form-label">Apellidos:</label>
        <input wire:model="search_surname" type="text" class="form-control dt-input" data-column="2" placeholder="Apellidos" data-column-index="1" />
    </div>
    <div class="registerd_students" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($students))
                    @foreach($students as $student)
                        <tr>
                            <td>{{$student['name'].' '.$student['surname']}} {{$student['is_bonus'] == 1 ? '('.$student->company_name.')' : ''}}</td>
                            <td>{{$student['dni']}}</td>
                            <td> <a href="{{url('/students/view/'.$student->id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
