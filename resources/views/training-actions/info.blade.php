<!-- Modal -->
<div wire:ignore.self class="modal fade" id="trainingActionTabModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-student-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{$this->formative_action}} - {{$this->name}}</h1>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" wire:click="getInfo({{$this->selected_id}})" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" wire:click="editAction({{$this->selected_id}})" data-bs-toggle="tab" href="#edit">Editar</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane container active" id="general">
                        @livewire('training-actions-info')
                    </div>
                    <div class="tab-pane container" id="edit">
                        @livewire('training-actions-edit')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
