<!-- Modal -->
<div wire:ignore.self class="modal fade" id="moduleTabModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-student-tab">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" wire:click.prevent="cancel()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1"></h1>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'info' ? 'active' : '' }}" wire:click="$set('tab', 'info')" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                @if($tab == 'info')
                    <div class="tab-pane container-fluid active" id="general">
                        @include('livewire.modules.info')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
