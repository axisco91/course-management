 <div class="row">
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/students/edit/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
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
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="company">Empresa</label> <a href="{{url('/companies/view/'.$this->company_id)}}" target="_blank" class="view"><i class="fa-regular fa-eye"></i></a>
        <div wire:ignore>
            <select class="form-control" wire:model.lazy="company_id" id="company_id" disabled>
                <option value="">Seleccione una empresa</option>
                @foreach($companies as $company)
                    <option value="{{$company['id']}}">{{$company['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="user">Usuario</label>
        <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="password">Contraseña</label>
        <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña" disabled>
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
        <label class="form-label" for="c_quote">C. Cotización</label>
        <input wire:model.lazy="c_quote" type="text" class="form-control" id="c_quote" placeholder="C. Cotización" disabled>
    </div>
    <div class="col-md-4 col-12">
        <div wire:ignore>
            <div class="mb-1">
                <label class="form-label" for="quote_group_id">Grupo Cotización</label>
                <select class="form-select @error('quote_group_id') is-invalid @enderror" wire:model.lazy="quote_group_id" id="quote_group_id" disabled>
                    <option value="">Seleccione un categoria profesional</option>
                    @foreach($quote_groups as $quote_group)
                        <option value="{{$quote_group['id']}}">{{$quote_group['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @error('quote_group_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        <label class="form-label" for="annual_gross_salary">Salario Bruto Anual</label>
        <input wire:model.lazy="annual_gross_salary" type="text" class="form-control" id="annual_gross_salary" placeholder="Salario Bruto Anual" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="annual_hours">Horas Anuales</label>
        <input wire:model.lazy="annual_hours" type="text" class="form-control" id="annual_hours" placeholder="Horas Anuales" disabled>
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="hourly_cost_worker_gross">Coste Hora Bruto del Trabajador</label>
        <input wire:model.lazy="hourly_cost_worker_gross" type="text" class="form-control" id="hourly_cost_worker_gross" placeholder="Coste Hora Bruto del Trabajador" disabled>@error('hourly_cost_worker_gross') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="population">Población</label>
        <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población" disabled>@error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-4 mb-1">
        <label class="form-label" for="iban">Iban</label>
        <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban" disabled>@error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12 col-md-12 mb-1">
        <label class="form-label" for="observation"></label>
        <textarea wire:model.lazy="observation" class="form-control" id="observation" placeholder="observaciones" disabled></textarea>
        @error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
