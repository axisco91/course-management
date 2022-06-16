@section('title', __('Company Observations'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="fab fa-laravel text-info"></i>
							Company Observation Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Company Observations">
						</div>
						<div class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#createDataModal">
						<i class="fa fa-plus"></i>  Add Company Observations
						</div>
					</div>
				</div>

				<div class="card-body">
						@include('livewire.companyObservations.create')
						@include('livewire.companyObservations.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr>
								<td>#</td>
								<th>Company Id</th>
								<th>Observation</th>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							@foreach($companyObservations as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row->company_id }}</td>
								<td>{{ $row->observation }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Acciones
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-bs-toggle="modal" data-bs-target="#updateModal" class="dropdown-item" wire:click="Editar({{$row->id}})"><i class="fa-regular fa-pen-to-square"></i> Editar </a>
									<a class="dropdown-item eliminar" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Eliminar </a>
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>
					{{ $companyObservations->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                $('body').on('click', '.eliminar', function () {
                    button = $(this)
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: '¿Estas seguro?',
                        text: "Eliminaras a la observación de la empresa!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si, eliminalo!',
                        cancelButtonText: 'No, cancela!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            id = $(this).data('id');
                            Livewire.emit('destroy', id)
                            window.addEventListener('eliminated', e=>{
                                if (e.detail.value != ''){
                                    swalWithBootstrapButtons.fire(
                                        'Eliminado!',
                                        'Eliminado con exito.',
                                        'success'
                                    )
                                } else{
                                    swalWithBootstrapButtons.fire(
                                        'Error',
                                        'Fallo al eliminar.',
                                        'error'
                                    )
                                }
                            });

                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swalWithBootstrapButtons.fire(
                                'Cacelado',
                                'No se ha podido eliminar.',
                                'error'
                            )
                        }
                    })
                })
            })
        </script>
    @endsection
</div>
