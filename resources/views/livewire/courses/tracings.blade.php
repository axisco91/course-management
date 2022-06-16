<!-- Modal -->
<div wire:ignore.self class="modal fade" id="tracingsModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="tracingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tracingsModalLabel">Seguimiento</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session()->has('message'))
                    <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                @endif
                <div class="table-responsive" style=" overflow-x: auto;">
                    @if(isset($tracings))
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                            <tr>
                                <td>#</td>
                                <th>Alumno</th>
                                <th>Empresa</th>
                                <th>Actividades Realizadas</th>
                                <th>Horas Realizadas</th>
                                <th>Unidades Realizadas</th>
                                <th>Fecha de Seguimiento</th>
                                <th>Evaluación Final</th>
                                <td>Acciones</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tracings as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->student }}</td>
                                    <td>{{ $row->company }}</td>
                                    <td>{{ $row->performed_activities }}</td>
                                    <td>{{ $row->performed_hours }}</td>
                                    <td>{{ $row->performed_units }}</td>
                                    <td>{{ $row->follow_up_date }}</td>
                                    <td>{{ $row->final_test }}</td>
                                    <td width="90">
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Acciones
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a data-bs-toggle="modal" data-bs-target="#updateTracings" class="dropdown-item" wire:click="editTracing({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
                                            </div>
                                        </div>
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
              <div class="col-12 text-center mt-2 pt-50">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
