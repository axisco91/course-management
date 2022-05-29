<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre">
                    @error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="nif">CIF</label>
                    <input wire:model.lazy="nif" type="text" class="form-control @error('nif') is-invalid @enderror" id="nif" placeholder="Cif">
                    @error('nif') <div class="invalid-feedback">CIF es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="type_id">Tipo</label>
                    <select wire:model.lazy="type_id" class="form-select select2 @error('type_id') is-invalid @enderror" id="type_id">
                        <option value="">Seleccione un tipo</option>
                        @foreach($company_types as $type)
                            <option value="{{$type['id']}}">{{$type['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('type_id') <div class="invalid-feedback">Tipo es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="activity_id">Actividad</label>
                    <select wire:model.lazy="activity_id" class="form-select select2 @error('activity_id') is-invalid @enderror" id="activity_id">
                        <option value="">Seleccione una actividad</option>
                        @foreach($company_activities as $activity)
                            <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('activity_id') <div class="invalid-feedback">Actividad es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="email">Correo</label>
                    <input wire:model.lazy="email" type="email" class="form-control @error('correo') is-invalid @enderror" id="email" placeholder="Correo">
                    @error('email') <div class="invalid-feedback">Correo es requerido</div> @enderror
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
                    <label class="form-label" for="legal_representative">Representante Legal</label>
                    <input wire:model.lazy="legal_representative" type="text" class="form-control" id="legal_representative" placeholder="Representante Legal">
                    @error('legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="dni_legal_representative">Dni representante legal</label>
                    <input wire:model.lazy="dni_legal_representative" type="text" class="form-control" id="dni_legal_representative" placeholder="Dni representante legal">
                    @error('dni_legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="quote">C. cotización</label>
                    <input wire:model.lazy="quote" type="text" class="form-control" id="quote" placeholder="C. cotización">@error('quote') <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <label class="form-label" for="advisor_id">Asesoria</label>
                    <select wire:model.lazy="advisor_id" class="form-select select2" id="advisor_id" placeholder="Advisor Id">
                        <option value="">Selección una Asesoria</option>
                        @foreach($advisors as $advisor)
                            <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('advisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="cnae_id">Cnae</label>
                    <select wire:model.lazy="cnae_id" class="form-select select2" id="cnae_id">
                        <option value="">Seleccione una cnae</option>
                        @foreach($cnaes as $cnae)
                            <option value="{{$cnae['id']}}">{{$cnae['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('cnae_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="average_template">Plantilla media</label>
                    <input wire:model.lazy="average_template" type="number" class="form-control" id="average_template" placeholder="Plantilla media">@error('average_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="iban">Iban</label>
                    <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban">@error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="sepa">Sepa</label>
                    <input wire:model.lazy="sepa" type="text" class="form-control" id="sepa" placeholder="Sepa">@error('sepa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="b2b">B2B</label>
                    <input wire:model.lazy="b2b" type="text" class="form-control" id="b2b" placeholder="B2B">@error('b2b') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="address">Dirección</label>
                    <input wire:model.lazy="address" type="text" class="form-control" id="address" placeholder="Dirección">@error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="post_code">Código postal</label>
                    <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal">@error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="province_id">Provincia</label>
                    <select wire:model.lazy="province_id" class="form-select select2 @error('province_id') is-invalid @enderror" id="province_id">
                        <option value="">Seleccione una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('province_id') <div class="invalid-feedback">Provincia es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="population">Población</label>
                    <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población">@error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <div class="form-check form-check-inline" style="padding-top: 32px;">
                        <input wire:model.lazy="active" class="form-check-input @error('active') is-invalid @enderror" type="checkbox" id="active" value="active" />
                        <label class="form-check-label" for="active">Activo</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="irpf">Irpf</label>
                    <input wire:model.lazy="irpf" type="text" class="form-control" id="irpf" placeholder="Irpf">@error('irpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="commission">Commisiones</label>
                    <input wire:model.lazy="commission" type="text" class="form-control" id="commission" placeholder="Commisiones">@error('commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="contact_1">Contacto 1</label>
                    <input wire:model.lazy="contact_1" type="text" class="form-control" id="contact_1" placeholder="Contacto 1">@error('contact_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="contact_2">Contacto 2</label>
                    <input wire:model.lazy="contact_2" type="text" class="form-control" id="contact_2" placeholder="Contacto 2">@error('contact_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="contact_3">Contacto 3</label>
                    <input wire:model.lazy="contact_3" type="text" class="form-control" id="contact_3" placeholder="Contacto 3">@error('contact_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
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

    <script>
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
</div>
