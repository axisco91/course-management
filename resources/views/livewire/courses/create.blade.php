<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Curso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                       <div class="form-group col-4">
                            <label for="name"></label>
                            <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_training_action_id">Acción Formativa</label>
                               <select wire:model.lazy="create_training_action_id" class="form-control selectCreate" id="create_training_action_id">
                                   <option value="">Selección una acción formativa</option>
                                   @foreach($training_actions as $action)
                                       <option value="{{$action['id']}}">
                                           @if ($action['id'] < 10)
                                               00{{$action['id']}} - {{$action['name']}}
                                           @elseif($action < 100)
                                               0{{$action['id']}} - {{$action['name']}}
                                           @else
                                               {{$action['id']}} - {{$action['name']}}
                                           @endif
                                       </option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_training_action_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="group">Grupo</label>
                            <input wire:model.lazy="group" type="text" class="form-control" id="group" placeholder="Grupo" disabled>@error('group') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="course_type_id">Tipo Curso</label>
                               <select wire:model.lazy="course_type_id" class="form-control selectCreate" id="course_type_id">
                                   <option value="">Seleccione un tipo</option>
                                   @foreach($course_types as $type)
                                       <option value="{{$type['id']}}">{{$type['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_course_type_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="teacher_id">Docente</label>
                               <br>
                               <select wire:model.lazy="create_teacher_id" class="form-control selectCreate" id="create_teacher_id">
                                   <option>Seleccione un docente</option>
                                   @foreach($teachers as $teacher)
                                       <option value="{{$teacher['id']}}">{{$teacher['name']}} {{$teacher['surname']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_teacher_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <br>
                            <label for="nebrija"><input wire:model.lazy="nebrija" type="checkbox" id="nebrija" value="nebrija"> Nebrija</label>
                            @error('nebrija') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <div wire:ignore>
                                <label for="create_formation_center_id">Centro de Formación</label>
                                <select wire:model.lazy="create_formation_center_id" class="form-control selectCreate" id="create_formation_center_id">
                                    <option value="">Seleccione un centro formativo</option>
                                    @foreach($formation_centers as $center)
                                        <option value="{{$center['id']}}">{{$center['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_formation_center_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <div wire:ignore>
                                <label for="create_delivery_center_id">Centro de Impartición</label>
                                <select wire:model.lazy="create_delivery_center_id" class="form-control selectCreate" id="create_delivery_center_id">
                                    <option value="">Seleccione un centro de impartición</option>
                                    @foreach($delivery_centers as $center)
                                        <option value="{{$center['id']}}">{{$center['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_delivery_center_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="beginning">Fecha Inicio</label>
                            <input wire:model.lazy="beginning" type="date" class="form-control" id="beginning">@error('beginning') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="end">Fecha Fin</label>
                            <input wire:model.lazy="end" type="date" class="form-control" id="end">@error('end') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="morning_schedule"></label>
                            <input wire:model.lazy="morning_schedule" type="text" class="form-control" id="morning_schedule" placeholder="Horario Mañana">@error('morning_schedule') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="afternoon_schedule"></label>
                            <input wire:model.lazy="afternoon_schedule" type="text" class="form-control" id="afternoon_schedule" placeholder="Horario Tarde">@error('afternoon_schedule') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                            <label>Dias de Impartición</label>
                           <br>
                            <label for="monday"><input wire:model.lazy="monday" id="monday" type="checkbox" id="monday" value="monday"> Lunes</label>
                            @error('monday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="tuesday"><input wire:model.lazy="tuesday" id="tuesday" type="checkbox" id="tuesday" value="tuesday"> Martes</label>
                            @error('tuesday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="wednesday"><input wire:model.lazy="wednesday" id="wednesday" type="checkbox" id="wednesday" value="wednesday"> Miercoles</label>
                            @error('wednesday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="thursday"><input wire:model.lazy="thursday" id="thursday" type="checkbox" id="thursday" value="thursday"> Jueves</label>
                            @error('thursday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="friday"><input wire:model.lazy="friday" id="friday" type="checkbox" id="friday" value="friday"> Viernes</label>
                            @error('friday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="saturday"><input wire:model.lazy="saturday" id="saturday" type="checkbox" id="saturday" value="saturday"> Sabado</label>
                            @error('saturday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-3">
                           <br>
                            <label for="sunday"><input wire:model.lazy="sunday" id="sunday" type="checkbox" id="sunday" value="sunday"> Domingo</label>
                            @error('sunday') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <br>
                            <label for="outsourced"><input wire:model.lazy="outsourced" id="outsourced" type="checkbox" id="outsourced" value="outsourced"> Subcontratado</label>
                            @error('outsourced') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="reactivated">Reactivado</label>
                            <select wire:model.lazy="reactivated" class="form-control" id="reactivated">
                                <option vale="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('reactivated') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="price">Precio</label>
                            <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio">@error('price') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="course_observation"></label>
                            <textarea wire:model.lazy="course_observation" class="form-control" id="course_observation" placeholder="Observaciones"></textarea>
                            @error('course_observation') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function(){
            $('.selectCreate').select2()
            $('.selectCreate').on('change', function(){
                @this.set(this.id, this.value)
            })
            $('.selectCreate').on('change', function(){
            @this.set(this.id, this.value)
            })
            $('#create_training_action_id').on('change', function(){
            @this.set(this.id, this.value)
            @this.setName()
            })
        })
    </script>
</div>
