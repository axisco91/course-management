@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <section class="bs-validation" id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Editar Seguimiento
                    </div>
                </div>
                @livewire('tracings-update', ['id' => $id])
            </div>
        </div>
    </section>
@endsection
