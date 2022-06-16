<!-- Modal -->
<div wire:ignore.self class="modal fade" id="choresModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="choresModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="choresModalLabel">Tareas</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session()->has('message'))
                    <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                @endif
                <div class="table-responsive" style=" overflow-x: auto;">
                    @if(isset($chores))
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                            <tr>
                                <td>#</td>
                                <th>Student Id</th>
                                <th>Company Id</th>
                                <th>Membership Tab Status</th>
                                <th>Membership Tab Date</th>
                                <th>Economic Proposal Status</th>
                                <th>Economic Proposal Date</th>
                                <th>Student Tab Status</th>
                                <th>Student Tab Date</th>
                                <th>Diploma Status</th>
                                <th>Diploma Status Date</th>
                                <th>Invoiced Status</th>
                                <th>Invoiced Date</th>
                                <td>Acciones</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($chores as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->student_name }}</td>
                                    <td>{{ $row->company_name }}</td>
                                    <td>{{ $row->membership_tab_status }}</td>
                                    <td>{{ $row->membership_tab_date }}</td>
                                    <td>{{ $row->economic_proposal_status }}</td>
                                    <td>{{ $row->economic_proposal_date }}</td>
                                    <td>{{ $row->student_tab_status }}</td>
                                    <td>{{ $row->student_tab_date }}</td>
                                    <td>{{ $row->diploma_status }}</td>
                                    <td>{{ $row->diploma_status_date }}</td>
                                    <td>{{ $row->invoiced_status }}</td>
                                    <td>{{ $row->invoiced_date }}</td>
                                    <td width="90">
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Acciones
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a data-bs-toggle="modal" data-bs-target="#updateChore" class="dropdown-item" wire:click="editChore({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
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
