<div class="card-body">
    <div class="col-12 mb-1">
        <a href="{{url('/modules/edit/'.$this->selected_id)}}" class="btn btn-success right">Editar</a>
    </div>
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="formative_module">Modulo Formativo</label>
                    <input wire:model.lazy="formative_module" type="text" class="form-control" id="formative_module" placeholder="Modulo Formativo" disabled>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="name">Nombre</label>
                    <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre" disabled>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="face_to_face_hours">Horas Presenciales</label>
                    <input wire:model.lazy="face_to_face_hours" type="text" class="form-control" id="face_to_face_hours" placeholder="Horas Presenciales" disabled>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="tutoring_hours">Horas Tutorías</label>
                    <input wire:model.lazy="tutoring_hours" type="text" class="form-control" id="tutoring_hours" placeholder="Horas Tutorias" disabled>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="exam_hours">Horas Examen</label>
                    <input wire:model.lazy="exam_hours" type="text" class="form-control" id="exam_hours" placeholder="Horas Examen" disabled>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="teletraining_hours">Horas Teleformaicón</label>
                    <input wire:model.lazy="teletraining_hours" type="text" class="form-control" id="teletraining_hours" placeholder="Horas Teleformaicón" disabled>
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
                    <label class="form-labe" for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}} disabled> Activo</label>
                    @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-12">

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
