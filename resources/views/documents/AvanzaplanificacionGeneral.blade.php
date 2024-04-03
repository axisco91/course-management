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

            <table class="mx-auto border border-2 border-black w-11/12 mt-5">
                <thead>
                    <tr class="border-bottom border-2 border-dark">
                        <th colspan="8" class="font-semibold pl-3 text-start">ESPECIALIDADES SEPE: AGENTE COMERCIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b-2 border-black bg-color text-white text-center">
                        <td colspan="2" class="border-right border-2 border-dark font-semibold">MÓDULOS PROFESIONALES/FORMATIVO</td>
                        <td colspan="4" class="border-r-2 border-black font-semibold">LUGAR Y FECHAS DE REALIZACIÓN DE LA ACCIÓN FORMATIVA </td>
                        <td colspan="2" class="font-semibold">EVALUACIÓN FINAL</td>
                    </tr>
                    <tr class="bg-color2 border-b-2 border-black">
                        <td class="border-right-dotted font-semibold">CÓDIGO</td>
                        <td class="border-r-2 border-black font-semibold">DENOMINACIÓN</td>
                        <td class="border-right-dotted font-semibold">EMPRESA</td>
                        <td class="border-r-2 border-black font-semibold">FECHAS</td>
                        <td class="border-right-dotted font-semibold">CÓDIGO CENTRO FORMACIÓN</td>
                        <td class="border-r-2 border-black font-semibold">FECHAS</td>
                        <td class="border-right-dotted font-semibold">LUGAR (centro formación)</td>
                        <td class="font-semibold">EVALUACIÓN FINAL</td>
                    </tr>
                    <tr>
                        <td class="border-right-dotted font-semibold">...</td>
                        <td class="border-r-2 border-black font-semibold">...</td>
                        <td class="border-right-dotted font-semibold">...</td>
                        <td class="border-r-2 border-black font-semibold">...</td>
                        <td colspan="2" class="border-r-2 border-black font-semibold">...</td>
                        <td colspan="2" class="font-semibold">...</td>
                    </tr>
                </tbody>
            </table>
            <div class="mx-auto">
                <p class="text-xs italic font-semibold text-start">* Los días que por exámenes o sesiones de formación presencial el horario supere el tiempo de formación previsto se reducirá proporcionalmente el horario de trabajo para que el cómputo 
                    total de formación y trabajo no exceda la jornada máxima legal</p>
            </div>
        </div>
    </body>
</html>