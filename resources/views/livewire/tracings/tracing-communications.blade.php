 <div class="card-body row">
    <h4>Comunicaciones</h4>
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/tracing_communications/create/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
     <div class="bs-stepper-content">
        @if(isset($tracing_communications))
            @foreach($tracing_communications as $tracing_communication)
                 <div class="card ecommerce-card">
                     <div class="card-body">
                     <div class="item-name" style="display: flex;flex-direction: row;justify-content: space-between;background-color: orange;padding: 4px;align-items: center;">
                         <div style="display: flex;flex-direction: row;align-content: stretch;flex-wrap: nowrap;justify-content: center;">
                             <span class="item-company" style="margin-right: 5px">{{$tracing_communication['created_at']}}</span>
                             <span class="" style="margin-right: 5px">{{$tracing_communication['incidence_type']}}</span>
                             <span class="" style="margin-right: 5px">{{$tracing_communication['user_name']}} {{$tracing_communication['user_surname']}}</span>
                         </div>
                         <div>
                             <a class="btn btn-sm btn-success" href="{{url('$/tracing_communications/edit/'.$tracing_communication['id'])}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
                             <a class="btn btn-sm btn-danger eliminate_advisor_incidence" data-id="{{$tracing_communication->id}}"> <i class="fa-solid fa-trash"></i></a>
                         </div>
                         </div>
                         <h5>{{$tracing_communication['affair']}}</h5>
                         <span class="delivery-date text-muted">{{$tracing_communication['notes']}}</span>
                     </div>
                 </div>
            @endforeach
        @endif
    </div>
</div>
