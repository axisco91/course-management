<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model.lazy="selected_id">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="training_action_id">Acción Formativa</label>
                    <select wire:model.lazy="training_action_id" class="form-control select2" id="training_action_id">
                        <option value="">Selección una acción formativa</option>
                        @foreach($training_actions as $action)
                            <option value="{{$action['id']}}" {{$training_action_id == $action['id'] ? 'selected' : ''}}>
                                @if ($action['id'] < 10)
                                    00{{$action['id']}} - {{$action['name']}}
                                @elseif($action['id'] < 100)
                                    0{{$action['id']}} - {{$action['name']}}
                                @else
                                    {{$action['id']}} - {{$action['name']}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('training_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="group">Grupo</label>
                <input wire:model.lazy="group" type="text" class="form-control" id="group" placeholder="Grupo" disabled>@error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="course_type_id">Tipo Curso</label>
                    <select wire:model.lazy="course_type_id" class="form-control select2" id="course_type_id">
                        <option value="">Seleccione un tipo</option>
                        @foreach($course_types as $type)
                            <option value="{{$type['id']}}" {{$type['id'] == $course_type_id ? 'selected' : ''}}>{{$type['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('course_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="teacher_id">Docente</label>
                    <br>
                    <select wire:model.lazy="teacher_id" class="form-control select2" id="teacher_id">
                        <option>Seleccione un docente</option>
                        @foreach($teachers as $teacher)
                            <option value="{{$teacher['id']}}">{{$teacher['name']}} {{$teacher['surname']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('teacher_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="nebrija">Nebrija</label>
                <input wire:model.lazy="nebrija" type="text" class="form-control" id="nebrija" placeholder="Nebrija">@error('nebrija') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="formation_center_id">Centro de Formación</label>
                    <select wire:model.lazy="formation_center_id" class="form-control select2" id="formation_center_id">
                        <option value="">Seleccione un centro formativo</option>
                        @foreach($formation_centers as $center)
                            <option value="{{$center['id']}}">{{$center['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('formation_center_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="delivery_center_id">Centro de Impartición</label>
                    <select wire:model.lazy="delivery_center_id" class="form-control select2" id="delivery_center_id">
                        <option value="">Seleccione un centro de impartición</option>
                        @foreach($delivery_centers as $center)
                            <option value="{{$center['id']}}">{{$center['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('delivery_center_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="beginning">Fecha Inicio</label>
                <input wire:model.lazy="beginning" type="date" class="form-control" id="beginning">@error('beginning') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="end">Fecha Fin</label>
                <input wire:model.lazy="end" type="date" class="form-control" id="end">@error('end') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="morning_schedule">Horario Mañana</label>
                <input wire:model.lazy="morning_schedule" type="text" class="form-control" id="morning_schedule" placeholder="Horario Mañana">@error('morning_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="afternoon_schedule">Horario Tarde</label>
                <input wire:model.lazy="afternoon_schedule" type="text" class="form-control" id="afternoon_schedule" placeholder="Horario Tarde">@error('afternoon_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <label class="form-label">Dias de Impartición</label>
                <br>
                <label class="form-label" for="monday"><input wire:model.lazy="monday" id="monday" type="checkbox" id="monday" value="monday"> Lunes</label>
                @error('monday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="tuesday"><input wire:model.lazy="tuesday" id="tuesday" type="checkbox" id="tuesday" value="tuesday"> Martes</label>
                @error('tuesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="wednesday"><input wire:model.lazy="wednesday" id="wednesday" type="checkbox" id="wednesday" value="wednesday"> Miercoles</label>
                @error('wednesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="thursday"><input wire:model.lazy="thursday" id="thursday" type="checkbox" id="thursday" value="thursday"> Jueves</label>
                @error('thursday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="friday"><input wire:model.lazy="friday" id="friday" type="checkbox" id="friday" value="friday"> Viernes</label>
                @error('friday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="saturday"><input wire:model.lazy="saturday" id="saturday" type="checkbox" id="saturday" value="saturday"> Sabado</label>
                @error('saturday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-3 mb-1">
                <br>
                <label class="form-label" for="sunday"><input wire:model.lazy="sunday" id="sunday" type="checkbox" id="sunday" value="sunday"> Domingo</label>
                @error('sunday') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <br>
                <label class="form-label" for="outsourced"><input wire:model.lazy="outsourced" id="outsourced" type="checkbox" id="outsourced" value="outsourced"> Subcontratado</label>
                @error('outsourced') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="reactivated">Reactivado</label>
                <select wire:model.lazy="reactivated" class="form-control" id="reactivated">
                    <option vale="0">No</option>
                    <option value="1">Si</option>
                </select>
                @error('reactivated') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="price">Precio</label>
                <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio">@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 mb-1">
                <label class="form-label" for="course_observation"></label>
                <textarea wire:model.lazy="course_observation" class="form-control" id="course_observation" placeholder="Observaciones"></textarea>
                @error('course_observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12 mb-1">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
        </div>
    </form>
@section('vendor-script')
    <!-- vendor files -->
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    @endsection
    <script>
        document.addEventListener('livewire:load', function() {
            initializeSelect2()
            $('.select2').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('#training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        })
    </script>
</div>
