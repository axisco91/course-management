<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="code">Código</label>
                    <input wire:model.lazy="code" type="text" class="form-control @error('code') is-invalid @enderror" id="code" placeholder="Código">@error('code') <div class="invalid-feedback">Código es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre">@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="total_hours">Horas Totales</label>
                    <input wire:model.lazy="total_hours" type="text" class="form-control @error('total_hours') is-invalid @enderror" id="total_hours" disabled placeholder="Horas Totales">@error('total_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="professional_family_id">Familia</label>
                    <select wire:model.lazy="professional_family_id" class="form-select select2 @error('professional_family_id') is-invalid @enderror" id="professional_family_id">
                        <option value="-1">Seleccione una familia profeccional</option>
                        @foreach($professional_families as $professional_family)
                            <option value="{{$professional_family['id']}}">{{$professional_family['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('professional_family_id') <div class="invalid-feedback">Colaborador es requerido</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="professional_area_id">Area</label>
                    <select wire:model.lazy="professional_area_id" class="form-select select2 @error('professional_area_id') is-invalid @enderror" id="professional_area_id">
                        <option value="-1">Seleccione una area profeccional</option>
                        @foreach($professional_areas as $professional_area)
                            <option value="{{$professional_area['id']}}">{{$professional_area['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('professional_area_id') <div class="invalid-feedback">Colaborador es requerido</div> @enderror
            </div>
            <div class="col-md-3 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="level">Nivel</label>
                    <select wire:model.lazy="level" class="form-select select2 @error('level') is-invalid @enderror" id="level">
                        <option value="-1">Seleccione un nivel</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                @error('level') <div class="invalid-feedback">Nivel es requerido</div> @enderror
            </div>
            <div class="col-md-2 col-12">
                <div class="mb-1">
                    <br>
                    <label class="form-labe" for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}}> Activo</label>
                    @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-12">
                <a href="{{url('/certifications')}}" class="btn btn-outlined-secondary">Volver</a>
                <button id="save" type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
            </div>
            <br><br><br>
            <div class="col-md-4 col-12 mb-2">
                <div wire:ignore>
                    <label class="form-label" for="training_unit_id">Unidad Formativa</label>
                    <select wire:model.lazy="training_unit_id" class="form-control select2 list" id="training_unit_id">
                        <option value="">Seleccione una unidad formativa</option>
                        @foreach($training_units as $training_unit)
                            <option value="{{$training_unit['id']}}">{{$training_unit['formative_action']}} {{$training_unit['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="module_id">Modulo</label>
                    <select wire:model.lazy="module_id" class="form-control select2 list" id="module_id">
                        <option value="">Seleccione un modulo</option>
                        @foreach($modules as $module)
                            <option value="{{$module['id']}}">{{$module['formative_module']}} {{$module['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 mb-2">
                @foreach($certification_elements as $certification_element)
                    <div class="" style="display: flex; background: white; margin: 5px; padding: 5px; justify-content: space-between;">
                        @if ($certification_element['training_unit_id'])
                            <div wire:ignore>
                            <div style="margin-top: 5px;">
                                {{$certification_element['formative_unit']}} - {{$certification_element['training_unit_name']}}
                            </div>
                            </div>
                            <div>
                                <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$certification_element->id}})"><i class="fas fa-minus"></i></div>
                            </div>
                        @elseif($certification_element['module_id'])
                            <div wire:ignore>
                            <div style="margin-top: 5px;">
                                {{$certification_element['formative_module']}} - {{$certification_element['module_name']}}
                            </div>
                            </div>
                            <div>
                                <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$certification_element->id}})"><i class="fas fa-minus"></i></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
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
            $('.list').on('change', function(){
            @this.set(this.id, $(this).val())
                id = $(this).val();
                type = $(this).attr('id');
                Livewire.emit('addElement', id, type)
            })
        })
        $('body').on('click', '#save', function(){
            content = ''
            if ($('#name').val() == ''){
                content += 'El nombre es requerido<br>'
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
