<div class="card-body">
    <form class="form needs-validation" novalidate>
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
        </div>
        <div class="col-12">
            <a href="{{url('/certifications')}}" class="btn btn-outlined-secondary">Volver</a>
            <button id="save" type="button" wire:click.prevent="store()" class="btn btn-primary">Guardar</button>
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
            document.addEventListener('livewire:load', function() {
                initializeSelect2()
                $('.select2').on('change', function(){
                @this.set(this.id, $(this).val())
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
