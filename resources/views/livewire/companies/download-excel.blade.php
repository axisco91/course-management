
<div wire:ignore.self class="modal fade" id="excelModal" data-bs-backdrop="static" role="dialog" aria-labelledby="excelDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="creditsModalLabel">Creditos</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="excel_type_id">Tipo</label>
                                <select wire:model.lazy="excel_type_id" class="form-select select2" id="excel_type_id">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($company_types as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="excel_activity_id">Actividad</label>
                                <select wire:model.lazy="excel_activity_id" class="form-select select2" id="excel_activity_id">
                                    <option value="">Seleccione una actividad</option>
                                    @foreach($company_activities as $activity)
                                        <option value="{{$activity['id']}}">{{$activity['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label" for="excel_advisor_id">Asesoria</label>
                            <div wire:ignore>
                                <select wire:model.lazy="excel_advisor_id" class="form-select select2" id="excel_advisor_id" placeholder="Advisor Id">
                                    <option value="">Selección una Asesoria</option>
                                    @foreach($advisors as $advisor)
                                        <option value="{{$advisor['id']}}">{{$advisor['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="excel_cnae_id">Cnae</label>
                                <select wire:model.lazy="excel-cnae_id" class="form-select select2" id="excel_cnae_id">
                                    <option value="">Seleccione una cnae</option>
                                    @foreach($cnaes as $cnae)
                                        <option value="{{$cnae['id']}}">{{$cnae['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <div wire:ignore>
                                <label class="form-label" for="excel_province_id">Provincia</label>
                                <select wire:model.lazy="excel_province_id" class="form-select select2" id="excel_province_id">
                                    <option value="">Seleccione una provincia</option>
                                    @foreach($provinces as $province)
                                        <option value="{{$province['id']}}">{{$province['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-2 pt-50">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="downloadExcel()" class="btn btn-primary close-model"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
