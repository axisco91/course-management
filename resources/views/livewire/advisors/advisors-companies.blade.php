 <div class="card-body row">
    <h4>Empresas</h4>
    <div class="col-md-12 mb-1">
        <label class="form-label">Nombre:</label>
        <input wire:model="search_company_name" type="text" class="form-control dt-input dt-full-name" data-column="1" placeholder="Nombre" data-column-index="0" />
    </div>

    <div class="registerd_students" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>NIF</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($companies))
                    @foreach($companies as $company)
                        <tr>
                            <td>{{$company['name']}}</td>
                            <td>{{$company['nif']}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
