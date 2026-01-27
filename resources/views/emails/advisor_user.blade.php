@extends('layouts/basicLayoutMaster')

<div>
    Hola, Estos son tus credenciales para acceder a la plataforma de {{$name}}.

</div>
<p>Usuario: {{$username}}</p>
<P>Contraseña: {{$password}}</P>
<p>
    <a href="{{$url}}" class="btn btn-primary" style="margin: 10px;text-align: center">Entrar</a>
</p>
<div>
    Un saludo
</div>
