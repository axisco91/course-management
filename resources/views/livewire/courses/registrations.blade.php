<div wire:ignore.self class="modal fade" id="registrationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Matriculaciones</h1>
                </div>
                <input type="hidden" wire:model="selected_id">
                <div class="card-body row">
                    <div class="col">
                        <h4>Alumnos no matriculados</h4>
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input wire:change="findUnregisterd()" wire:model="search_name_unregisterd" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos:</label>
                            <input wire:model="search_surname" type="text" class="form-control dt-input" data-column="2" placeholder="Apellidos" data-column-index="1" />
                        </div>
                        <div class="unregisterd_students" style="overflow-y:auto;">
                            @if(isset($students))
                                @foreach($students as $student)
                                    <div class="unregisterd">
                                        <div class="name">{{$student['name'].' '.$student['surname']}} ({{$student['dni']}})</div>
                                        <div class="btn btn-success btn-sm register" wire:click="register({{$student->id}})"><i class="fas fa-plus"></i></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h4>Alumnos matriculados</h4>
                        <div class="col-md-6">
                            <label class="form-label">Nombre:</label>
                            <input wire:model="search_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos:</label>
                            <input wire:model="search_surname" type="text" class="form-control dt-input" data-column="2" placeholder="Apellidos" data-column-index="1" />
                        </div>
                        <div class="registerd_students" style="overflow-y:auto;">
                            @if(isset($registrations))
                                @foreach($registrations as $registrated)
                                    <div class="registerd">
                                        <div class="name">{{$registrated['name'].' '.$registrated['surname']}} ({{$registrated['dni']}})</div>
                                        <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$registrated->student_id}})"><i class="fas fa-minus"></i></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
              <div class="col-12 text-center mt-2 pt-50">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
        </div>
    </div>
</div>
