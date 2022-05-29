 <div class="row">
     @if(isset($this->selected_id))
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="formative_action">Acción Formativa</label>
                <input wire:model.lazy="formative_action" type="text" class="form-control" id="formative_action" disabled placeholder="Acción Formativa">@error('formative_action') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="name">Nombre</label>
                <input wire:model.lazy="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nombre" disabled>@error('name') <div class="invalid-feedback">Nombre es requerido</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3 mb-1">
            <div wire:ignore>
                <label class="form-label" for="action_type_id">Tipo Acción</label>
                <select wire:model.lazy="action_type_id" class="form-select select2 @error('action_type_id') is-invalid @enderror" id="action_type_id" disabled>
                    <option value="-1">Seleccione un tipo de acción</option>
                    @foreach($action_types as $type)
                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('action_type_id') <div class="invalid-feedback">Tipo es requerido</div> @enderror
        </div>
        <div class="col-12 col-md-3 mb-1">
            <div wire:ignore>
                <label class="form-label" for="professional_family_id">Familia profesional</label>
                <select wire:model.lazy="professional_family_id" class="form-select select2 @error('professional_family_id') is-invalid @enderror" id="professional_family_id" disabled>
                    <option value="-1">Seleccione una familia profesional</option>
                    @foreach($professional_families as $family)
                        <option value="{{$family['id']}}">{{$family['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('professional_family_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="professional_area_id">Área profesional</label>
                <select wire:model.lazy="professional_area_id" class="form-select select2 @error('professional_area_id') is-invalid @enderror" id="professional_area_id" disabled>
                    <option value="-1">Seleccione una area profesional</option>
                    @foreach($professional_areas as $area)
                        <option value="{{$area['id']}}">{{$area['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('professional_area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="modality_id">Modalidad</label>
                <select wire:model.lazy="modality_id" class="form-select select2 @error('modality_id') is-invalid @enderror" id="modality_id" disabled>
                    <option value="-1">Seleccione una modalidad</option>
                    @foreach($modalities as $modalidad)
                        <option value="{{$modalidad['id']}}">{{$modalidad['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('modality_id') <div class="invalid-feedback">Modalidad es requerido</div> @enderror
        </div>
        <div class="col-md-3 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="training_action_level_id">Nivel</label>
                <select wire:model.lazy="training_action_level_id" class="form-select select2 @error('training_action_level_id') is-invalid @enderror" id="training_action_level_id" disabled>
                    <option value="-1">Seleccione un nivel</option>
                    @foreach($training_action_levels as $level)
                        <option value="{{$level['id']}}">{{$level['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('training_action_level_id') <div class="invalid-feedback">Nivel es requerido</div> @enderror
        </div>
        <div class="col-md-3 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="training_action_group_id">Grupos</label>
                <select wire:model.lazy="training_action_group_id" class="form-select select2 @error('training_action_group_id') is-invalid @enderror" id="training_action_group_id" disabled>
                    <option value="-1">Seleccione un grupo</option>
                    @foreach($training_action_groups as $group)
                        <option value="{{$group['id']}}">{{$group['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('training_action_group_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="tutoring_id">Tutorización</label>
                <select wire:model.lazy="tutoring_id" class="form-select select2 @error('tutoring_id') is-invalid @enderror" id="tutoring_id" disabled>
                    <option value="-1">Seleccione una tutorización</option>
                    @foreach($tutorings as $tutoring)
                        <option value="{{$tutoring['id']}}">{{$tutoring['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('tutoring_id') <div class="invalid-feedback">Tutorización es requerido</div> @enderror
        </div>
        <div class="col-md-2 col-12 mb-1">
            <label class="form-label" for="course_z">Curso Z</label>
            <select wire:model.lazy="course_z" class="form-select @error('course_z') is-invalid @enderror" id="course_z" disabled>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
            @error('course_z') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-2 col-12 mb-1">
            <label class="form-label" for="course_avz">Curso Avz</label>
            <select wire:model.lazy="course_avz" class="form-select @error('course_avz') is-invalid @enderror" id="course_avz" disabled>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
            @error('course_avz') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-2 col-12">
            <div class="mb-1">
                <br>
                <label class="form-labe" for="active"><input wire:model.lazy="active" type="checkbox" id="active" value="active" {{$active == 1 ? 'checked' : ''}} disabled> Activo</label>
                @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="mb-1">
                <br>
                <label class="form-label" for="in_catalog"> <input wire:model.lazy="in_catalog" type="checkbox" id="in_catalog" value="in_catalog" {{$in_catalog == 1 ? 'che' : ''}} disabled> En Catalogo</label>
                @error('in_catalog') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="face_to_face_hours">Horas Presenciales</label>
                <input wire:model.lazy="face_to_face_hours" type="text" class="form-control @error('face_to_face_hours') is-invalid @enderror" id="face_to_face_hours" placeholder="Horas Presenciales" disabled>
                @error('face_to_face_hours') <div class="invalid-feedback">Horas presenciales es requerido</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="teletraining_hours">Horas Teleformaicón</label>
                <input wire:model.lazy="teletraining_hours" type="text" class="form-control @error('teletraining_hours') is-invalid @enderror" id="teletraining_hours" placeholder="Horas Teleformaicón" disabled>
                @error('teletraining_hours') <div class="invalid-feedback">Horas Teleformaicón requerido</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="total_hours">Horas Totales</label>
                <input wire:model.lazy="total_hours" type="text" class="form-control @error('total_hours') is-invalid @enderror" id="total_hours" disabled placeholder="Horas Totales">@error('total_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="mb-1">
                <label class="form-label" for="price">Precio</label>
                <input wire:model.lazy="price" type="text" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Precio" disabled>@error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-1">
                <label class="form-label" for="objectives">Objetivos</label>
                <textarea wire:model.lazy="objectives" rows="20" class="form-control @error('objectives') is-invalid @enderror" id="objectives" placeholder="Objetivos" disabled></textarea>
                @error('objectives') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="mb-1">
                <label class="form-label" for="content">Contenido</label>
                <textarea wire:model.lazy="content @error('content') is-invalid @enderror" rows="20" class="form-control" id="content" placeholder="Contenido" disabled></textarea>@error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="user">Usuario</label>
                <input wire:model.lazy="user" type="text" class="form-control @error('user') is-invalid @enderror" id="user" placeholder="Usuario" disabled>@error('user') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 col-12">
            <label class="form-label" for="password">Contraseña</label>
            <input wire:model.lazy="password" type="text" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña" disabled>@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="web_platform_id">Plataforma</label>
                <select wire:model.lazy="web_platform_id" class="form-select select2 @error('web_platform_id') is-invalid @enderror" id="web_platform_id" disabled>
                    <option value="-1">Seleccione una plataforma</option>
                    @foreach($web_platforms as $platform)
                        <option value="{{$platform['id']}}">{{$platform['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('web_platform_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <div class="mb-1">
                <label class="form-label" for="observations">Observaciones</label>
                <textarea wire:model.lazy="observations" rows="10" class="form-control @error('observations') is-invalid @enderror" id="observations" placeholder="Observaciones" disabled></textarea>@error('observations') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="number_activities">Numero Actividades</label>
                <input wire:model.lazy="number_activities" type="number" class="form-control @error('number_activities') is-invalid @enderror" id="number_activities" placeholder="Numero Actividades" disabled>@error('number_activities') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="mb-1">
                <label class="form-label" for="number_units">Numero Unidades</label>
                <input wire:model.lazy="number_units" type="number" class="form-control @error('number_unts') is-invalid @enderror" id="number_units" placeholder="Numero Unidades" disabled>@error('number_units') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 col-12 mb-1">
            <div wire:ignore>
                <label class="form-label" for="provider_id">Proveedor</label>
                <select wire:model.lazy="provider_id" class="form-select select2 @error('provider_id') is-invalid @enderror" id="provider_id" disabled>
                    <option value="-1">Seleecione un proveedor</option>
                    @foreach($providers as $provider)
                        <option value="{{$provider['id']}}">{{$provider['name']}}</option>
                    @endforeach
                </select>
            </div>
            @error('provider_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
     @endif
</div>
