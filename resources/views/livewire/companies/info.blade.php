<!-- Modal -->
<div wire:ignore.self class="modal fade" id="companiesTabModal" data-bs-backdrop="static" role="dialog" aria-labelledby="companiesTabModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companiesTabModalLabel">{{$this->name}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
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
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="name">Nombre</label>
                                <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre" disabled>@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="nif">CIF</label>
                                <input wire:model.lazy="nif" type="text" class="form-control" id="nif" placeholder="Cif" disabled>@error('nif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <div wire:ignore>
                                    <label class="form-label" for="type_id">Tipo</label>
                                    <select wire:model.lazy="type_id" class="form-control select2" id="type_id" disabled>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($company_types as $type)
                                            <option value="{{$type['id']}}">{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group  col-sm-12">
                                <div wire:ignore>
                                    <label class="form-label" for="activity_id">Actividad</label>
                                    <select wire:model.lazy="activity_id" class="form-control select2" id="activity_id" disabled>
                                        <option value="">Seleccione una actividad</option>
                                        @foreach($company_activities as $activity)
                                            <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('activity_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="email">Correo</label>
                                <input wire:model.lazy="email" type="email" class="form-control" id="email" placeholder="Correo" disabled>@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="telephone">Telefono</label>
                                <input wire:model.lazy="telephone" type="text" class="form-control" id="telephone" placeholder="Telefono" disabled>@error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="legal_representative">Representante Legal</label>
                                <input wire:model.lazy="legal_representative" type="text" class="form-control" id="legal_representative" placeholder="Representante Legal" disabled>@error('legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="dni_legal_representative">Dni representante legal</label>
                                <input wire:model.lazy="dni_legal_representative" type="text" class="form-control" id="dni_legal_representative" placeholder="Dni representante legal" disabled>@error('dni_legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="quote">C. cotización</label>
                                <input wire:model.lazy="quote" type="text" class="form-control" id="quote" placeholder="C. cotización" disabled>@error('quote') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="advisor_id">Asesoria</label>
                                <div wire:ignore>
                                    <select wire:model.lazy="advisor_id" class="form-control select2" id="advisor_id" disabled>
                                        <option value="">Selección una Asesoria</option>
                                        @foreach($advisors as $advisor)
                                            <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('advisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-sm-12">
                                <div wire:ignore>
                                    <label class="form-label" for="cnae_id">Cnae</label>
                                    <select wire:model.lazy="cnae_id" class="form-control select2" id="cnae_id" disabled>
                                        <option value="">Seleccione una cnae</option>
                                        @foreach($cnaes as $cnae)
                                            <option value="{{$cnae['id']}}">{{$cnae['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('cnae_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="average_template">Plantilla media</label>
                                <input wire:model.lazy="average_template" type="number" class="form-control" id="average_template" placeholder="Plantilla media" disabled>@error('average_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="iban">Iban</label>
                                <input wire:model.lazy="iban" type="text" class="form-control" id="iban" placeholder="Iban" disabled>@error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="sepa">Sepa</label>
                                <input wire:model.lazy="sepa" type="text" class="form-control" id="sepa" placeholder="Sepa" disabled>@error('sepa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="b2b">B2B</label>
                                <input wire:model.lazy="b2b" type="text" class="form-control" id="b2b" placeholder="B2B" disabled>@error('b2b') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="address">Dirección</label>
                                <input wire:model.lazy="address" type="text" class="form-control" id="address" placeholder="Dirección" disabled>@error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="post_code">Código postal</label>
                                <input wire:model.lazy="post_code" type="text" class="form-control" id="post_code" placeholder="Código postal" disabled>@error('post_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <div wire:ignore>
                                    <label class="form-label" for="province_id">Provincia</label>
                                    <select wire:model.lazy="province_id" class="form-control select2" id="province_id" disabled>
                                        <option value="">Seleccione una provincia</option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('province_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="population">Población</label>
                                <input wire:model.lazy="population" type="text" class="form-control" id="population" placeholder="Población" disabled>@error('population') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 col-12">
                                <br>
                                <label class="form-label" for="active"><input wire:model.lazy="active" id="active" type="checkbox" value="active" disabled> Activo</label>
                                @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-sm-12">
                                Observaciones
                                @if(isset($observations))
                                    @foreach($observations as $row)
                                        <div class="form-group col-sm-12">
                                            <textarea wire:model.lazy="observation" class="form-control" placeholder="{{$row->observation}}"></textarea>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
