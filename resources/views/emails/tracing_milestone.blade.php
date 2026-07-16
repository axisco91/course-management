@if(in_array(($mailType ?? ''), ['quarter', 'half'], true))
<div>
    Estimado alumno,
</div>

<div style="margin-top: 10px;">
    Tu curso "<strong>{{ $formativeActionLabel }}</strong>", alcanza hoy el {{ $milestoneLabel }} de su duración total.
</div>

<div style="margin-top: 10px;">
    Recuerda que para conseguir el apto debes visualizar la totalidad del contenido, realizando todas las evaluaciones y tareas y obteniendo al menos una nota mínima de 5 en cada una de ellas.
</div>

<div style="margin-top: 10px;">
    Como sabes, estoy a tu disposición ante cualquier consulta.
</div>

<div style="margin-top: 10px;">
    Saludos.
</div>
@elseif(($mailType ?? '') === 'three_quarters')
<div>
    Estimado alumno,
</div>

<div style="margin-top: 10px;">
    Tu curso "<strong>{{ $formativeActionLabel }}</strong>", alcanza hoy el 75% de su duración total.
</div>

<div style="margin-top: 10px;">
    Recuerda que para el <strong>{{ $courseEndDate ?? '-' }}</strong> debes haber visualizado la totalidad del contenido, realizando todas las evaluaciones y tareas, obteniendo al menos una nota mínima de 5 para alcanzar los requisitos establecidos y completar las horas de visualización.
</div>

<div style="margin-top: 10px;">
    Como sabes, estoy a tu disposición ante cualquier consulta.
</div>

<div style="margin-top: 10px;">
    Saludos.
</div>
@elseif(($mailType ?? '') === 'final')
<div>
    Estimado alumno,
</div>

<div style="margin-top: 10px;">
    Contacto contigo para comunicarte que tu curso: "<strong>{{ $formativeActionLabel }}</strong>" llega hoy a su fin.
</div>

<div style="margin-top: 10px;">
    En esta acción formativa has obtenido la calificación de <strong>{{ ($finalResult ?? 'apto') === 'no_apto' ? 'NO APTO' : 'APTO' }}</strong>. Recuerda, que debes cumplir con los requisitos estipulados en tu contrato en todas las acciones formativas a desarrollar a lo largo de la planificación.
</div>

<div style="margin-top: 10px;">
    Ante cualquier duda que te surja, como siempre, estoy a tu disposición.
</div>

<div style="margin-top: 10px;">
    Saludos.
</div>
@else
<div>
    Hola,
</div>

<div style="margin-top: 10px;">
    Te enviamos un seguimiento de tu curso
    <strong>{{ $formativeActionLabel }}</strong>.
</div>

<div style="margin-top: 10px;">
    <strong>Hito:</strong> {{ $milestoneLabel }}<br>
    <strong>Fecha del hito:</strong> {{ $milestoneDate ?? '-' }}<br>
    <strong>Fecha de fin del curso:</strong> {{ $courseEndDate ?? '-' }}
</div>

<div style="margin-top: 10px;">
    Un saludo.
</div>
@endif
