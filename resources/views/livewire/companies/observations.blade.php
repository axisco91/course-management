<!-- Modal -->
<div wire:ignore.self class="modal fade" id="observationsModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="observationsModalLabel">Observaciones</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session()->has('message'))
                    <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                @endif
                <div class="table-responsive">
                    @if(isset($observations))
                    <table class="table table-bordered table-sm">
                        <thead class="thead">
                        <tr>
                            <td>#</td>
                            <th>Observación</th>
                            <th>Fecha</th>
                            <td>Acciones</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($observations as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->observation }}</td>
                                <td>{{ $row->date }}</td>
                                <td width="90">
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a data-bs-toggle="modal" data-bs-target="#updateObservation" class="dropdown-item" wire:click="editObservation({{$row->id}})"><i class="fa fa-edit"></i> Editar </a>
                                            <a class="dropdown-item" onclick="confirm('Confirmar eliminar observacion: {{$row->onservacion}}? \nNo se puede recuperar!')||event.stopImmediatePropagation()" wire:click="destroyObservation({{$row->id}})"><i class="fa fa-trash"></i> Eliminar </a>
                                        </div>
                                    </div>
                                </td>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
