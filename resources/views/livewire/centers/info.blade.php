<!-- Modal -->
<div wire:ignore.self class="modal fade" id="centersTabModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-student-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{$this->name}}</h1>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container-fluid active" id="general">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="name">Nombre</label>
                                <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre" disabled>@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="address">Dirección</label>
                                <input wire:model="address" type="text" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Dirección" disabled>@error('address') <div class="invalid-feedback">Dirección es requerido</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">Correo</label>
                                <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Correo" disabled>@error('email') <div class="invalid-feedback">Correo es requerido</div> @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telephone">Telefono</label>
                                <input wire:model="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" placeholder="Telefono" disabled>@error('telephone') <div class="invalid-feedback">Telefono es requerido</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
