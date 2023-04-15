<div class="card-body row">
    <h4>Observaciones</h4>
    <div class="col-12 mt-2 pt-50 mb-1">
        <a href="{{url('/companies/observations/'.$this->selected_id)}}" class="btn btn-primary" target="_blank">Lista</a>
    </div>
    <div class="" style="overflow-y:auto;">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Observación</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($observations))
                @foreach($observations as $row)
                    <tr>
                        <td>{{$row['observation']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
