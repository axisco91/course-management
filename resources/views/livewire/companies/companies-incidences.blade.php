 <div class="card-body row">
    <h4>Histórico</h4>
     <div class="col-12 mb-1">
         <a class="btn btn-success right" href="{{url('/company_incidences/create/'.$this->selected_id)}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
     </div>
     <div class="bs-stepper-content">
        @if(isset($company_incidences))
            @foreach($company_incidences as $company_incidence)
                 <div class="card ecommerce-card">
                     <div class="card-body">
                     <div class="item-name" style="display: flex;flex-direction: row;justify-content: space-between;background-color: orange;padding: 4px;align-items: center;">
                         <div style="display: flex;flex-direction: row;align-content: stretch;flex-wrap: nowrap;justify-content: center;">
                             <span class="item-company" style="margin-right: 5px">{{$company_incidence['created_at']}}</span>
                             <span class="" style="margin-right: 5px">{{$company_incidence['incidence_type']}}</span>
                             <span class="" style="margin-right: 5px">{{$company_incidence['user_name']}} {{$company_incidence['user_surname']}}</span>
                         </div>
                         <div>
                             <a class="btn btn-sm btn-success" href="{{url('/company_incidences/edit/'.$company_incidence['id'])}}" target="_blank"><i class="fa-solid fa-pencil"></i></a>
                             <a class="btn btn-sm btn-danger eliminate_advisor_incidence" data-id="{{$company_incidence->id}}"> <i class="fa-solid fa-trash"></i></a>
                         </div>
                         </div>
                         <h5>{{$company_incidence['affair']}}</h5>
                         <span class="delivery-date text-muted">{{$company_incidence['notes']}}</span>
                     </div>
                 </div>
            @endforeach
        @endif
    </div>
</div>
