<div class="app-content">
    @if (session()->has('message'))
        <input hidden id="success-toast" data-type="success" data-show="true" value="{{ session('message') }}">
    @endif
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Perfil</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url("")}}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item active"> Perfil
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!--   <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                   <div class="mb-1 breadcrumb-right">
                       <div class="dropdown">
                           <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                           <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
                       </div>
                   </div>
               </div>-->
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12" wire:ignore>
                    <ul class="nav nav-pills mb-2">
                        <!-- account -->
                        <li class="nav-item">
                            <a class="nav-link btn-info" href="/user/setting_profile">
                                <i data-feather="user" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Perfil</span>
                            </a>
                        </li>
                        <li class="nav-item" style="margin-left: 10px">
                            <a class="nav-link active" href="">
                                <i data-feather="user" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Cambiar contraseña</span>
                            </a>
                        </li>
                    </ul>

                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Cambiar contraseña</h4>
                        </div>

                        <div class="card-body py-2 my-25">
                            <!-- form -->
                            <form class="validate-form mt-2 pt-50">
                                <div class="row">
                                    <div class="col-6 mb-1">
                                        <label class="form-label" for="name">Contraseña</label>
                                        <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña">@error('password') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                                    </div>
                                    <div class="col-6 mb-1">
                                        <label class="form-label" for="password_confirmation">Repetir contraseña</label>
                                        <input wire:model="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Repetir contraseña">@error('password_confirmation') <div class="invalid-feedback">Contraseña es requerido</div> @enderror
                                    </div>
                                    <div class="col-12 mb-1">
                                        <button type="button" id="save" class="btn btn-primary" wire:click.prevent="update()">Guardar</button>
                                    </div>
                                </div>
                            </form>
                            <!--/ form -->
                        </div>
                    </div>
                    <!--/ profile -->
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $('body').on('click', '#save', function(){
                content = ''
                if ($('#password').val() == '' || $('#password_confirmation').val() == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Falta datos',
                        html: '<div>Los dos campos son requerido</div>',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
                else if ($('#password').val() !== $('#password_confirmation').val()){
                    Swal.fire({
                        icon: 'error',
                        title: 'Falta datos',
                        html: '<div>Las contraseñas no coinciden.</div>',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                } else {

                }
            })
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
        </script>
    @endsection
</div>
