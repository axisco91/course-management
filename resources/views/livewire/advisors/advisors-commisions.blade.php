 <div class="card-body row">
    <h4>Cursos</h4>
    <div class="col-md-12 mb-1">
        <label class="form-label">Nombre:</label>
        <input wire:model="search_company_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
    </div>

    <div class="registerd_students" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    @can('ver courses')
                        <th>Acción</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @if(isset($courses))
                    @foreach($courses as $course)
                        <tr>
                            <td>{{$course['name']}}</td>
                            @can('ver courses')
                                <td>
                                    <a href="{{url('/courses/view/'.$course->id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
