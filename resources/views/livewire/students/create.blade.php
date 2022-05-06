<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Alumno</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                       <div class="form-group col-4">
                            <label for="name">Nombre</label>
                            <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="surname">Apellidos</label>
                            <input wire:model.lazy="surname" type="text" class="form-control" id="surname" placeholder="Apellidos">@error('surname') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="dni">DNI</label>
                            <input wire:model.lazy="dni" type="text" class="form-control" id="dni" placeholder="Dni">@error('dni') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="telephone">Telefono</label>
                            <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono">@error('telephone') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="email">Correo</label>
                            <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo">@error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_company_id">Empresa</label>
                               <select class="form-control selectCreate" wire:model.lazy="create_company_id" id="create_company_id">
                                   <option value="">Seleccione una empresa</option>
                                   @foreach($companies as $company)
                                       <option value="{{$company['id']}}">{{$company['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_company_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="user">Usuario</label>
                            <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario">@error('user') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="password">Contraseña</label>
                            <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña">@error('password') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="date_of_birth">Fecha de nacimiento</label>
                            <input wire:model.lazy="date_of_birth" type="date" class="form-control" id="date_of_birth" placeholder="Fecha de Nacimiento">@error('date_of_birth') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_level_study_id">Nivel Estudio</label>
                               <select class="form-control selectCreate" wire:model.lazy="create_level_study_id" id="create_level_study_id">
                                   <option value="">Seleccione un nivel de estudio</option>
                                   @foreach($level_studies as $level)
                                       <option value="{{$level['id']}}">{{$level['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_level_study_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="disabled"><input wire:model.lazy="disabled" id="disabled" type="checkbox" id="cbox1" value="first_checkbox"> Discapacitado</label>
                           @error('disabled') <span class="error text-danger">{{ $message }}</span> @enderror
                       </div>
                        <div class="form-group col-4">
                            <label for="social_security_number">Nº Seguridad Social</label>
                            <input wire:model.lazy="social_security_number" type="text" class="form-control" id="social_security_number" placeholder="Nº Seguridad Social">@error('social_security_number') <span class="error text-danger">{{ $message }}</span> @enderror
                            <br>
                        </div>
                       <div class="form-group col-4">
                            <label for="c_quote">C. Cotización</label>
                            <input wire:model.lazy="c_quote" type="text" class="form-control" id="c_quote" placeholder="C. Cotización">@error('c_quote') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="quote_group">Grupo Cotización</label>
                            <input wire:model.lazy="quote_group" type="text" class="form-control" id="quote_group" placeholder="Grupo Cotización">@error('quote_group') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_professional_category_id">Categoria Profesional</label>
                               <select class="form-control selectCreate" wire:model.lazy="create_professional_category_id" id="create_professional_category_id">
                                   <option value="-1">Seleccione un categoria profesional</option>
                                   @foreach($professional_categories as $category)
                                       <option value="{{$category['id']}}">{{$category['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_professional_category_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="annual_gross_salary">Salario Bruto Anual</label>
                            <input wire:model.lazy="annual_gross_salary" type="text" class="form-control" id="annual_gross_salary" placeholder="Salario Bruto Anual">@error('annual_gross_salary') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="annual_hours">Horas Anuales</label>
                            <input wire:model.lazy="annual_hours" type="text" class="form-control" id="annual_hours" placeholder="Horas Anuales">@error('annual_hours') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="hourly_cost_worker_gross">Coste Hora Bruto del Trabajador</label>
                            <input wire:model.lazy="hourly_cost_worker_gross" type="text" class="form-control" id="hourly_cost_worker_gross" placeholder="Coste Hora Bruto del Trabajador">@error('hourly_cost_worker_gross') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="direction">Dirección</label>
                            <input wire:model.lazy="direction" type="text" class="form-control" id="direction" placeholder="Dirección">@error('direction') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="post_code">Código Postal</label>
                            <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal">@error('post_code') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_province_id">Provincia</label>
                               <select class="form-control selectCreate" wire:model.lazy="create_province_id" id="create_province_id">
                                   <option value="-1">Seleccione una provincia</option>
                                   @foreach($provinces as $province)
                                       <option value="{{$province['id']}}">{{$province['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_province_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="population">Población</label>
                            <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población">@error('population') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="iban">Iban</label>
                            <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban">@error('iban') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="observation"></label>
                            <textarea wire:model.lazy="observation" class="form-control" placeholder="observaciones"></textarea>
                            @error('observation') <span class="error text-danger">{{ $message }}</span> @enderror
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
            @this.set(this.id, $(this).val())
            })
        })
    </script>
</div>
