<section id="home">
    <div class="row match-height" wire:ignore>
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

         <!-- Revenue Report Card -->
         <div class="col-lg-8 col-12">
             <div class="card card-revenue-budget">
                 <div class="row mx-0">
                     <div class="col-md-8 col-12 revenue-report-wrapper">
                         <div class="d-sm-flex justify-content-between align-items-center mb-3">
                             <h4 class="card-title mb-50 mb-sm-0">Cursos Totales</h4>
                             <div class="d-flex align-items-center">
                                 <div class="d-flex align-items-center me-2">
                                     <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                     <span>Cursos</span>
                                 </div>
                             </div>
                         </div>
                         <div id="courses-year-chart"></div>
                     </div>
                     <div class="col-md-4 col-12 budget-wrapper">
                     <!--      <div>
                             <select wire:model.lazy="total_course_year" id="total_course_year">
                                 @foreach($this->years as $current_year)
                                    <option value="{{$current_year}}">{{$current_year}}</option>
                                 @endforeach
                             </select>
                         </div>-->
                         <h2 wire:model.lazy="total_courses_year" style="font-size: 48px;padding-top: 44%;" class="mb-25" id="total_courses">{{$this->total_courses_year}}</h2>
                         <div id="benefits-chart" data-co-month="{{json_encode($this->courses_per_month, true)}}"></div>
                     </div>
                 </div>
             </div>
         </div>
         <!--/ Revenue Report Card -->

        <div class="col-lg-6 col-12">
            <div>Pendiente envio correo Bienvenida</div>
            <livewire:chores-beginning-datatable/>
        </div>
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
    <script>
        $( document ).ready(function() {
            getCourses($('#total_course_year').val())
        })
        $('body').on('change', '#total_course_year', function(){
            getCourses($(this).val())
        })
        function getCourses(year){
            @this.set('total_course_year', year)
            this.Livewire.emit('getCoursesPerMonth')
            var $coursesYearChart = document.querySelector('#courses-year-chart');
            var coursesYearChartOptions;
            var coursesYearChart;
            coursesYearChartOptions = {
                chart: {
                    height: 230,
                    stacked: true,
                    type: 'bar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '17%',
                        endingShape: 'rounded'
                    },
                    distributed: true
                },
                colors: [window.colors.solid.primary, window.colors.solid.warning],
                series: [
                    {
                        name: 'Courses',
                        data: @this.get('courses_per_month')
                    },
                ],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                grid: {
                    padding: {
                        top: -20,
                        bottom: -10
                    },
                    yaxis: {
                        lines: { show: false }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dic'],
                    labels: {
                        style: {
                            colors: 'red',
                            fontSize: '0.86rem'
                        }
                    },
                    axisTicks: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: 'red',
                            fontSize: '0.86rem'
                        }
                    }
                }
            };
            coursesYearChart = new ApexCharts($coursesYearChart, coursesYearChartOptions);
            coursesYearChart.render();
        }
    </script>
    @endsection
</section>
