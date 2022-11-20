 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/certifications/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
     <div class="col-12 col-md-3">
         <div class="mb-1">
             <label class="form-label" for="code">Código</label>
             <input wire:model.lazy="code" type="text" class="form-control" id="code" placeholder="Código" disabled>
         </div>
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
     <div class="col-md-3 col-12 mb-1">
         <div wire:ignore>
             <label class="form-label" for="professional_family_id">Familia</label>
             <select wire:model.lazy="professional_family_id" class="form-select" id="professional_family_id" disabled>
                 <option value="-1">Seleccione una familia profeccional</option>
                 @foreach($professional_families as $professional_family)
                     <option value="{{$professional_family['id']}}">{{$professional_family['name']}}</option>
                 @endforeach
             </select>
         </div>
     </div>
     <div class="col-md-3 col-12 mb-1">
         <div wire:ignore>
             <label class="form-label" for="professional_area_id">Area</label>
             <select wire:model.lazy="professional_area_id" class="form-select" id="professional_area_id" disabled>
                 <option value="-1">Seleccione una area profeccional</option>
                 @foreach($professional_areas as $professional_area)
                     <option value="{{$professional_area['id']}}">{{$professional_area['name']}}</option>
                 @endforeach
             </select>
         </div>
     </div>
     <div class="col-md-3 col-12 mb-1">
         <div wire:ignore>
             <label class="form-label" for="level">Nivel</label>
             <select wire:model.lazy="level" class="form-select" id="level" disabled>
                 <option value="-1">Seleccione un nivel</option>
                 <option value="1">1</option>
                 <option value="2">2</option>
                 <option value="3">3</option>
             </select>
         </div>
     </div>
     <div class="col-md-2 col-12">
         <div class="mb-1">
             <br>
             <label class="form-labe" for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}} disabled> Activo</label>
             @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
         </div>
     </div>
</div>
