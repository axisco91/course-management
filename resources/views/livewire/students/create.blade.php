<div class="card-body">
    @error('name')
    <input hidden id="error_name" value="1">
    @enderror

    <form class="form needs-validation" novalidate>
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre" required>
                    @error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="surname">Apellidos</label>
                    <input wire:model.lazy="surname" type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" placeholder="Apellidos">
                    @error('surname') <div class="invalid-feedback">Apellidos es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="dni">DNI</label>
                    <input wire:model.lazy="dni" type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" placeholder="Dni">
                    @error('dni') <div class="invalid-feedback">DNI es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="telephone">Telefono</label>
                    <input wire:model.lazy="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" placeholder="Telefono">
                    @error('telephone') <div class="invalid-feedback">Telefono es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="email">Correo</label>
                    <input wire:model.lazy="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Correo">
                    @error('email') <div class="invalid-feedback">Correo es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="company_id">Empresa</label>
                    <select class="form-select select2 @error('comapny_id') is-invalid @enderror" wire:model.lazy="company_id" id="company_id">
                        <option value="">Seleccione una empresa</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company_id') <div class="invalid-feedback">Empresa es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="user">Usuario</label>
                    <input wire:model.lazy="user" type="text" class="form-control @error('user') is-invalid @enderror" id="user" placeholder="Usuario">
                    @error('user') <div class="invalid-feedback">Usuario es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="password">Contraseña</label>
                    <input wire:model.lazy="password" type="text" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña">
                    @error('password') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="date_of_birth">Fecha de nacimiento</label>
                    <input wire:model.lazy="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" placeholder="Fecha de Nacimiento">
                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <div class="mb-1">
                        <label class="form-label" for="level_study_id">Nivel Estudio</label>
                        <select class="form-select select2 @error('level_study_id') is-invalid @enderror" wire:model.lazy="level_study_id" id="level_study_id">
                            <option value="">Seleccione un nivel de estudio</option>
                            @foreach($level_studies as $level)
                                <option value="{{$level['id']}}">{{$level['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('level_study_id') <div class="invalid-feedback">Nivel de estudio es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <div class="form-check form-check-inline" style="padding-top: 32px;">
                        <input wire:model.lazy="disabled" class="form-check-input @error('disabled') is-invalid @enderror" type="checkbox" id="disabled" value="first_checkbox" />
                        <label class="form-check-label" for="disabled">Discapacitado</label>
                    </div>
                    @error('disabled') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="social_security_number">Nº Seguridad Social</label>
                    <input wire:model.lazy="social_security_number" type="text" class="form-control @error('social_security_number') is-invalid @enderror" id="social_security_number" placeholder="Nº Seguridad Social">
                    @error('social_security_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="c_quote">C. Cotización</label>
                    <input wire:model.lazy="c_quote" type="text" class="form-control @error('c_quote') is-invalid @enderror" id="c_quote" placeholder="C. Cotización">
                    @error('c_quote') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <div class="mb-1">
                        <label class="form-label" for="quote_group_id">Grupo Cotización</label>
                        <select class="form-select select2 @error('quote_group_id') is-invalid @enderror" wire:model.lazy="quote_group_id" id="quote_group_id">
                            <option value="">Seleccione un grupo de cotización</option>
                            @foreach($quote_groups as $quote_group)
                                <option value="{{$quote_group['id']}}">{{$quote_group['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('quote_group_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <div class="mb-1">
                        <label class="form-label" for="professional_category_id">Categoria Profesional</label>
                        <select class="form-select select2 @error('professional_category_id') is-invalid @enderror" wire:model.lazy="professional_category_id" id="professional_category_id">
                            <option value="">Seleccione un categoria profesional</option>
                            @foreach($professional_categories as $category)
                                <option value="{{$category['id']}}">{{$category['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('professional_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="annual_gross_salary">Salario Bruto Anual</label>
                    <input wire:model.lazy="annual_gross_salary" type="num" class="form-control @error('annual_gross_salary') is-invalid @enderror" id="annual_gross_salary" placeholder="Salario Bruto Anual">
                    @error('annual_gross_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="annual_hours">Horas Anuales</label>
                    <input wire:model.lazy="annual_hours" type="num" class="form-control @error('annual_hours') is-invalid @enderror" id="annual_hours" placeholder="Horas Anuales">
                    @error('annual_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="hourly_cost_worker_gross">Coste Hora Bruto del Trabajador €/h</label>
                    <input wire:model.lazy="hourly_cost_worker_gross" type="num" class="form-control @error('hourly_cost_worker_gross') is-invalid @enderror" id="hourly_cost_worker_gross" placeholder="Coste Hora Bruto del Trabajador">
                    @error('hourly_cost_worker_gross') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="direction">Dirección</label>
                    <input wire:model.lazy="direction" type="text" class="form-control @error('direction') is-invalid @enderror" id="direction" placeholder="Dirección">
                    @error('direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="post_code">Código Postal</label>
                    <input wire:model.lazy="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" id="post_code" placeholder="Código postal">
                    @error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <label class="form-label" for="province_id">Provincia</label>
                    <select class="form-select select2" wire:model.lazy="province_id" id="province_id">
                        <option value="-1">Seleccione una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                    @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="population">Población</label>
                    <input wire:model.lazy="population" type="text" class="form-control @error('population') is-invalid @enderror" id="population" placeholder="Población">
                    @error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="iban">Iban</label>
                    <input wire:model.lazy="iban" type="text" class="form-control @error('iban') is-invalid @enderror" id="iban" placeholder="Iban">
                    @error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-1">
                    <label class="form-label" for="observation">Observación</label>
                    <textarea wire:model.lazy="observation" class="form-control @error('observation') is-invalid @enderror" rows="4" placeholder="observaciones"></textarea>
                    @error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-outlined-secondary">Volver</a>
            <button id="save" type="button" wire:click.prevent="store()" class="btn btn-primary me-1">Guardar</button>
        </div>
    </form>
    @section('vendor-script')
    <!-- vendor files -->
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    @endsection
    @section('page-script')
    <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-toastr.js') }}"></script>
    @endsection
    @section('scripts')
    <script>
        Livewire.on('alreadyExists', type => {
            text = '';
            if (type == 'dni'){
                text = 'DNI';
            } else if (type == 'user'){
                text = 'usuario'
            }
            Swal.fire({
                icon: 'error',
                title: 'Ya Existe',
                text: '¡Ya existe un alumno con ese '+text+'!',
            })
        })
        Livewire.on('toastr', type => {
            if (type == 'success'){
                toastr['success']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            } else{
                toastr['warning']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            }
        })
        document.addEventListener('livewire:load', function() {
            $( document ).ready(
                setTimeout(function (){
                    initializeSelect2()
                }, 100)
            );
            $('.select2').on('change', function(){
            @this.set(this.id, $(this).val())
            })
            $('#annual_hours').on('change', function(){
                Livewire.emit('calculateHourlyCost');
            })
            $('#annual_gross_salary').on('change', function(){
                Livewire.emit('calculateHourlyCost');
            })
        })
        $('body').on('click', '#save', function(){
            content = ''
           if ($('#name').val() == ''){
              content += 'El nombre es requerido<br>'
           }
           if ($('#surname').val() == ''){
               content += 'El apellido es requerido<br>'
           }
           if ($('#dni').val() == ''){
               content += 'El DNI es requerido<br>'
           }
           if ($('#telephone').val() == ''){
               content += 'El telefono es requerido<br>'
           }
           if ($('#email').val() == ''){
               content += 'El correo es requerido<br>'
           }
           if ($('#user').val() == ''){
               content += 'El usuario es requerido<br>'
           }
           if ($('#level_study_id').val() == ''){
               content += 'El nivel de estudio es requerido<br>'
           }
           if ($('#password').val()== ''){
               content += 'La contraseña es requerido<br>'
           }
           if ($('#company_id').val() == ''){
               content += 'La empresa es requerido'
           }
           if (content != ''){
               Swal.fire({
                   icon: 'error',
                   title: 'Falta datos',
                   html: '<div>'+content+'</div>',
                   customClass: {
                       confirmButton: 'btn btn-primary'
                   },
                   buttonsStyling: false
               });
           }
        })
    </script>
    @endsection
</div>
