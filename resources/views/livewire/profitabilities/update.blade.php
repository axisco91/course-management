<div class="card-body">
    <form class="form needs-validation" novalidate>
        <input type="hidden" wire:model.lazy="selected_id">
        <div class="row">
           <div class="col-md-4 col-12 mb-1">
               <div wire:ignore>
                   <label class="form-label" for="course_id">Curso</label>
                   <select wire:model.lazy="course_id" class="form-select select2" id="course_id" disabled>
                       <option value="-1">Seleccione un tipo</option>
                       @foreach($courses as $course)
                           <option value="{{$course['id']}}" {{$course['id'] == $course_id ? 'selected' : ''}}>{{$course['name']}}</option>
                       @endforeach
                   </select>
                   @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12 mb-1">
               <div wire:ignore>
                    <label class="form-label" for="company_id">Empresa</label>
                    <select wire:model.lazy="company_id" class="form-control select2" id="company_id" disabled>
                        <option value="-1">Seleccione un tipo</option>
                        @foreach($companies as $company)
                            <option value="{{$company['id']}}" {{$company['id'] == $company_id ? 'selected' : ''}}>{{$company['name']}}</option>
                        @endforeach
                    </select>
                    @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12 mb-1">
               <div wire:ignore>
                   <label class="form-label" for="student_id">Alumno</label>
                   <select wire:model.lazy="student_id" class="form-control select2" id="student_id" disabled>
                       @foreach($students as $student)
                           <option value="{{$student['id']}}" {{$student['id'] == $student_id ? 'selected' : ''}}>{{$student['name'].' '.$student['surname']}}</option>
                       @endforeach
                   </select>
                   @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="price">Precio</label>
                   <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio" value="{{$price}}">@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="license">Licencia</label>
                   <input wire:model.lazy="license" type="number" class="form-control" id="license" placeholder="Licencia" value="{{$license}}">@error('license') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="teacher">Docente</label>
                   <input wire:model.lazy="teacher" type="text" class="form-control" id="teacher" placeholder="Docente" value="{{$teacher}}">@error('teacher') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="management">Gestión</label>
                   <input wire:model.lazy="management" type="text" class="form-control" id="management" placeholder="Gestión" value="{{$management}}">@error('management') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="nebrija_title">Titulo Nebrija</label>
                   <input wire:model.lazy="nebrija_title" type="text" class="form-control" id="nebrija_title" placeholder="Titulo Nebrija" value="{{$nebrija_title}}">@error('nebrija_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="discount">Descuento</label>
                   <input wire:model.lazy="discount" type="text" class="form-control" id="discount" placeholder="Descuento" value="{{$discount}}">@error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="collaborator_percentage">Porcentaje Colaborador</label>
                    <input wire:model.lazy="collaborator_percentage" type="text" class="form-control" id="collaborator_percentage" placeholder="Porcentaje Colaborador" value="{{$collaborator_percentage}}">@error('collaborator_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="collaborator_commission">Comisión Colaborador</label>
                   <input wire:model.lazy="collaborator_commission" type="text" class="form-control" id="collaborator_commission" placeholder="Comisión Colaborador" value="{{$collaborator_commission}}" disabled>@error('collaborator_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="mb-1">
                    <label class="form-label" for="advisor_percentage">Porcentaje Asesoria</label>
                    <input wire:model.lazy="advisor_percentage" type="text" class="form-control" id="advisor_percentage" placeholder="Porcentaje Asesoria" value="{{$advisor_percentage}}">@error('advisor_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="advisor_commission">Comisión Asesoria</label>
                   <input wire:model.lazy="advisor_commission" type="text" class="form-control" id="advisor_commission" placeholder="Comision Asesoria" value="{{$advisor_commission}}" disabled>@error('advisor_commission') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="total">Total</label>
                   <input wire:model.lazy="total" type="text" class="form-control" id="total" value="{{$total}}" disabled>@error('total') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-md-4 col-12">
               <div class="mb-1">
                   <label class="form-label" for="benefits">Beneficios</label>
                   <input wire:model.lazy="benefits" type="text" class="form-control" id="benefits" disabled {{$benefits}}>@error('benefits') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
           <div class="col-12">
               <div class="mb-1">
                   <label class="form-label" for="observations">Observaciones</label>
                   <textarea wire:model.lazy="observations" class="form-control" id="observations">{{$observations}}</textarea>
                   @error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
               </div>
            </div>
        </div>
        <div class="col-12">
            <a href="{{url('/profitabilities')}}" class="btn btn-secondary">Volver</a>
            <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        </div>
    </form>
    @section('vendor-script')
        <!-- vendor files -->
            <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
            <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    @endsection
    @section('page-script')
        <!-- Page js files -->
        <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
        <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-toastr.js') }}"></script>
    @endsection
    @section('scripts')
    <script>
        Livewire.on('toastr', type => {
            if (type == 'success'){
                toastr['success']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            } else{
                toastr['warning']($('#success-toast').val(), {
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    timeOut: 2000,
                });
            }
        })
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
    @endsection
</div>
