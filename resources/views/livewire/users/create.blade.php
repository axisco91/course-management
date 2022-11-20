<div wire:ignore.self class="modal fade" id="createDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Crear Usuario</h1>
                </div>
                <form id="createUserForm" class="row gy-1 pt-75" onsubmit="return false">
                    <input type="hidden" wire:model="selected_id">
                    <div class="row">
                       <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="name">Nombre</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                       <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="surname">Apellidos</label>
                            <input wire:model="surname" type="text" class="form-control" id="surname" placeholder="Apellidos">@error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                       <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="username">Usuario</label>
                            <input wire:model="username" type="text" class="form-control" id="username" placeholder="Usuario">@error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                       <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="email">Correo</label>
                            <input wire:model="email" type="text" class="form-control" id="email" placeholder="Correo">@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                       <div class="col-md-4 col-12 mb-1">
                            <label class="form-label" for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                            <input wire:model="password" id="password" type="password" class="form-control" name="password" placeholder="Contraseña">@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                       <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="create_role_id">Rol</label>
                                <select class="form-select select2" wire:model.lazy="create_role_id" id="create_role_id">
                                    <option value="">Seleccione un rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role['id']}}">{{$role['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-3 mb-1">
                            <br>
                            <label class="form-label" for="has_commission"><input wire:model.lazy="has_commission" id="has_commission" type="checkbox"> Comisiona</label>
                            @error('has_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label" for="commission">Comisión</label>
                            <input wire:model="commission" type="number" class="form-control" id="commission" placeholder="Comisión">@error('commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                text: '¡Ya existe un usuario con ese '+text+'!',
            })
        })
        document.addEventListener('livewire:load', function(){
            $('.select2').select2()
            $('.select2').on('change', function(){
            @this.set(this.id, $(this).val())
            })
        })
    </script>
    @endsection
</div>
