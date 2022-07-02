<div class="card-body row">
    <h4>Cursos matriculados</h4>
    <div class="col-md-6 mb-1">
        <label class="form-label">Curso:</label>
        <input wire:model="search_course_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
    </div>
    <div class="col-md-6 mb-1">
        <label class="form-label">Grupo:</label>
        <input wire:model="search_group" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Grupo" data-column-index="0" />
    </div>
    <div class="registerd_students" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Curso</th>
                <th>Grupo</th>
                <th>F. Inicio</th>
                <th>F. Fin</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($this->courses))
                @foreach($this->courses as $course)
                    <tr>
                        <td>{{$course['name']}}</td>
                        <td>{{$course['group']}}</td>
                        <td>{{$course['beginning']}}</td>
                        <td>{{$course['end']}}</td>
                        <td>
                            <a href="{{url('/courses/view/'.$course->id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
