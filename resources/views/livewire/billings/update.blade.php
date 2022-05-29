<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model="selected_id">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="billing_number">Nº Factura</label>
                    <input wire:model.lazy="billing_number" type="text" class="form-control" id="billing_number" placeholder="Nº Factura">@error('billing_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="course_id">Curso</label>
                    <select wire:model.lazy="course_id" class="form-control select2" id="course_id" disabled>
                        @foreach($courses as $course)
                            <option value="{{$course['id']}}">{{$course['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <div wire:ignore>
                    <label class="form-label" for="company_id">Empresa</label>
                    <select wire:model.lazy="company_id" class="form-control select2" id="company_id" disabled>
                        <option value="-1">Seleccione una empresa</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}">{{$company['name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="number_students">Numero Alumnos</label>
                    <input wire:model.lazy="number_students" type="number" class="form-control" id="number_students" placeholder="Numero Alumnos">@error('number_students') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="billing">Facturacion</label>
                    <input wire:model.lazy="billing" type="text" class="form-control" id="billing" placeholder="Facturación">@error('billing') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="bonus">Bonificación</label>
                    <input wire:model.lazy="bonus" type="text" class="form-control" id="bonus" placeholder="Bonificación">@error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="total_training_activity">Total Actividad Formativa</label>
                    <input wire:model.lazy="total_training_activity" type="text" class="form-control" id="total_training_activity" placeholder="Total Actividad Formativa">@error('total_training_activity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="expenses">Gastos de Organización</label>
                    <input wire:model.lazy="expenses" type="text" class="form-control" id="expenses" placeholder="Gastos de Organización">@error('expenses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="only_organizing_entity">Solamente Entidad Organizadora</label>
                    <input wire:model.lazy="only_organizing_entity" type="text" class="form-control" id="only_organizing_entity" placeholder="Solamente Entidad Organizadora">@error('only_organizing_entity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="salary_costs">Costes Salariales</label>
                    <input wire:model.lazy="salary_costs" type="text" class="form-control" id="salary_costs" placeholder="Costes Salariales">@error('salary_costs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="payment_id">Forma de Pago</label>
                <select wire:model.lazy="payment_id" class="form-control" id="payment_id">
                    <option value="-1">Seleccióne una forma de pago</option>
                    @foreach($payments as $payment)
                        <option value="{{$payment['id']}}">{{$payment['name']}}</option>
                    @endforeach
                </select>
                @error('payment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="communication_start_date">Fecha Comunicación Inicio</label>
                    <input wire:model.lazy="communication_start_date" type="date" class="form-control" id="communication_start_date">@error('communication_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="comunication_end_date">Fecha Comunicación Cierre</label>
                    <input wire:model.lazy="comunication_end_date" type="date" class="form-control" id="comunication_end_date">@error('comunication_end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="invoiced">Facturado</label>
                <select wire:model.lazy="invoiced" class="form-control" id="invoiced">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                @error('invoiced') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="billing_date">Fecha Factura</label>
                    <input wire:model.lazy="billing_date" type="date" class="form-control" id="billing_date">@error('billing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="collection_date">Fecha Cobro</label>
                    <input wire:model.lazy="collection_date" type="date" class="form-control" id="collection_date">@error('collection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="bonus_status">Estado Bonificación</label>
                <select wire:model.lazy="bonus_status" class="form-control" id="bonus_status">
                    <option value="0">Pendiente</option>
                    <option value="1">Enviada</option>
                </select>
                @error('bonus_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 col-12 mb-1">
                <label class="form-label" for="company_bonus">Bonificación empresa</label>
                <select wire:model.lazy="company_bonus" class="form-control" id="company_bonus">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                @error('company_bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <div class="mb-1">
                    <label class="form-label" for="observation">Observación</label>
                    <textarea wire:model="observation" class="form-control" id="observation" placeholder="Observación"></textarea>@error('observation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            <button type="button" wire:click.prevent="update()" class="btn btn-primary">Guardar</button>
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
    </script>
</div>
