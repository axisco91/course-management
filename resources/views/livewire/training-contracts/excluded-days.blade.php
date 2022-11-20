<div class="card-body">
    <div class="mb-1">
        <a class="btn" href="{{asset('/training_contracts/edit/'.$selected_id)}}">Fase 1</a>
        <a class="btn" href="{{asset('/training_contracts/second_phase/'.$selected_id)}}">Fase 2</a>
        <a class="btn btn-primary">Dias Excluidos</a>
        <a class="btn" href="{{asset('/training_contract_incidences/'.$selected_id)}}">Historico</a>
    </div>
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-12 col-md-3 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="province_id">Provinces</label>
                    <select wire:model.lazy="province_id" class="form-select select2" id="province_id" data-type="province">
                        <option value="-1">Selecciona una provincia</option>
                        @foreach($provinces as $province)
                            <option value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="group_id">Grupo</label>
                    <select wire:model.lazy="group_id" class="form-select select2" id="group_id" data-type="group">
                        <option value="-1">Selecciona un grupo</option>
                        @foreach($groups as $group)
                            <option value="{{$group['id']}}">{{$group['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-1">
                <br>
                <button id="add_general_days" type="button" wire:click.prevent="addGeneralDays()" class="btn btn-primary" data-bs-dismiss="modal">Añadir días generales</button>
            </div>
            <div class="col-12 col-md-3 mb-1">
                <label for="date">Añadir Fecha</label>
                <input wire:model.lazy="date" type="date" class="form-control" id="date">@error('date') <div class="invalid-feedback"></div> @enderror
            </div>
            <div class="col-12 col-md-3 mb-1">
                <br>
                <button id="add_date" type="button" wire:click.prevent="addDate()" class="btn btn-primary" data-bs-dismiss="modal">Añadir día</button>
            </div>
        </div>
        <div class="col-12 mb-2">
            @if (isset($excluded_days))
                @foreach($excluded_days as $excluded_day)
                    <div class="" style="display: flex; background: white; margin: 5px; padding: 5px; justify-content: space-between;">
                        <div style="margin-top: 5px;">
                            {{$excluded_day['day']}}
                        </div>
                        <div>
                            <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$excluded_day->id}})"><i class="fas fa-minus"></i></div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-12">
            <a href="{{url('/training_contracts')}}" class="btn btn-outlined-secondary">Volver</a>
        </div>
    </form>
@section('vendor-script')
    <!-- vendor files -->
        <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                $( document ).ready(
                    setTimeout(function (){
                        initializeSelect2()
                    }, 100)
                );
                $('.select2').on('change', function(){
                @this.set(this.id, $(this).val())
                    id = $(this).val();
                    type = $(this).data('type');
                    Livewire.emit('addElement', id, type)
                })
            })
        </script>
    @endsection
</div>
