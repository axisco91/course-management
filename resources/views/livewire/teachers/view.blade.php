<div class="card-body">
    <div class="col-12 mb-1">
        <a href="{{url('/teachers/edit/'.$this->selected_id)}}" class="btn btn-success right">Editar</a>
    </div>
    <form class="form">
        <input type="hidden" wire:model.lazy="selected_id">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre" disabled>
                    @error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="surname">Apellidos</label>
                    <input wire:model.lazy="surname" type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" placeholder="Apellidos" disabled>
                    @error('surname') <div class="invalid-feedback">Apellidos es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="dni">DNI</label>
                    <input wire:model.lazy="dni" type="text" class="form-control" id="dni" placeholder="Dni" disabled>
                    @error('dni') <div class="invalid-feedback">DNI es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="email">Correo</label>
                    <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo" disabled>
                    @error('email') <div class="invalid-feedback">Correo es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="telephone">Telefono</label>
                    <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono" disabled>
                    @error('telephone') <div class="invalid-feedback">Telefono es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="user">Usuario</label>
                    <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario" disabled>
                    @error('user') <div class="invalid-feedback">Usuario es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="password">Contraseña</label>
                    <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña" disabled>
                    @error('user') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                </div>
            </div>
           <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="teacher_area_id">Área</label>
                    <select class="form-select select2" wire:model.lazy="teacher_area_id" id="teacher_area_id" multiple disabled>
                        <option value="">Selecciona áreas</option>
                        @foreach($teacher_areas as $area)
                            <option value="{{$area['id']}}">{{$area['name']}}</option>
                        @endforeach
                    </select>
                    @error('teacher_areas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
           </div>
            <div class="col-md-4 col-12">
               <div class="mb-1">
                    <label class="form-label" for="address">Dirección</label>
                    <input wire:model.lazy="address" type="text" class="form-control" id="address" placeholder="Dirección" disabled>@error('direction') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
            <div class="col-md-4 col-12">
               <div class="mb-1">
                    <label class="form-label" for="post_code">Código Postal</label>
                    <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal" disabled>@error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="province_id">Provincia</label>
                    <select class="form-select select2 @error('province-id') is-invalid @enderror" wire:model.lazy="province_id" id="province_id" disabled>
                        <option value="">Seleccione una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="population">Población</label>
                    <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población" disabled>@error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="iban">Iban</label>
                    <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban" disabled>@error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="mb-1">
                    <label class="form-label" for="observation">Observación</label>
                    <textarea wire:model.lazy="observations" class="form-control @error('observations') is-invalid @enderror" rows="4" id="observation" placeholder="observaciones" disabled></textarea>
                    @error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-12">

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
                text: '¡Ya existe un docente con ese '+text+'!',
            })
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
        })
    </script>
    @endsection
</div>
