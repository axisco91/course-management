<div class="card-body">
    <form>
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="affair">Asunto</label>
                    <input wire:model.lazy="affair" type="text" class="form-control @error('affair') is-invalid @enderror" id="name" placeholder="Asunto">
                    @error('name') <div class="invalid-feedback">Asunto es requerido</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div wire:ignore>
                    <label class="form-label" for="advisor_id">Asesoria</label>
                    <select wire:model.lazy="advisor_id" class="form-select select2" id="advisor_id" placeholder="Asesoria" disabled>
                        <option value="">Selección una Asesoria</option>
                        @foreach($advisors as $advisor)
                            <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('advisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="incidence_type_id">Tipo Incidencia</label>
                    <select wire:model.lazy="incidence_type_id" class="form-select select2" id="incidence_type_id">
                        <option value="">Seleccione un tipo de incidencia</option>
                        @foreach($incidence_types as $incidence_type)
                            <option value="{{$incidence_type['id']}}">{{$incidence_type['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('incidence_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="user_id">Usuario</label>
                    <select wire:model.lazy="user_id" class="form-select select2" id="user_id">
                        <option value="">Seleccione un usuario</option>
                        @foreach($users as $user)
                            <option value="{{$user['id']}}">{{$user['name']}} {{$user['surname']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('incidence_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <div class="mb-1">
                    <div class="form-group">
                        <label class="form-label" for="notes"></label>
                        <textarea wire:model="notes" class="form-control" id="notes" placeholder="Notas"></textarea>@error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            <button id="save" type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
        </div>
    </form>
</div>
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
            })
        })
        $('body').on('click', '#save', function(){
            content = ''
            if ($('#affair').val() == ''){
                content += 'El asunto es requerido<br>'
            }
            if ($('#incidence_type_id').val() == ''){
                content += 'El tipo es requerido<br>'
            }
            if ($('#advisor_id').val() == ''){
                content += 'La asesoria es requerido<br>'
            }
            if ($('#user_id').val() == ''){
                content += 'El ususario es requerido<br>'
            }
            if (content != ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Falta datos',
                    html: '<div>'+content+'</div>',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        })
    </script>
    @endsection
    </div>

