<div class="card">
    <div class="card-header border-bottom">
        <h4 class="card-title">Acciones Formativas</h4>
        @if (session()->has('message'))
            <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
        @endif
        @if (session()->has('error'))
            <input hidden id="toastr" data-type="error" value="{{ session('error') }}">
        @endif
        @include('training-actions.info')
    </div>
    <!--Search Form -->
    <div class="card-body mt-2">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <label class="form-label">Acción Formativa:</label>
                <input wire:model="search_formative_actions" type="text" class="form-control dt-input" data-column="2" placeholder="Acción Formativa" data-column-index="1" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre:</label>
                <input wire:model="search_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_professional_family_id">Familia profesional</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_professional_family_id" class="form-select select2" id="search_professional_family_id" placeholder="Familia profesional Id">
                        <option value="">Selección una familia profesional</option>
                        @foreach($professional_families as $professional_family)
                            <option value="{{$professional_family['id']}}">{{$professional_family['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_professional_area_id">Area profesional</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_professional_area_id" class="form-select select2" id="search_professional_area_id" placeholder="Area profesional Id">
                        <option value="">Selección una area profesional</option>
                        @foreach($professional_areas as $professional_area)
                            <option value="{{$professional_area['id']}}">{{$professional_area['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_modality_id">Modalidad</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_modality_id" class="form-select select2" id="search_modality_id" placeholder="Modalidad">
                        <option value="">Selección una modalidad</option>
                        @foreach($modalities as $modality)
                            <option value="{{$modality['id']}}">{{$modality['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <label class="form-label" for="search_provider_id">Proveedor</label>
                <div wire:ignore>
                    <select wire:model.lazy="search_provider_id" class="form-select select2" id="search_provider_id" placeholder="Proveedor">
                        <option value="">Selección un proveedor</option>
                        @foreach($providers as $provider)
                            <option value="{{$provider['id']}}">{{$provider['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row g-1 mb-md-1">
            <div class="col-md-4">
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="inactiveFilter"><input wire:model="inactiveFilter" id="inactiveFilter" type="checkbox"> Mostrar inactivos</label>
            </div>
            <div class="col-md-4">
                <a wire:ignore class="btn btn-sm btn-info" href="{{url('/training-actions/create')}}">
                    <i data-feather="plus-circle" class="me-50"></i> Añadir Acción Formativa
                </a>
                <button wire:ignore class="btn btn-sm btn-success" wire:click.prevent="downloadExcel()">
                    <i class="fa-solid fa-download"></i>  Descargar Excel
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0" />
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Acción Formativa</th>
                    <th>Nombre</th>
                    <th>Horas</th>
                    <th>Familia Profesional</th>
                    <th>Area Profesional</th>
                    <th>Modalidad</th>
                    <th>Proveedor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainingActions as $row)
                <tr>
                    <th data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->formative_action }}</th>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->name }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->total_hours }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->professional_family }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->professional_area }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->modality }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})">{{ $row->provider }}</td>
                    <td data-bs-toggle="modal" data-bs-target="#trainingActionTabModal" wire:click="general({{$row->id}})"><span class="badge rounded-pill badge-light-{{$row->active == 0 ?'danger' : 'success'}} me-1">{{$row->active == 0 ? 'Inactivo' : 'Activo'}}</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                            <!--<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>-->
                                <a href="{{url('/training-actions/edit/'.$row->id)}}" class="dropdown-item edit"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                @if ($row->inactivo == 1)
                                    <a class="dropdown-item" onclick="confirm('¿Quieres volver a activar a {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Activar </a>
                                @else
                                    <a class="dropdown-item" onclick="confirm('¿Quieres desactivar a {{$row->name}}?')||event.stopImmediatePropagation()" wire:click="changeState({{$row->id}})"><i class="fa fa-active"></i> Desactivar </a>
                                @endif
                                <a class="dropdown-item" href="{{url('/courses/create/'.$row->id)}}"><i data-feather="plus-circle" class="me-50"></i> Crear Curso</a>
                            </div>
                        </div>
                    </td>
                @endforeach
            </tbody>
        </table>
        {{ $trainingActions->links() }}
        </div>
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
</div>
