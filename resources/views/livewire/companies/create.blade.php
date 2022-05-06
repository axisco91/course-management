<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Empresa</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="name">Nombre</label>
                            <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="nif">CIF</label>
                            <input wire:model.lazy="nif" type="text" class="form-control" id="nif" placeholder="Cif">@error('nif') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <div wire:ignore>
                                <label for="create_type_id">Tipo</label>
                                <select wire:model.lazy="create_type_id" class="form-control selectCreate" id="create_type_id">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($company_types as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_type_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group  col-sm-12">
                            <div wire:ignore>
                                <label for="create_activity_id">Actividad</label>
                                <select wire:model.lazy="create_activity_id" class="form-control selectCreate" id="create_activity_id">
                                    <option value="">Seleccione una actividad</option>
                                    @foreach($company_activities as $activity)
                                        <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_activity_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="email">Correo</label>
                            <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo">@error('email') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="telephone">Telefono</label>
                            <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono">@error('telephone') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="legal_representative">Representante Legal</label>
                            <input wire:model.lazy="legal_representative" type="text" class="form-control" id="legal_representative" placeholder="Representante Legal">@error('legal_representative') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="dni_legal_representative">Dni representante legal</label>
                            <input wire:model.lazy="dni_legal_representative" type="text" class="form-control" id="dni_legal_representative" placeholder="Dni representante legal">@error('dni_legal_representative') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="quote">C. cotización</label>
                            <input wire:model.lazy="quote" type="text" class="form-control" id="quote" placeholder="C. cotización">@error('quote') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4" wire:ignore>
                            <div>
                                <label for="create_advisor_id">Asesoria</label>
                                <select wire:model.lazy="create_advisor_id" class="form-control selectCreate" id="create_advisor_id" placeholder="Advisor Id">
                                    <option value="">Selección una Asesoria</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_advisor_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12">
                            <div wire:ignore>
                                <label for="create_cnae_id">Cnae</label>
                                <select wire:model.lazy="create_cnae_id" class="form-control selectCreate" id="create_cnae_id">
                                    <option value="">Seleccione una cnae</option>
                                    @foreach($cnaes as $cnae)
                                        <option value="{{$cnae['id']}}">{{$cnae['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_cnae_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="average_template">Plantilla media</label>
                            <input wire:model.lazy="average_template" type="number" class="form-control" id="average_template" placeholder="Plantilla media">@error('average_template') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="iban">Iban</label>
                            <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban">@error('iban') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="sepa">Sepa</label>
                            <input wire:model.lazy="sepa" type="text" class="form-control" id="sepa" placeholder="Sepa">@error('sepa') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="b2b">B2B</label>
                            <input wire:model.lazy="b2b" type="text" class="form-control" id="b2b" placeholder="B2B">@error('b2b') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="address">Dirección</label>
                            <input wire:model.lazy="address" type="text" class="form-control" id="address" placeholder="Dirección">@error('address') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="post_code">Código postal</label>
                            <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal">@error('post_code') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <div wire:ignore>
                                <label for="create_province_id">Provincia</label>
                                <select wire:model.lazy="create_province_id" class="form-control selectCreate" id="create_province_id">
                                    <option value="">Seleccione una provincia</option>
                                    @foreach($provinces as $province)
                                        <option value="{{$province['id']}}">{{$province['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('create_province_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="population">Población</label>
                            <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población">@error('population') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <br>
                            <label for="active"><input wire:model="active" id="active" type="checkbox" value="active"> Activo</label>
                            @error('active') <span class="error text-danger">{{ $message }}</span> @enderror
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
