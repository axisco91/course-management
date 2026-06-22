@extends('layouts/basicLayoutMaster')

<div>
    Hola {{ $studentName }},
</div>

<div style="margin-top: 10px;">
    Te recordamos que tu curso
    <strong>{{ $courseName }}</strong>
    finaliza en una semana.
</div>

<div style="margin-top: 10px;">
    <strong>Fecha de fin:</strong> {{ $courseEndDate }}
</div>

<div style="margin-top: 10px;">
    Un saludo.
</div>
