<!-- Modal -->
<div wire:ignore.self class="modal fade" id="registrationsModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="registrarionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrationsModalLabel">matriculaciones</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" wire:model="selected_id">
                <div class="card-body row">
                    <div class="col">
                        <h4>Alumnos no matriculados</h4>
                        <div class="unregisterd_students">
                            @if(isset($students))
                                @foreach($students as $student)
                                    <div class="unregisterd">
                                        <div class="name">{{$student['name'].' '.$student['surname']}}</div>
                                        <div class="btn btn-success btn-sm register" wire:click="register({{$student->id}})"><i class="fas fa-plus"></i></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <h4>Alumnos matriculados</h4>
                        <div class="registerd_students">
                            @if(isset($registrations))
                                @foreach($registrations as $registrated)
                                    <div class="registerd">
                                        <div class="name">{{$registrated['name'].' '.$registrated['surname']}}</div>
                                        <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$registrated->student_id}})"><i class="fas fa-minus"></i></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
