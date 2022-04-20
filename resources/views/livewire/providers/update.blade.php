<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Editar Proveedor</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
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
                                <label for="type_id">Tipo</label>
                                <select wire:model.lazy="type_id" class="form-control select2" id="type_id">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($company_types as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group  col-sm-4">
                            <div wire:ignore>
                                <label for="activity_id">Actividad</label>
                                <select wire:model.lazy="activity_id" class="form-control select2" id="activity_id">
                                    <option value="">Seleccione una actividad</option>
                                    @foreach($company_activities as $activity)
                                        <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('activity_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
                        <div class="form-group col-sm-4">
                            <div wire:select>
                                <label for="cnae_id">Cnae</label>
                                <select wire:model.lazy="cnae_id" class="form-control select2" id="cnae_id">
                                    <option value="">Seleccione una cnae</option>
                                    @foreach($cnaes as $cnae)
                                        <option value="{{$cnae['id']}}">{{$cnae['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('cnae_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
                            <div wore:ignore>
                                <label for="province_id">Provincia</label>
                                <select wire:model.lazy="province_id" class="form-control select2" id="province_id">
                                    <option value="">Seleccione una provincia</option>
                                    @foreach($provinces as $province)
                                        <option value="{{$province['id']}}">{{$province['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('province_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="population">Población</label>
                            <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población">@error('population') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <br>
                            <label for="active"><input wire:model.lazy="active" id="active" type="checkbox" value="active"> Activo</label>
                            @error('active') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <div wire:ignore>
                                <label for="advisor_id">Asesoria</label>
                                <select wire:model.lazy="advisor_id" class="form-control select2" id="advisor_id" placeholder="Advisor Id">
                                    <option value="-1">Selección una Asesoria</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('advisor_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="irpf">Irpf</label>
                            <input wire:model.lazy="irpf" type="text" class="form-control" id="irpf" placeholder="Irpf">@error('irpf') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="commission">Commisiones</label>
                            <input wire:model.lazy="commission" type="text" class="form-control" id="commission" placeholder="Commisiones">@error('commission') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="contact_1">Contacto 1</label>
                            <input wire:model.lazy="contact_1" type="text" class="form-control" id="contact_1" placeholder="Contacto 1">@error('contact_1') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="contact_2">Contacto 2</label>
                            <input wire:model.lazy="contact_2" type="text" class="form-control" id="contact_2" placeholder="Contacto 2">@error('contact_2') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="contact_3">Contacto 3</label>
                            <input wire:model.lazy="contact_3" type="text" class="form-control" id="contact_3" placeholder="Contacto 3">@error('contact_3') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
       </div>
        <script>
            document.addEventListener('livewire:load', function() {
                $('body').on('show.bs.modal', '#updateModal', function (e) {
                    setTimeout(function () {
                        initializeSelect2()
                    }, 100)
                })
                $('.select2').on('change', function(){
                @this.set(this.id, $(this).val())
                })
            })
        </script>
    </div>
</div>
