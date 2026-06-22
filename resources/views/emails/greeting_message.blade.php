@extends('layouts/basicLayoutMaster')

<div>
    Hola {{ $studentName }},
</div>

<div style="margin-top: 10px;">
    Te damos la bienvenida al curso
    <strong>{{ $courseName }}@if(!empty($courseGroup)) (Grupo {{ $courseGroup }})@endif</strong>.
</div>

<div style="margin-top: 10px;">
    <strong>Fecha inicio:</strong> {{ $courseStartDate ?? '-' }}<br>
    <strong>Fecha fin:</strong> {{ $courseEndDate ?? '-' }}
</div>

<div style="margin-top: 10px;">
    Desde {{ $platformName }} te acompañaremos durante toda la formación.
</div>

<div style="margin-top: 10px;">
    <strong>URL:</strong> {{ $platformUrl ?? '-' }}<br>
    <strong>Usuario:</strong> {{ $platformUsername ?? '-' }}<br>
    <strong>Contraseña:</strong> {{ $platformPassword ?? '-' }}
</div>

@if(!empty($platformUrl))
<p style="margin-top: 14px;">
    <a href="{{ $platformUrl }}" class="btn btn-primary" style="margin: 0; text-align: center;">
        Acceder a la plataforma
    </a>
</p>
@endif

<div style="margin-top: 10px;">
    Un saludo.
</div>
