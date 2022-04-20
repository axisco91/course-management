<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateTracingModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateTracingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTracingModalLabel">Editar Seguimiento</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="name"></label>
                            <input wire:model="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="course_id">Curso</label>
                            <select wire:model="course_id" class="form-control" id="course_id" disabled>
                                <option value="-1">Selección un curso</option>
                                @foreach($courses as $course)
                                    <option value="{{$course['id']}}">{{$course['name']}}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="company_id">Empresa</label>
                            <select wire:model="company_id" class="form-control" id="company_id" disabled>
                                <option value="-1">Selección una empresa</option>
                                @foreach($companies as $company)
                                    <option value="{{company['id']}}">{{$companies['name']}}</option>
                                @endforeach
                            </select>
                            @error('company_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="student_id">Alumno</label>
                            <select wire:model="student_id" class="form-control" id="student_id" disabled>
                                <option value="-1">Seleccióna un alumno</option>
                                @foreach($students as $student)
                                    <option value="{{$student['id']}}">{{$student['name'] .' '. $student['surname']}}</option>
                                @endforeach
                            </select>
                            @error('student_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="group">Actividades Realizadas</label>
                            <input wire:model="performed_activities" type="number" class="form-control" id="group" placeholder="Grupo" disabled>@error('group') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="course_type_id">Tipo Curso</label>
                            <select wire:model="course_type_id" class="form-control" id="course_type_id">
                                <option value="-1">Seleccione un tipo</option>
                                @foreach($course_types as $type)
                                    <option vale="{{$type['id']}}">{{$type['name']}}</option>
                                @endforeach
                            </select>
                            @error('course_type_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="teacher_id">Docente</label>
                            <select wire:model="teacher_id" class="form-control" id="teacher_id">
                                <option>Seleccione un docente</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher['id']}}">{{$teacher['name']}}</option>
                                @endforeach
                            </select>
                            @error('teacher_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="nebrija">Nebrija</label>
                            <input wire:model="nebrija" type="text" class="form-control" id="nebrija" placeholder="Nebrija">@error('nebrija') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="formation_center_id">Centro de Formación</label>
                            <select wire:model="formation_center_id" class="form-control" id="formation_center_id">
                                <option value="-1">Seleccione un centro formativo</option>
                                @foreach($formation_centers as $center)
                                    <option value="{{$center['id']}}">{{$center['name']}}</option>
                                @endforeach
                            </select>
                            @error('formation_center_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="delivery_center_id">Centro de Impartición</label>
                            <select wire:model="delivery_center_id" class="form-control" id="delivery_center_id">
                                <option value="-1">Seleccione un centro de impartición</option>
                                @foreach($delivery_centers as $center)
                                    <option value="{{$center['id']}}">{{$center['name']}}</option>
                                @endforeach
                            </select>
                            @error('delivery_center_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="beginning">Fecha Inicio</label>
                            <input wire:model="beginning" type="date" class="form-control" id="beginning">@error('beginning') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="end">Fecha Fin</label>
                            <input wire:model="end" type="date" class="form-control" id="end">@error('end') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="morning_schedule">Horario Mañana</label>
                            <input wire:model="morning_schedule" type="text" class="form-control" id="morning_schedule" placeholder="Horario Mañana">@error('morning_schedule') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="afternoon_schedule">Horario Tarde</label>
                            <input wire:model="afternoon_schedule" type="text" class="form-control" id="afternoon_schedule" placeholder="Horario Tarde">@error('afternoon_schedule') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <label>Dias de Impartición</label>
                            <br>
                            <label for="monday"><input wire:model="monday" id="monday" type="checkbox" id="monday" value="monday"> Lunes</label>
                            @error('monday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="tuesday"><input wire:model="tuesday" id="tuesday" type="checkbox" id="tuesday" value="tuesday"> Martes</label>
                            @error('tuesday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="wednesday"><input wire:model="wednesday" id="wednesday" type="checkbox" id="wednesday" value="wednesday"> Miercoles</label>
                            @error('wednesday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="thursday"><input wire:model="thursday" id="thursday" type="checkbox" id="thursday" value="thursday"> Jueves</label>
                            @error('thursday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="friday"><input wire:model="friday" id="friday" type="checkbox" id="friday" value="friday"> Viernes</label>
                            @error('friday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="saturday"><input wire:model="saturday" id="saturday" type="checkbox" id="saturday" value="saturday"> Sabado</label>
                            @error('saturday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-3">
                            <br>
                            <label for="sunday"><input wire:model="sunday" id="sunday" type="checkbox" id="sunday" value="sunday"> Domingo</label>
                            @error('sunday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <br>
                            <label for="outsourced"><input wire:model="outsourced" id="outsourced" type="checkbox" id="outsourced" value="outsourced"> Subcontratado</label>
                            @error('outsourced') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-4">
                            <label for="reactivated">Reactivado</label>
                            <select wire:model="reactivated" class="form-control" id="reactivated">
                                <option vale="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('reactivated') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="course_observation"></label>
                            <textarea wire:model="course_observation" class="form-control" id="course_observation" placeholder="Observaciones"></textarea>
                            @error('course_observation') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>
</div>
