 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/potential_students/convert/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
    <div class="col-12 col-md-4 mb-1col">
        <label class="form-label" for="dni">DNI</label>
        <input wire:model.lazy="dni" type="text" class="form-control" id="dni" placeholder="Dni" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="telephone">Telefono</label>
        <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="email">Correo</label>
        <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo" disabled>
    </div>
     <div class="col-md-4 col-12 mb-1">
         <label class="form-label" for="company_name">Empresa</label>
         <input wire:model.lazy="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" placeholder="Nombre Empresa" disabled>
         @error('company_name') <div class="invalid-feedback">Nombre empresa es requerido</div> @enderror
     </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="date_of_birth">Fecha de nacimiento</label>
        <input wire:model.lazy="date_of_birth" type="date" class="form-control" id="date_of_birth" placeholder="Fecha de Nacimiento" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <div wire:ignore>
            <label class="form-label" for="level_study_id">Nivel Estudio</label>
            <select class="form-select" wire:model.lazy="level_study_id" id="level_study_id" disabled>
                <option value="">Seleccione un nivel de estudio</option>
                @foreach($level_studies as $level)
                    <option value="{{$level['id']}}">{{$level['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('level_study_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <div wire:ignore>
            <label class="form-label" for="disabled">Discapacitado</label>
            <select class="form-select" wire:model.lazy="disabled" id="disabled" disabled>
                <option value="1">Si</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="social_security_number">Nº Seguridad Social</label>
        <input wire:model.lazy="social_security_number" type="text" class="form-control" id="social_security_number" placeholder="Nº Seguridad Social" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <div wire:ignore>
            <label class="form-label" for="professional_category_id">Categoria Profesional</label>
            <select class="form-select" wire:model.lazy="professional_category_id" id="professional_category_id" disabled>
                <option value="">Seleccione un categoria profesional</option>
                @foreach($professional_categories as $category)
                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('professional_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="direction">Dirección</label>
        <input wire:model.lazy="direction" type="text" class="form-control" id="direction" placeholder="Dirección" disabled>@error('direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="post_code">Código Postal</label>
        <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal" disabled>@error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <div wire:ignore>
            <label class="form-label" for="province_id">Provincia</label>
            <select class="form-select" wire:model.lazy="province_id" id="province_id" disabled>
                <option value="">Seleccione una provincia</option>
                @foreach($provinces as $province)
                    <option value="{{$province['id']}}">{{$province['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
