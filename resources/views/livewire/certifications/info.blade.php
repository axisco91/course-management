 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/certifications/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
     <div class="col-12 col-md-3">
         <div class="mb-1">
             <label class="form-label" for="name">Nombre</label>
             <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre" disabled>@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
         </div>
     </div>
     <div class="col-12 col-md-3">
         <div class="mb-1">
             <label class="form-label" for="hours">Horas</label>
             <input wire:model.lazy="total_hours" type="text" class="form-control @error('hours') is-invalid @enderror" id="total_hours" disabled placeholder="Horas totales">@error('total_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
         </div>
     </div>
     <div class="col-12 mb-2">
         <div>Acciones Formativas o modulos</div>
         @foreach($certification_elements as $certification_element)
             <div class="" style="display: flex; background: white; margin: 5px; padding: 5px; justify-content: space-between;">
                 @if ($certification_element['training_action_id'])
                     <div style="margin-top: 5px;">
                         {{$certification_element['formative_action']}} - {{$certification_element['training_action_name']}}
                     </div>
                     <div>

                     </div>
                 @elseif($certification_element['module_id'])
                     <div style="margin-top: 5px;">
                         {{$certification_element['code']}} - {{$certification_element['module_name']}}
                     </div>
                     <div>

                     </div>
                 @endif
             </div>
         @endforeach
     </div>
</div>
