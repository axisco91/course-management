<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Factura</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="billing_number">Nº Factura</label>
                            <input wire:model.lazy="billing_number" type="text" class="form-control" id="billing_number" placeholder="Nº Factura">@error('billing_number') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="course_id">Curso</label>
                            <select wire:model.lazy="course_id selectCreate" class="form-control" id="course_id">
                                <option value="-1">Seleccione un curso</option>
                                @foreach($courses as $course)
                                    <option value="{{$course['id']}}">{{$course['name']}}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="company_id">Empresa</label>
                            <select wire:model.lazy="company_id" class="form-control selectCreate" id="company_id">
                                <option value="-1">Seleccione una empresa</option>
                                @foreach($companies as $company)
                                    <option value="{{$company['id']}}">{{$company['name']}}</option>
                                @endforeach
                            </select>
                            @error('company_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="number_students">Numero Alumnos</label>
                            <input wire:model.lazy="number_students" type="number" class="form-control" id="number_students" placeholder="Numero Alumnos">@error('number_students') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="billing">Facturacion</label>
                            <input wire:model.lazy="billing" type="text" class="form-control" id="billing" placeholder="Facturación">@error('billing') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="bonus">Bonificación</label>
                            <input wire:model.lazy="bonus" type="text" class="form-control" id="bonus" placeholder="Bonificación">@error('bonus') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="total_training_activity">Total Actividad Formativa</label>
                            <input wire:model.lazy="total_training_activity" type="text" class="form-control" id="total_training_activity" placeholder="Total Actividad Formativa">@error('total_training_activity') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="expenses">Gastos de Organización</label>
                            <input wire:model.lazy="expenses" type="text" class="form-control" id="expenses" placeholder="Gastos de Organización">@error('expenses') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="only_organizing_entity">Solamente Entidad Organizadora</label>
                            <input wire:model.lazy="only_organizing_entity" type="text" class="form-control" id="only_organizing_entity" placeholder="Solamente Entidad Organizadora">@error('only_organizing_entity') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="salary_costs">Costes Salariales</label>
                            <input wire:model.lazy="salary_costs" type="text" class="form-control" id="salary_costs" placeholder="Costes Salariales">@error('salary_costs') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="payment_id">Forma de Pago</label>
                            <select wire:model.lazy="payment_id" class="form-control" id="payment_id">
                                <option value="-1">Seleccióne una forma de pago</option>
                                @foreach($payments as $payment)
                                    <option value="{{$payment['id']}}">{{$payment['name']}}</option>
                                @endforeach
                            </select>
                            @error('payment_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="communication_start_date">Fecha Comunicación Inicio</label>
                            <input wire:model.lazy="communication_start_date" type="date" class="form-control" id="communication_start_date">@error('communication_start_date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="comunication_end_date">Fecha Comunicación Cierre</label>
                            <input wire:model="comunication_end_date" type="date" class="form-control" id="comunication_end_date">@error('comunication_end_date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="invoiced">Facturado</label>
                            <select wire:model.lazy="invoiced" class="form-control" id="invoiced">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('invoiced') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="billing_date">Fecha Factura</label>
                            <input wire:model.lazy="billing_date" type="date" class="form-control" id="billing_date">@error('billing_date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="collection_date">Fecha Cobro</label>
                            <input wire:model="collection_date" type="date" class="form-control" id="collection_date">@error('collection_date') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-4">
                            <label for="bonus_status">Estado Bonificación</label>
                            <select wire:model.lazy="bonus_status" class="form-control" id="bonus_status">
                                <option value="0">Pendiente</option>
                                <option value="1">Enviada</option>
                            </select>
                            @error('bonus_status') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="company_bonus">Bonificación empresa</label>
                            <select wire:model.lazy="company_bonus" class="form-control" id="company_bonus">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('company_bonus') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="observation">Observación</label>
                            <textarea wire:model.lazy="observation" class="form-control" id="observation" placeholder="Observación"></textarea>@error('observation') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function(){
            $('.selectCreate').select2()
            $('.selectCreate').on('change', function(){
            @this.set(this.id, $(this).val())
            })
        })
    </script>
</div>
