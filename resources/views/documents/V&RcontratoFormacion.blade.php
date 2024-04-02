<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato Formación - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
        hr{
            border: 2px solid rgb(225, 234, 231) !important; 
            color: rgb(225, 234, 231) !important; 
        }

        .border-green-900{
            border-color: rgb(24, 57, 46) !important; 
        }

        .border-green-100{
            border-color: rgb(184, 206, 198) !important; 
        }

        .font-bold{
            font-weight: bold; 
        }

        .container1 img{
            position: fixed; 
            top: 0%; 
            right: 5%;
            width: 25%;
            margin-bottom: 2%;
        }

        .bg-green-100{
            background-color: rgb(184, 206, 198) !important; 
        }

        .text-green-100{
            color: rgb(184, 206, 198) !important; 
        }

        .bg-green-900{
            background-color: rgb(24, 57, 46) !important; 
        }

        .text-green-900{
            color: rgb(24, 57, 46) !important; 
        }

        .titulo{
            position: fixed;
            top: 0%;
            left: 15%; 
            border-radius: 20%; 
            width: 50%; 
        }   

        .dni{
            position: fixed; 
            top: 24.8%; 
            right: 30%; 
        }

        .final_contract{
            position: fixed; 
            top: 28.7%; 
            right: 12.5%; 
        }

        .final_formative_activity{
            position: fixed; 
            top: 32.4%; 
            right: 5%; 
        }

        .text-center{
            text-align: center; 
        }

        .border-right{
            border-left: none !important; 
            border-top: none !important; 
            border-bottom: none !important; 
        }

        .border-bottom{
            border-left: none !important; 
            border-top: none !important; 
            border-right: none !important; 
        }

        .border-r-2{
            border-right-style: solid !important;
            border-right-width: 2px !important; 
            border-left: none !important; 
            border-top: none !important; 
        }

        .underline{
            text-decoration: underline; 
        }

        .footer1, .footer2, .footer3{
            font-size: 10px;
            position: fixed; 
            bottom: 0%; 
        }

        .footer1{
            left: 0%; 
            right: 0%; 
        }

        .footer2{
            position: fixed; 
            bottom: 0%; 
            left: 32%; 
        }

        .footer3{
            position: fixed; 
            bottom: 0%;  
            right: 0%; 
        }

        @media print {
        tr.page-break {
            break-after: page;
        }
        tr.spacer {
            border-top: 10px solid black;
        }
    }
    </style>
</head>
<body class="p-2">

    <div class="container1 mb-3">
        <p class="p-1 titulo bg-green-900 text-white text-center">CONTRATO DE FORMACIÓN EN ALTERNANCIA</p>
        <img src="./V&R/logo.png" alt="" style="width: 16%;">
    </div>

    <div style="margin-top:10px">
        <h1 class="text-left text-green-800 m-2" style="font-size: 16px;">PLANIFICACIÓN DE LA ACTIVIDAD FORMATIVA</h1>
        <hr>
        <section class="mt-3">
        <div class="first col-12" style="display: flex; justify-content: space-between; font-size: 8px;">
            <p class="text-green-900 ml-1">OCUPACIÓN: <span class="text-green-900 font-normal">{{$occupation->name}}</span></p>
            <p class="text-green-900 ml-1">ALUMNO/A: <span class="text-green-900 font-normal">{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</span></p>
            <p class="dni text-green-900 ml-1">DNI: <span class="text-green-900 font-normal">{{$trainingContract->student->dni}}</span></p>
        </div>
        <div class="second col-12" style="display: flex; justify-content: space-between; font-size: 8px;">
            <p class="text-green-900 ml-1">Fecha inicio del contrato: <span class="text-green-900 font-normal">{{$trainingContract->beginning}}</span></p>
            <p class="text-green-900 final_contract ml-1">Fecha de fin de contrato: <span class="text-green-900 font-normal">{{$trainingContract->end}}</span></p>
            <p class="text-green-900 ml-1">Fecha de inicio actividad formativa: <span class="text-green-900 font-normal">{{$trainingContract->beginning_formation}}</span></p>
            <p class="text-green-900 final_formative_activity ml-1">Fecha de fin actividad formativa: <span class="text-green-900 font-normal">{{$trainingContract->end_formation}}</span></p>
        </div>
    </section>
        <section class="mt-3">
            <table class="mt-3 table table-sm table-bordered border-green-900 border border-2">
                <tbody class="text-center">
                    <tr>
                        <th colspan="7" class="px-6 py-3 text-xs border-2 border-dark text-green-900 font-bold">
                            ESPECIALIDAD FORMATIVA <span class="font-normal">{{$trainingContract->formation_hours}}h</span>
                        </th>
                    </tr>
                    <tr class="bg-green-900 border-2 border-dark">
                        <td colspan="2" class="px-6 py-3 text-xs text-green-100 border-2 border-right border-green-100 font-bold">
                            ESPECIALIDADES FORMATIVAS
                        </td>
                        <td colspan="5" class="px-6 py-3 text-xs text-green-100 font-bold">
                            LUGAR Y FECHAS DE REALIZACIÓN DE LA ACCIÓN FORMATIVA
                        </td>
                    </tr>
                    <tr class="bg-green-100 text-green-900 border-bottom border-2 border-green-900 font-bold">
                        <td class="border-r-2 border-green-900">Código</td>
                        <td class="border-r-2 border-green-900">Denominación</td>
                        <td class="border-r-2 border-green-900">Centro de formación</td>
                        <td class="border-r-2 border-green-900">Fecha Inicio / Fin</td>
                        <td class="border-r-2 border-green-900">Horas Semana</td>
                        <td class="border-r-2 border-green-900">Días Semana</td>
                        <td>Horario</td>
                    </tr>
                    @foreach($elements as $e)
                    <tr class="text-green-900 border-bottom border-2 border-green-100 font-bold">
                        <td class="border-r-2 border-green-100">{{$e->training_action->codigo}}</td>
                        <td class="border-r-2 border-green-100">{{$e->training_action->name}}</td>
                        <td class="border-r-2 border-green-100">{{ $e->training_contract->provider->name ?? '' }}</td>
                        <td class="border-r-2 border-green-100">{{$e->training_contract->beginning_formation}} / {{$e->training_contract->beginning_formation}}</td>
                        <td class="border-r-2 border-green-100">{{$company->weekly_hours}}</td>
                        <td class="border-r-2 border-green-100">{{$daysWeek}}</td>
                        <td>{{$e->training_contract->working_hours}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-xs">Durante las vacaciones legalmente establecidas no se organizará ninguna actividad formativa. Días con derecho a su disfrute para el periodo contractual vigente: 6</p>
        </section>
        <div class="pl-5 ">
            <img src="./V&R/firma.PNG" alt="">
        </div>
        <div>
            <p class="underline footer1 text-green-100 font-bold ml-2">EMPRESA: <span class="text-green-900 underline">{{$company->name}}</span></p>
            <p class="underline footer2 text-green-100 font-bold ml-2">TRABAJADOR/A: <span class="text-green-900 underline">{{$trainingContract->company_tutor}}</span></p>
            <p class="underline footer3 text-green-100 font-bold ml-2">CENTRO DE FORMACIÓN: <span class="text-green-900 underline">{{$trainingContract->center_of_work}}</span></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>