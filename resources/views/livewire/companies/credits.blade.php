<!-- Modal -->
<div wire:ignore.self class="modal fade" id="creditsModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="creditsDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="creditsModalLabel">Creditos</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session()->has('message'))
                    <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                @endif
                <div class="table-responsive">
                    @if(isset($credits))
                    <table class="table table-bordered table-sm">
                        <thead class="thead">
                        <tr>
                            <td>#</td>
                            <th>Crédito Disponible</th>
                            <th>Crédito Consumido</th>
                            <th>Crédito Restante</th>
                            <td>Acciones</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($credits as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->available_credit }}</td>
                                <td>{{ $row->consumed_credit }}</td>
                                <td>{{ $row->year }}</td>
                                <td width="90">
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a data-bs-toggle="modal" data-bs-target="#updateCredit" class="dropdown-item" wire:click="editCredit({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
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
