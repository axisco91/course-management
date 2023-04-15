<div class="card-body">
    <form class="form needs-validation" novalidate>
        <div class="col-12 mb-1">
            <a href="{{url('/training_units/edit/'.$this->selected_id)}}" class="btn btn-success right">Editar</a>
        </div>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="mb-1">
                    <label class="form-label" for="formative_unit">Unidad Formativa</label>
                    <input wire:model.lazy="formative_unit" type="text" class="form-control" id="formative_unit" placeholder="Unidad Formativa" disabled>
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
                    <label class="form-label" for="face_to_face_hours">Horas Presenciales</label>
                    <input wire:model.lazy="face_to_face_hours" type="text" class="form-control" id="face_to_face_hours" placeholder="Horas Presenciales" disabled>
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
                    <input wire:model.lazy="total_hours" type="text" class="form-control" id="total_hours" placeholder="Horas Totales" disabled>
                </div>
            </div>
            <div class="col-md-2 col-12">
                <div class="mb-1">
                    <br>
                    <label class="form-labe" for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}} disabled> Activo</label>
                </div>
            </div>
        </div>
    </form>
</div>
