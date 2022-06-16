<section id="dashboard-analytics">
    <div class="row match-height">
        <!-- Greetings Card starts -->
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card card-congratulations">
                <div class="card-body text-center">
                    <img
                        src="{{asset('app-assets/images/elements/decore-left.png')}}"
                        class="congratulations-img-left"
                        alt="card-img-left"
                    />
                    <img
                        src="{{asset('app-assets/images/elements/decore-right.png')}}"
                        class="congratulations-img-right"
                        alt="card-img-right"
                    />
                    <div class="avatar avatar-xl bg-primary shadow">
                        <div class="avatar-content">
                            <i data-feather="award" class="font-large-1"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="mb-1 text-white">Congratulations John,</h1>
                        <p class="card-text m-auto w-75">
                            You have done <strong>57.6%</strong> more sales today. Check your new badge in your profile.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Greetings Card ends -->

        <!-- Subscribers Chart Card starts -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header flex-column align-items-start pb-0">
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="users" class="font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder mt-1">{{$this->total_registrations}}</h2>
                    <p class="card-text">Matriculaciones Totales</p>
                </div>
                <div id="registrations-chart" data-registrations-count="{{json_encode($this->registrations_count, true)}}"></div>
            </div>
        </div>
        <!-- Subscribers Chart Card ends -->

        <!-- Orders Chart Card starts -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header flex-column align-items-start pb-0">
                    <div class="avatar bg-light-warning p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="package" class="font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder mt-1">38.4K</h2>
                    <p class="card-text">Orders Received</p>
                </div>
                <div id="order-chart"></div>
            </div>
        </div>
        <!-- Orders Chart Card ends -->
    </div>

    <div class="row match-height">
         <!-- Revenue Report Card -->
         <div class="col-lg-8 col-12">
             <div class="card card-revenue-budget">
                 <div class="row mx-0">
                     <div class="col-md-8 col-12 revenue-report-wrapper">
                         <div class="d-sm-flex justify-content-between align-items-center mb-3">
                             <h4 class="card-title mb-50 mb-sm-0">Revenue Report</h4>
                             <div class="d-flex align-items-center">
                                 <div class="d-flex align-items-center me-2">
                                     <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                     <span>Earning</span>
                                 </div>
                                 <div class="d-flex align-items-center ms-75">
                                     <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                     <span>Expense</span>
                                 </div>
                             </div>
                         </div>
                         <div id="revenue-report-chart" data-expenses-month="{{json_encode($this->expenses_per_month, true)}}"></div>
                     </div>
                     <div class="col-md-4 col-12 budget-wrapper">
                         <div class="btn-group">
                             <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 {{$this->year}}
                             </button>
                             <div class="dropdown-menu">
                                 @foreach($years as $current_year)
                                    <a class="dropdown-item" href="#">{{$current_year}}</a>
                                 @endforeach
                             </div>
                         </div>
                         <h2 class="mb-25">{{$this->total_benefits}}</h2>
                         <div id="benefits-chart" data-benefits-month="{{json_encode($this->benefits_per_month, true)}}"></div>
                     </div>
                 </div>
             </div>
         </div>
         <!--/ Revenue Report Card -->
     </div>
    <!-- List DataTable -->
    <!--
    <div class="row">
        <div class="col-12">
            <div class="card invoice-list-wrapper">
                <div class="card-datatable table-responsive">
                    <table class="invoice-list-table table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th><i data-feather="trending-up"></i></th>
                            <th>Client</th>
                            <th>Total</th>
                            <th class="text-truncate">Issued Date</th>
                            <th>Balance</th>
                            <th>Invoice Status</th>
                            <th class="cell-fit">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>-->
    <!--/ List DataTable -->
    @section('page-script')
    <!-- Page js files -->
        <script src="{{asset('app-assets/js/scripts/pages/dashboard-analytics.js')}}"></script>
        <script src="{{asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>
    @endsection
</section>
