<div class="card-body">
    <div class="mb-1">
        <a class="btn" href="{{asset('/training_contracts/edit/'.$selected_id)}}">Fase 1</a>
        <a class="btn btn-primary">Fase 2</a>
    </div>
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
        @if($specialty == 1)
            <div class="col-12 col-md-4 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="specialty_id">Especialidades</label>
                    <select wire:model.lazy="specialty_id" class="form-select select2" id="specialty_id">
                        <option value="-1">Selecciona especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{$specialty['id']}}">{{$specialty['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        @if($professional_certificate == 1)
            <div class="col-12 col-md-4 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="certification_id">Certificaciones</label>
                    <select wire:model.lazy="certification_id" class="form-select select2" id="certification_id">
                        <option value="-1">Selecciona certificación</option>
                        @foreach($certifications as $certification)
                            <option value="{{$certification['id']}}">{{$certification['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        </div>
        <div class="col-12 mb-2">
            @foreach($training_contract_certifications as $training_contract_certification)
                <div class="" style="display: flex; background: white; margin: 5px; padding: 5px; justify-content: space-between;">
                    @if ($training_contract_certification['training_action_id'])
                        <div style="margin-top: 5px;">
                            {{$training_contract_certification['formative_action']}} - {{$training_contract_certification['training_action_name']}}
                        </div>
                        <div>
                            <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$training_contract_certification->id}})"><i class="fas fa-minus"></i></div>
                        </div>
                    @elseif($training_contract_certification['certification_id'])
                        <div style="margin-top: 5px;">
                            {{$training_contract_certification['certification_name']}}
                        </div>
                        <div>
                            <div class="btn btn-danger btn-sm unregister" wire:click="unregister({{$training_contract_certification->id}})"><i class="fas fa-minus"></i></div>
                        </div>
                    @endif
                </div>
            @endforeach
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
                    type = $(this).attr('id');
                    Livewire.emit('addElement', id, type)
                })
            })
            $('body').on('click', '#save', function(){
                content = ''
                if ($('#name').val() == ''){
                    content += 'El nombre es requerido<br>'
                }
                if ($('#action_type_id').val() == ''){
                    content += 'El tipo es requerido<br>'
                }
                if ($('#modality_id').val() == ''){
                    content += 'La modalidad es requerido<br>'
                }
                if ($('#training_action_level_id').val() == ''){
                    content += 'El nivel es requerido<br>'
                }
                if ($('#tutoring_id').val() == ''){
                    content += 'La tutoria es requerido<br>'
                }
                if ($('#face_to_face_hours').val() == ''){
                    content += 'Las horas presenciales son requeridos<br>'
                }
                if ($('#teletraining_hours').val() == ''){
                    content += 'Las horas de teletrabajo son requeridos<br>'
                }
                if ($('#price').val() == ''){
                    content += 'El precio es requerido<br>'
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
