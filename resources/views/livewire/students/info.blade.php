<!-- Modal -->
<div wire:ignore.self class="modal fade" id="studentsTabModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-student-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{$this->name}} {{$this->surname}}</h1>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container active" id="general">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="dni">DNI</label>
                                <input wire:model.lazy="dni" type="text" class="form-control" id="dni" placeholder="Dni" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="telephone">Telefono</label>
                                <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="email">Correo</label>
                                <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <div wire:ignore>
                                    <label class="form-label" for="company">Empresa</label>
                                    <select class="form-control" wire:model.lazy="company_id" id="company_id" disabled>
                                        <option value="">Seleccione una empresa</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="user">Usuario</label>
                                <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="password">Contraseña</label>
                                <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="date_of_birth">Fecha de nacimiento</label>
                                <input wire:model.lazy="date_of_birth" type="date" class="form-control" id="date_of_birth" placeholder="Fecha de Nacimiento" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <div wire:ignore>
                                    <label class="form-label" for="level_study_id">Nivel Estudio</label>
                                    <select class="form-select select2" wire:model.lazy="level_study_id" id="level_study_id" disabled>
                                        <option value="">Seleccione un nivel de estudio</option>
                                        @foreach($level_studies as $level)
                                            <option value="{{$level['id']}}">{{$level['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('level_study_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <br>
                                <label class="form-label" for="disabled"><input wire:model.lazy="disabled" id="disabled" type="checkbox" id="cbox1" value="first_checkbox" disabled> Discapacitado</label>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="social_security_number">Nº Seguridad Social</label>
                                <input wire:model.lazy="social_security_number" type="text" class="form-control" id="social_security_number" placeholder="Nº Seguridad Social" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="c_quote">C. Cotización</label>
                                <input wire:model.lazy="c_quote" type="text" class="form-control" id="c_quote" placeholder="C. Cotización" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="quote_group">Grupo Cotización</label>
                                <input wire:model.lazy="quote_group" type="text" class="form-control" id="quote_group" placeholder="Grupo Cotización" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <div wire:ignore>
                                    <label class="form-label" for="professional_category_id">Categoria Profesional</label>
                                    <select class="form-select select2" wire:model.lazy="professional_category_id" id="professional_category_id" disabled>
                                        <option value="">Seleccione un categoria profesional</option>
                                        @foreach($professional_categories as $category)
                                            <option value="{{$category['id']}}">{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('professional_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="annual_gross_salary">Salario Bruto Anual</label>
                                <input wire:model.lazy="annual_gross_salary" type="text" class="form-control" id="annual_gross_salary" placeholder="Salario Bruto Anual" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="annual_hours">Horas Anuales</label>
                                <input wire:model.lazy="annual_hours" type="text" class="form-control" id="annual_hours" placeholder="Horas Anuales" disabled>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="hourly_cost_worker_gross">Coste Hora Bruto del Trabajador</label>
                                <input wire:model.lazy="hourly_cost_worker_gross" type="text" class="form-control" id="hourly_cost_worker_gross" placeholder="Coste Hora Bruto del Trabajador" disabled>@error('hourly_cost_worker_gross') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="direction">Dirección</label>
                                <input wire:model.lazy="direction" type="text" class="form-control" id="direction" placeholder="Dirección" disabled>@error('direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="post_code">Código Postal</label>
                                <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal" disabled>@error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <div wire:ignore>
                                    <label class="form-label" for="province_id">Provincia</label>
                                    <select class="form-select select2" wire:model.lazy="province_id" id="province_id" disabled>
                                        <option value="">Seleccione una provincia</option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="population">Población</label>
                                <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población" disabled>@error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="iban">Iban</label>
                                <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban" disabled>@error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="observation"></label>
                                <textarea wire:model.lazy="observation" class="form-control" placeholder="observaciones" disabled></textarea>
                                @error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
