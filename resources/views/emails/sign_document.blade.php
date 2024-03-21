@extends('layouts/basicLayoutMaster')

<div>
    Hola {{$name}}, Accede al siguiente enlace para firmar un documento necesario para la formación.
    <a href="http://localhost:3000/view-pdf/{{$key}}" class="btn btn-primary" style="margin: 10px;text-align: center">Pincha aquí</a>
</div>
<div>
    Un saludo
</div>
