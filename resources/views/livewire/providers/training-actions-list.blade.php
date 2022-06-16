<div class="card-body row">
    <h4>Cursos matriculados</h4>
    <div class="col-md-6 mb-1">
        <label class="form-label">Course:</label>
        <input wire:model="search_course_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
    </div>
    <div class="col-md-6 mb-1">
        <label class="form-label">Group</label>
        <input wire:model="search_course_group" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Grupo" data-column-index="0" />
    </div>
    <div class="registerd_students" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Formative Action</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($training_actions))
                @foreach($training_actions as $training_action)
                    <tr>
                        <td>{{$training_action['name']}}</td>
                        <td>{{$training_action['group']}}</td>
                        <td>
                            <a href="{{url('/training_actions/view/'.$training_action->id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
