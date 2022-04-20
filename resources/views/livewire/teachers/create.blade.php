<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Docente</h5>
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
                            <label for="email">Correo</label>
                            <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo">@error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="telephone">Telefono</label>
                            <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono">@error('telephone') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="user">Usuario</label>
                            <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario">@error('user') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="password">Contraseña</label>
                            <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña">@error('user') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4" wire:ignore>
                            <label for="create_teacher_area_id">Área</label>
                            <select class="form-control selectCreate" wire:model.lazy="create_teacher_area_id" id="create_teacher_area_id" multiple>
                                <option value="-1">Selecciona áreas</option>
                                @foreach($teacher_areas as $area)
                                    <option value="{{$area['id']}}">{{$area['name']}}</option>
                                @endforeach
                            </select>
                            @error('teacher_areas') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="address">Dirección</label>
                            <input wire:model.lazy="address" type="text" class="form-control" id="address" placeholder="Dirección">@error('direction') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="post_code">Código Postal</label>
                            <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal">@error('post_code') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4" wire:ignore>
                            <label for="create_province_id">Provincia</label>
                            <select class="form-control selectCreate" wire:model.lazy="create_province_id" id="create_province_id">
                                <option value="">Seleccione una provincia</option>
                                @foreach($provinces as $province)
                                    <option value="{{$province['id']}}">{{$province['name']}}</option>
                                @endforeach
                            </select>
                            @error('province_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
                            <label for="observations">Observaciones</label>
                            <textarea wire:model.lazy="observations" class="form-control" id="observations" placeholder="Observaciones"></textarea>@error('observations') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">guardar</button>
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
