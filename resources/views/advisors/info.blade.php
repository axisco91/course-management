<!-- Modal -->
<div wire:ignore.self class="modal fade" id="advisorsTabModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-course-tab">
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
                        <a class="nav-link {{ $tab == 'info' ? 'active' : '' }}" wire:click="$set('tab', 'info')" data-bs-toggle="tab" href="#general">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'companies' ? 'active' : '' }}" wire:click="$set('tab', 'companies')" data-bs-toggle="tab" href="#companies">Empresas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'courses' ? 'active' : '' }}" wire:click="$set('tab', 'courses')" data-bs-toggle="tab" href="#courses">Cursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'observations' ? 'active' : '' }}" wire:click="$set('tab', 'observations')" data-bs-toggle="tab" href="#observations">Observaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'incidences' ? 'active' : '' }}" wire:click="$set('tab', 'incidences')" data-bs-toggle="tab" href="#incidences">Histórico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'commissions' ? 'active' : '' }}" wire:click="$set('tab', 'commissions')" data-bs-toggle="tab" href="#commissions">Comisiones</a>
                    </li>
                    <li class="nav nav-tabs">
                        <a class="nav-link" data-bs-toggle="tab" href="#"></a>
                    </li>
                </ul>
                @if($tab == 'info')
                    <div class="tab-pane container-fluid active" id="general">
                        @include('livewire.advisors.info')
                    </div>
                @elseif($tab == 'companies')
                    <div class="tab-pane container" id="companies">
                        @include('livewire.advisors.advisors-companies')
                    </div>
                @elseif($tab == 'courses')
                    <div class="tab-pane container" id="courses">
                        @include('livewire.advisors.advisors-courses')
                    </div>
                @elseif($tab == 'observations')
                    <div class="tab-pane container" id="courses">
                        @include('livewire.advisors.advisors-observations-list')
                    </div>
                @elseif($tab == 'incidences')
                    <div class="tab-pane container" id="incidences">
                        @include('livewire.advisors.advisors-incidences')
                    </div>
                @elseif($tab == 'commissions')
                    <div class="tab-pane container" id="commissions">
                        @include('livewire.advisors.advisors-commissions')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
