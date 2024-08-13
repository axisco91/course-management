<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Bonificaciones - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Calibri', sans-serif !important;
            font-size: 12px;
        }
        h1 {
            font-weight: bold;
            font-size: 1.6rem;
        }
        h2 {
            font-family: 'Calibri', sans-serif !important;
        }
        .icono img {
            position: fixed;
            right: 5%;
            width: 150px;
        }
        .subrayado {
            text-decoration: underline;
        }
        .table-bordered thead th {
            border-bottom: 2px solid rgb(168, 208, 141);
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .table-bordered tbody tr {
            border-bottom: 2px solid rgb(239, 246, 234);
            border-top: none;
            border-left: none;
            border-right: none;
            text-align: center;
        }
        .table-bordered tbody tr .borde {
            border-bottom: none;
            border-top: none;
            border-left: none;
            border-right: 2px solid rgb(208, 229, 193);
        }
        .table-custom tbody tr:nth-child(odd) {
            background-color: rgb(226, 239, 217);
        }
        .table-custom tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        .tabla-pequena {
            font-size: 12px;
        }
        .letra-pequena {
            font-size: 10px;
        }
        .segunda-parte {
            margin-top: 0 !important;
        }
        /* Nueva clase para evitar el salto de página dentro de la tabla */
        .no-break {
            page-break-inside: avoid;
        }
  
        body, h1, h2, h3, h4, h5, h6, p {
            font-family: 'Calibri', sans-serif !important;
        }

    </style>
</head>
<body class="p-5">
    <!-- LOGO -->
    <div class="icono mb-4">
        <img src="./V&R/logo.png" alt="">
    </div>
    <section>
        <!-- PRIMERA PARTE -->
        <h1 class="text-center mt-3 pb-3">CERTIFICADO DE BONIFICACIONES</h1>
        <article class="mt-4 mb-3 text-center">
            MV & JAR CONSULTORES, S.L., CON CIF: B72132988, con domicilio de notificaciones en C/ Real Fernando, local 4, código postal 11540,  Sanlúcar de Barrameda (Cádiz), representada por Don. Manuel 
            Villegas Rosa, mayor de edad con DNI: 79252530G, Centro Acreditado en el Registro Estatal para la 
            impartición  de  formación  con  el  N.º  de  registro  8000000645,  correo  electrónico  a  efectos  de 
            notificaciones: info@vrconsultores.es, <strong>CERTIFICA</strong>: 
            <div class="mt-3">
                La contratación de los servicios de formación teórica en la modalidad de TELEFORMACIÓN inherente a 
                un contrato para la Formación y el Aprendizaje en Alternancia con el siguiente detalle: 
                <p>Empresa: <span class="subrayado">{{$trainingContract->company->name}}</span>– CIF <span class="subrayado">{{$trainingContract->company->nif}}</span> </p>
                <p>Trabajador/a: <span class="subrayado">{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</span> </p>
                <p>Contratación con inicio el <span class="subrayado">{{$trainingContract->beginning}}</span>, y finalización el <span class="underline">{{$trainingContract->end}}</span>. </p>
            </div>
            <div class="mt-3">
                A continuación, le detallamos los importes a bonificarse en los seguros sociales del mes, correspondiente 
                a la formación:
            </div>
        </article>
        <!-- TABLA -->
        <table class="mx-auto table table-bordered table-custom tabla-pequena no-break">
            <thead>
                <tr class="text-center font-bold">
                    <th>NOMBRE TRABAJADOR/A</th>
                    <th>DNI</th>
                    <th>F.INICIO</th>
                    <th>F.FIN</th>
                    <th>HORAS</th>
                    <th>IMPORTE</th>
                </tr>
            </thead>
            <tbody>
            @foreach($bonus as $e)
                @php
                    $fechaInicio = \Carbon\Carbon::parse($e->start)->locale('es');
                    $mesNombre = ucfirst($fechaInicio->translatedFormat('F'));
                    $monthKey = $fechaInicio->format('Y-m');
                    $sumaHoras += $monthlyFormationHours->{$monthKey} ?? 0;
                @endphp
                <tr>
                    <td class="borde">{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</td>
                    <td class="borde">{{$trainingContract->student->dni}}</td>
                    <td class="borde">{{$e->start}}</td>
                    <td class="borde">{{$e->end}}</td>
                    <td class="borde">
                    {{ property_exists($monthlyFormationHours, $monthKey) ? $monthlyFormationHours->{$monthKey} : 'N/A' }}
                    </td>
                    <td>{{ property_exists($monthlyFormationHours, $monthKey) ? $monthlyFormationHours->{$monthKey}*5 : 'N/A' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-end mt-3 mr-5 pr-5">
            <p class="text-lg">Total, bonificaciones de la empresa: {{$sumaHoras}} h {{$sumaHoras*5}} €</p>
        </div>
        <!-- SEGUNDA PARTE -->
        <h2 class="font-bold pb-3 segunda-parte">IMPORTANTE:</h2>
        @php
            $meses = [
                'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
            ];
            $dia = \Carbon\Carbon::parse($trainingContract->beginning)->format('d');
            $num_mes = \Carbon\Carbon::parse($trainingContract->beginning)->format('m');
            $anio = \Carbon\Carbon::parse($trainingContract->beginning)->format('Y');
            $mes = $meses[$num_mes - 1];
        @endphp
        <article class="mt-4 mb-3">
            Si durante la duración del contrato surge cualquier situación que suponga una modificación una baja 
            anticipada o una suspensión temporal del contrato recogidas en el artículo 2.2.b de la ley 3/2012: baja 
            por IT, baja por maternidad, paternidad, riesgo durante la lactancia, adopción o acogimiento; es necesario 
            que nos informe de manera inmediata; para que podamos paralizar y/o reanudar correctamente la 
            formación. Al mismo tiempo, nos debe remitir los documentos justificativos de dicha situación para 
            proceder a comunicarla al Servicio Público de Empleo competente.
            <p>Y para que sirva al interesado como justificante, se expide el presente documento en Sanlúcar de Barrameda, a <span class="subrayado">{{ $dia }}</span> de <span class="subrayado">{{ $mes }}</span> de <span class="subrayado">{{ $anio }}</span>.</p>
        </article>
        <!-- FIRMA -->
        <img width="150" src="./V&R/firma.PNG" alt="">
        <p class="mt-2">Manuel Villegas Rosa Gerencia.</p>
    </section>
    <footer class="pt-5 mt-5 text-center letra-pequena">
        <p>MV & JAR CONSULTORES, S.L. – B72132988 – C/ REAL FERNANDO, Local 4 – 11.540 SANLÚCAR DE BDA. (CÁDIZ) </p>
        <p>Contacto: Silvia Arcas - 651 926 502 – 956 367 562 – <a href="#">silvia.arcas@vrconsultores.es</a> – vrconsultores.es</p>
    </footer>
</body>
</html>
