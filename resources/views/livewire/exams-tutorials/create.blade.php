<div class="card-body">
    <form class="form needs-validation" novalidate>
        <div class="row">
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="type">Tipo</label>
                    <select wire:model.lazy="type" class="form-select select2 @error('type') is-invalid @enderror" id="type">
                        <option value="-1">Seleccione un tipo</option>
                        <option value="examen">Examen</option>
                        <option value="tutoria">Tutoria</option>
                    </select>
                </div>
                @error('type') <div class="invalid-feedback">Tipo es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="center_id">Centro</label>
                    <select wire:model.lazy="center_id" class="form-select select2 @error('type') is-invalid @enderror" id="center_id">
                        <option value="-1">Seleccione un centro</option>
                        @foreach($centers as $center)
                            <option value="{{$center['id']}}">{{$center['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('center_id') <div class="invalid-feedback">centro es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="date">Fecha</label>
                <input wire:model.lazy="date" type="date" class="form-control" id="date">@error('date') <div class="invalid-feedback">Fecha es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="beginning">Hora Inicio</label>
                <input wire:model.lazy="beginning" type="time" class="form-control" id="beginning">@error('beginning') <div class="invalid-feedback">Hora inicio es requerido</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="end">Hora Fin</label>
                <input wire:model.lazy="end" type="time" class="form-control" id="end">@error('end') <div class="invalid-feedback">Hora fin es requerido</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <a href="{{url('/training_contracts/second_phase/'.$this->selected_id)}}" class="btn btn-outlined-secondary">Volver</a>
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
            initializeSelect2()
            $('.select2').on('change', function(){
            @this.set(this.id, $(this).val())
            })
            $('#face_to_face_hours, #teletraining_hours').on('change', function(){
            @this.setTotalHours(Number($('#face_to_face_hours').val()), Number($('#teletraining_hours').val()))
            })
            $('#company_id').on('change', function(){
                Livewire.emit('getInfo')
            })
        })
        $('body').on('click', '#save', function(){
            content = ''
            if ($('#name').val() == ''){
                content += 'El nombre es requerido<br>'
            }
            if ($('#action_type_id').val() == ''){
                content += 'El tipo de acción es requerido<br>'
            }
            if ($('#modality_id').val() == ''){
                content += 'La modalidad es requerido<br>'
            }
            if ($('#training_action_level_id').val() == ''){
                content += 'El nivel es requerido<br>'
            }
            if ($('#tutoring_id').val() == ''){
                content += 'La tutoria es requerido<br>'
            }
            if ($('#face_to_face_hours').val() == ''){
                content += 'Las horas presenciales son requeridos<br>'
            }
            if ($('#teletraining_hours').val() == ''){
                content += 'Las horas de teletrabajo son requeridos<br>'
            }
            if ($('#price').val() == ''){
                content += 'El precio es requerido<br>'
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
