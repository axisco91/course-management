<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Planificación General</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            .color{
                color: rgb(2, 145, 212); 
            }

            .bg-color{
                background-color: rgb(0, 144, 212); 
            }
            
            .bg-color2{
                background-color: rgb(242, 242, 242); 
            }

            .font-semibold{
                font-weight: 600;
            }

            .border-right-dotted{
                border-right: 1px dotted black;
            }

            .text-xs{
                font-size: 0.75rem;
                font-style:italic; 
            }

            .text-sm{
                font-size: 0.875rem;
            }

            .border-right{
                border-right: 1px solid black;
                border-bottom:none; 
                border-left: none; 
                border-top: none;
            }
        </style>
    </head>
    <body>
        <!-- CABECERA -->
        <div class="text-end">
            <img src="./Avanza/logo.png" alt="" width="20%">
        </div>

        <!-- INFORMACIÓN -->
        <div class="text-center">
            <h1 class="color font-semibold">CONTRATOS PARA LA FORMACION Y EL APRENDIZAJE</h1>

            <article class="mt-4">
                <p><strong>PLANIFICACIÓN DE LA ACTIVIDAD FORMATIVA Y DE LA EVALUACIÓN FINAL – Alumno/a: </strong> {{$trainingContract->student->name}} {{$trainingContract->student->surname}} – {{$trainingContract->student->dni}}</p>
                <p class="font-semibold mt-3">Fecha de inicio del contrato: {{$trainingContract->beginning}}. Fecha de fin del contrato: {{$trainingContract->end}}</p>
                <p class="font-semibold mt-3">Fecha de inicio actividad formativa: {{$trainingContract->beginning_formation}}. Fecha de fin actividad formativa: {{$trainingContract->end_formation}}.</p>
                <p class="font-semibold mt-3">(En esta planificación se contempla como periodo de vacaciones del trabajador del 01/04/23 al 16/04/23,
                    01/08/23 al 14/08/23 y del 01/08/24 al 30/08/24)</p>
            </article>

            <table class="mx-auto border border-2 border-dark2 mt-3">
                <tbody>
                    <tr class="border-bottom border-2 border-dark">
                        <td colspan="8" class="font-semibold ps-3 text-start">ESPECIALIDADES SEPE: AGENTE COMERCIAL</td>
                    </tr>
                    <tr class="border-bottom border-2 border-dark bg-color text-white text-center">
                        <td colspan="2" class="border-right border-2 border-dark font-semibold">MÓDULOS PROFESIONALES/FORMATIVO</td>
                        <td colspan="4" class="border-right border-2 border-dark font-semibold">LUGAR Y FECHAS DE REALIZACIÓN DE LA ACCIÓN FORMATIVA </td>
                        <td colspan="2" class="font-semibold">EVALUACIÓN FINAL</td>
                    </tr>
                    <tr class="bg-color2 border-bottom border-2 border-dark text-center text-sm">
                        <td class="border-right-dotted font-semibold">CÓDIGO</td>
                        <td class="border-right border-2 border-dark font-semibold">DENOMINACIÓN</td>
                        <td class="border-right-dotted font-semibold">EMPRESA</td>
                        <td class="border-right border-2 border-dark font-semibold">FECHAS</td>
                        <td class="border-right-dotted font-semibold">CÓDIGO CENTRO FORMACIÓN</td>
                        <td class="border-right border-2 border-dark font-semibold">FECHAS</td>
                        <td class="border-right-dotted font-semibold">LUGAR (centro formación)</td>
                        <td class="font-semibold">EVALUACIÓN FINAL</td>
                    </tr>
                    @foreach ($elements as $e)
                        <tr class="border-bottom border-2 border-dark text-center text-sm">
                            <td class="border-right-dotted font-semibold">
                                {{$e->training_action->codigo}}
                                Horas: {{$e->training_action->total_hours}}
                            </td>
                            <td class="border-right border-2 border-dark font-semibold"> {{$e->training_action->name}} </td>
                            <td class="border-right-dotted font-semibold"> {{$e->training_action->webPlatform->codigo ?? ''}} - {{$e->training_action->webPlatform->name}}</td>
                            <td class="border-right border-2 border-dark font-semibold">
                                Inicio: {{$e->training_contract->beginning_formation}} -
                                Fin: {{$e->training_contract->end_formation}}
                             </td>
                            <td colspan="2" class="border-right border-2 border-dark font-semibold">No tiene sesiones presenciales</td>
                            <td colspan="2" class="font-semibold">No tiene sesiones presenciales</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mx-auto mt-2">
                <p class="text-xs font-semibold text-start">* Los días que por exámenes o sesiones de formación presencial el horario supere el tiempo de formación previsto se reducirá proporcionalmente el horario de trabajo para que el cómputo 
                    total de formación y trabajo no exceda la jornada máxima legal</p>
            </div>
        </div>
    </body>
</html>