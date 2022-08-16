@extends('layouts/login_layout')

@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                        <!-- Brand logo--><div class="brand-logo">
                            <img style="height: 125px" src="{{URL::asset('images/logo/logo_vertical.png')}}">
                        </div>
                        <!-- /Brand logo-->
                        <!-- Left Text-->
                        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid" src="../../../app-assets/images/pages/forgot-password-v2-dark.svg" alt="Forgot password V2" /></div>
                        </div>
                        <!-- /Left Text-->
                        <!-- Forgot password-->
                        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                                <h2 class="card-title fw-bold mb-1">¿Has olvidado tu contraseña? 🔒</h2>
                                <p class="card-text mb-2">Introduce tu correo y te mandaremos unas instrucciones para resetear la contraseña</p>
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-1">
                                        <label class="form-label" for="forgot-password-email">Correo</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <button class="btn btn-primary w-100" tabindex="2">Enviar link para resetear</button>
                                </form>
                                <p class="text-center mt-2"><a href="{{'/login'}}"><i data-feather="chevron-left"></i> Volver al login</a></p>
                            </div>
                        </div>
                        <!-- /Forgot password-->
                    </div>
                </div>
            </div>
        </div>
@endsection
