 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/courses/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="name"></label>
         <input wire:model.lazy="name" type="text" class="form-control" id="name" disabled placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="training_action_id">Acción Formativa</label> <a href="{{url('/training_actions/view/'.$this->training_action_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
         <div wire:ignore>
             <select wire:model.lazy="training_action_id" class="form-control" id="training_action_id" disabled>
                 <option value="">Selección una acción formativa</option>
                 @foreach($training_actions as $action)
                     <option value="{{$action['id']}}" {{$this->training_action_id == $action['id'] ? 'selected' : ''}}>
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
             <select wire:model.lazy="course_type_id" class="form-control" id="course_type_id" disabled>
                 <option value="">Seleccione un tipo</option>
                 @foreach($course_types as $type)
                     <option value="{{$type['id']}}" {{$type['id'] == $course_type_id ? 'selected' : ''}}>{{$type['name']}}</option>
                 @endforeach
             </select>
         </div>
         @error('course_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="teacher_id">Docente</label> <a href="{{url('/teachers/view/'.$this->teacher_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
         <div wire:ignore>
             <select wire:model.lazy="teacher_id" class="form-control" id="teacher_id" disabled>
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
         <select wire:model.lazy="nebrija" class="form-control" id="nebrija" disabled>
             <option vale="0">No</option>
             <option value="1">Si</option>
         </select>
         @error('nebrija') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <div wire:ignore>
             <label class="form-label" for="formation_center_id">Centro de Formación</label>
             <select wire:model.lazy="formation_center_id" class="form-control" id="formation_center_id" disabled>
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
             <select wire:model.lazy="delivery_center_id" class="form-control" id="delivery_center_id" disabled>
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
         <input wire:model.lazy="beginning" type="date" class="form-control" id="beginning" disabled>@error('beginning') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="end">Fecha Fin</label>
         <input wire:model.lazy="end" type="date" class="form-control" id="end" disabled>@error('end') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="morning_schedule">Horario Mañana</label>
         <input wire:model.lazy="morning_schedule" type="text" class="form-control" id="morning_schedule" placeholder="Horario Mañana" disabled>@error('morning_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="afternoon_schedule">Horario Tarde</label>
         <input wire:model.lazy="afternoon_schedule" type="text" class="form-control" id="afternoon_schedule" placeholder="Horario Tarde" disabled>@error('afternoon_schedule') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <label class="form-label">Dias de Impartición</label>
         <br>
         <label class="form-label" for="monday"><input {{$this->monday == 1 ? 'checked' : ''}} id="monday" type="checkbox" id="monday" value="monday" disabled> Lunes</label>
         @error('monday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="tuesday"><input {{$this->tuesday == 1 ? 'checked' : ''}} id="tuesday" type="checkbox" id="tuesday" value="tuesday" disabled> Martes</label>
         @error('tuesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="wednesday"><input {{$this->wednesday == 1 ? 'checked' : ''}} id="wednesday" type="checkbox" id="wednesday" value="wednesday" disabled> Miercoles</label>
         @error('wednesday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="thursday"><input {{$this->thursday == 1 ? 'checked' : ''}} id="thursday" type="checkbox" id="thursday" value="thursday" disabled> Jueves</label>
         @error('thursday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="friday"><input {{$this->friday == 1 ? 'checked' : ''}} id="friday" type="checkbox" id="friday" value="friday" disabled> Viernes</label>
         @error('friday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="saturday"><input {{$this->saturday == 1 ? 'checked' : ''}} id="saturday" type="checkbox" id="saturday" value="saturday" disabled> Sabado</label>
         @error('saturday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="form-group col-3 mb-1">
         <br>
         <label class="form-label" for="sunday"><input {{$this->sunday == 1 ? 'checked' : ''}} id="sunday" type="checkbox" id="sunday" value="sunday" disabled> Domingo</label>
         @error('sunday') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <br>
         <label class="form-label" for="outsourced"><input {{$this->outsourced == 1 ? 'checked' : ''}} id="outsourced" type="checkbox" id="outsourced" value="outsourced" disabled> Subcontratado</label>
         @error('outsourced') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>

     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="reactivated">Reactivado</label>
         <select wire:model.lazy="reactivated" class="form-control" id="reactivated" disabled>
             <option vale="0">No</option>
             <option value="1">Si</option>
         </select>
         @error('reactivated') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="price">Precio</label>
         <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio" disabled>@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-12 mb-1">
         <label class="form-label" for="course_observation"></label>
         <textarea wire:model.lazy="course_observation" class="form-control" id="course_observation" placeholder="Observaciones" disabled></textarea>
         @error('course_observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
     </div>
     <div class="col-12 mb-1">
         <button type="button" id="save_course" wire:click.prevent="update()" class="btn btn-primary" hidden>Guardar</button>
     </div>
     <script>
         $('.select2').select2({
             dropdownParent: $('#coursesTabModal')
         });
     </script>
</div>
