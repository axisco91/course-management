<div class="card-body">
    <form class="form needs-validation" novalidate>
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="formative_module">Modulo Formativo</label>
                    <input wire:model.lazy="formative_module" type="text" class="form-control @error('formative_module') is-invalid @enderror" id="formative_module" placeholder="Modulo Formativo">@error('formative_module') <div class="invalid-feedback">Modulo Formativo es requerido</div> @enderror
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
                    <label class="form-label" for="tutoring_hours">Horas Tutorías</label>
                    <input wire:model.lazy="tutoring_hours" type="text" class="form-control @error('tutoring_hours') is-invalid @enderror" id="tutoring_hours" placeholder="Horas Tutorias">
                    @error('tutoring_hours') <div class="invalid-feedback">Horas Tutorias es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="exam_hours">Horas Examen</label>
                    <input wire:model.lazy="exam_hours" type="text" class="form-control @error('exam_hours') is-invalid @enderror" id="exam_hours" placeholder="Horas Examen">
                    @error('exam_hours') <div class="invalid-feedback">Horas Examen es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="face_to_face_hours">Horas Presenciales</label>
                    <input wire:model.lazy="face_to_face_hours" type="text" class="form-control @error('face_to_face_hours') is-invalid @enderror" id="face_to_face_hours" placeholder="Horas Presenciales" disabled>
                    @error('face_to_face_hours') <div class="invalid-feedback">Horas presenciales es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="teletraining_hours">Horas Teleformaicón</label>
                    <input wire:model.lazy="teletraining_hours" type="text" class="form-control @error('teletraining_hours') is-invalid @enderror" id="teletraining_hours" placeholder="Horas Teleformaicón">
                    @error('teletraining_hours') <div class="invalid-feedback">Horas Teleformaicón es requerido</div> @enderror
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="total_hours">Horas Totales</label>
                    <input wire:model.lazy="total_hours" type="text" class="form-control @error('total_hours') is-invalid @enderror" id="total_hours" disabled placeholder="Horas Totales">@error('total_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
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
            <a href="{{url('/modules')}}" class="btn btn-outlined-secondary">Volver</a>
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
</div>
