<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Crear Acción Formativa</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="row">
                       <div class="form-group col-4">
                            <label for="name">Nombre</label>
                            <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                       </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_action_type_id">Tipo Acción</label>
                               <select wire:model.lazy="create_action_type_id" class="form-control selectCreate" id="create_action_type_id">
                                   <option value="">Seleccione un tipo de acción</option>
                                   @foreach($action_types as $type)
                                       <option value="{{$type['id']}}">{{$type['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                           @error('create_action_type_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_professional_family_id">Familia profesional</label>
                               <select wire:model.lazy="create_professional_family_id" class="form-control selectCreate" id="create_professional_family_id">
                                   <option value="">Seleccione una familia profesional</option>
                                   @foreach($professional_families as $family)
                                       <option value="{{$family['id']}}">{{$family['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_professional_family_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_professional_area_id">Área profesional</label>
                               <select wire:model.lazy="create_professional_area_id" class="form-control selectCreate" id="create_professional_area_id">
                                   <option value="">Seleccione una area profesional</option>
                                   @foreach($professional_areas as $area)
                                       <option value="{{$area['id']}}">{{$area['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_professional_area_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_modality_id">Modalidad</label>
                               <select wire:model.lazy="create_modality_id" class="form-control selectCreate" id="create_modality_id">
                                   <option value="">Seleccione una modalidad</option>
                                   @foreach($modalities as $modalidad)
                                       <option value="{{$modalidad['id']}}">{{$modalidad['name']}}</option>
                                   @endforeach
                               </select>
                           </div>

                            @error('create_modality_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_training_action_level_id">Nivel</label>
                               <select wire:model.lazy="create_training_action_level_id" class="form-control selectCreate" id="create_training_action_level_id">
                                   <option value="">Seleccione un nivel</option>
                                   @foreach($training_action_levels as $level)
                                       <option value="{{$level['id']}}">{{$level['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_training_action_level_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_training_action_group_id">Grupos</label>
                               <select wire:model.lazy="create_training_action_group_id" class="form-control selectCreate" id="create_training_action_group_id">
                                   <option value="">Seleccione un grupo</option>
                                   @foreach($training_action_groups as $group)
                                       <option value="{{$group['id']}}">{{$group['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_training_action_group_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_tutoring_id">Tutorización</label>
                               <select wire:model.lazy="create_tutoring_id" class="form-control selectCreate" id="create_tutoring_id">
                                   <option value="-1">Seleccione una tutorización</option>
                                   @foreach($tutorings as $tutoring)
                                       <option value="{{$tutoring['id']}}">{{$tutoring['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_tutoring_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="course_z">Curso Z</label>
                            <select wire:model.lazy="course_z" class="form-control" id="course_z">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('course_z') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="course_avz">Curso Avz</label>
                            <select wire:model.lazy="course_avz" class="form-control" id="course_avz">
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                            @error('course_avz') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <br>
                            <label for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}}> Activo</label>
                            @error('active') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <br>
                            <label for="in_catalog"> <input wire:model.lazy="in_catalog" type="checkbox" id="in_catalog" value="in_catalog" {{$in_catalog == 1 ? 'che' : ''}}> En Catalogo</label>
                            @error('in_catalog') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="face_to_face_hours">Horas Presenciales</label>
                            <input wire:model.lazy="face_to_face_hours" type="text" class="form-control" id="face_to_face_hours" placeholder="Horas Presenciales">@error('face_to_face_hours') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="teletraining_hours">Horas Teletrabajo</label>
                            <input wire:model.lazy="teletraining_hours" type="text" class="form-control" id="teletraining_hours" placeholder="Horas Teletrabajo">@error('teletraining_hours') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="price">Precio</label>
                            <input wire:model.lazy="price" type="text" class="form-control" id="price" placeholder="Precio">@error('price') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group">
                            <label for="objectives">Objetivos</label>
                            <textarea wire:model.lazy="objectives" class="form-control" id="objectives" placeholder="Objetivos"></textarea>
                            @error('objectives') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group">
                            <label for="content">Contenido</label>
                            <textarea wire:model.lazy="content" class="form-control" id="content" placeholder="Contenido"></textarea>@error('content') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="user">Usuario</label>
                            <input wire:model.lazy="user" type="text" class="form-control" id="user" placeholder="Usuario">@error('user') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="password">Contraseña</label>
                            <input wire:model.lazy="password" type="text" class="form-control" id="password" placeholder="Contraseña">@error('password') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_web_platform_id">Plataforma</label>
                               <select wire:model.lazy="create_web_platform_id" class="form-control selectCreate" id="create_web_platform_id">
                                   <option value="">Seleccione una plataforma</option>
                                   @foreach($web_platforms as $platform)
                                       <option value="{{$platform['id']}}">{{$platform['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_web_platform_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea wire:model.lazy="observations" class="form-control" id="observations" placeholder="Observaciones"></textarea>@error('observations') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="number_activities">Numero Actividades</label>
                            <input wire:model.lazy="number_activities" type="number" class="form-control" id="number_activities" placeholder="Numero Actividades">@error('number_activities') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                            <label for="number_units">Numero Unidades</label>
                            <input wire:model.lazy="number_units" type="number" class="form-control" id="number_units" placeholder="Numero Unidades">@error('number_units') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       <div class="form-group col-4">
                           <div wire:ignore>
                               <label for="create_provider_id">Proveedor</label>
                               <select wire:model.lazy="create_provider_id" class="form-control selectCreate" id="create_provider_id">
                                   <option value="-1">Seleecione un proveedor</option>
                                   @foreach($providers as $provider)
                                       <option value="{{$provider['id']}}">{{$provider['name']}}</option>
                                   @endforeach
                               </select>
                           </div>
                            @error('create_provider_id') <span class="error text-danger">{{ $message }}</span> @enderror
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
