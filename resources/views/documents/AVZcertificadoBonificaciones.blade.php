<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado De Bonificaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/certificado.css">
</head>
<body>
    <!-- PRIMERA PÁGINA -->

<div class="mt-2">
    <div class="col-md-12 text-right">
        <img src="img-certificado/avz_logo_horizontal_CMYK_verde-gris_fondo-transparente.png" alt="avzlogo" class="img-fluid fixed-height-img-logo">
    </div>

    <div class="col-md-12">
        <h1 class="letra-color">CERTIFICADO DE BONIFICACIONES</h1>
    </div>

    <div class="col-md-12 mt-2">
        <p> D. Antonio J. Jiménez Agraz, en representación del centro de formación AVZ FORMACION, S.L., con CIF nº B16826638,
            y código de centro 8000001711, certifica la contratación de los servicios de formación teórica de un contrato para la
            Formación en Alternancia con la Empresa: {{$trainingContract->company->name}}, con CIF {{$trainingContract->company->cif}} y por el trabajador {{$trainingContract->student->name}} {{$trainingContract->student->surname}},
            con DNI {{$trainingContract->student->dni}}, con inicio el {{$trainingContract->beginning_formation}} y finalización el {{$trainingContract->end_formation}}  y con las siguientes bonificaciones mensuales:</p>
    </div>

    <div class="row mt-2">
        <p class="text-center letra-color">BONIFICACIONES POR LA FORMACIÓN TEÓRICA IMPARTIDA POR EL CENTRO</p>

        <table class="mx-auto" style="width: 85%">
            <thead class="letra-color">
                <tr>
                    <th>Mes</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Horas</th>
                    <th>Importe a Bonificar</th>
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
                        <td class="no-vertical-padding">{{$mesNombre}}</td>
                        <td class="no-vertical-padding">{{$e->start}}</td>
                        <td class="no-vertical-padding">{{$e->end}}</td>
                        <td class="no-vertical-padding">{{$e->amount / 5}}</td>
                        <td class="no-vertical-padding">{{$e->amount}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="col-md-12 m-3">
        <p style="font-size: x-small;">(*) Las cantidades expresadas en este documento son orientativas pudiendo variar en función de los periodos de no formación que deriven de vacaciones o bajas de cualquier índole, en cuyo caso recibirá un nuevo informe a tal efecto.</p>
    </div>
    
    @php
        $nombre_mes = now()->translatedFormat('F');
        $dia = now()->format('d');
        $anio = now()->format('Y');
    @endphp

    <div class="col-md-12 mt-2 mb-5">
        <p>Y para que conste donde proceda, firmo el presente certificado en Lucena, a {{$dia}} de {{$nombre_mes}} de {{$anio}}.</p>
    </div>
</div>
<div style="page-break-after: always;"></div>


<div class="mt-2">
    <div class="col-md-12 text-right">
        <img src="img-certificado/avz_logo_horizontal_CMYK_verde-gris_fondo-transparente.png" alt="avzlogo" class="img-fluid fixed-height-img-logo">
    </div>
    <div class="col-md-12 text-right mt-2">
        <p>Fdo. Antonio J. Jiménez Agraz</p>
    </div>
    <div style="height:500px"></div>

    <div class="col-md-12 mt-2">
        <p class="text-center letra-color">BONIFICACIÓN ADICIONAL POR LOS COSTES DERIVADOS DE LA OBLIGADA TUTORIZACIÓN EN LA EMPRESA</p>
    </div>

    <div class="col-md-12 mt-2">
        <p>Según el art. 8, apartado 4, de la Orden ESS/2518/2013, de 26 de diciembre, por la que se regulan los aspectos formativos del contrato para la formación y el aprendizaje:</p>
    </div>

    <div class="col-md-12 mt-1">
        <p><i>“A fin de financiar los costes derivados de la obligada tutorización en la empresa de cada trabajador a través del contrato para la formación y el aprendizaje, las empresas se podrán aplicar una, bonificación adicional a los costes señalados en el apartado 1, con una cuantía máxima de 1,5 euros por alumno y hora de tutoría, con un máximo de 40 horas por mes y alumno. En el supuesto de empresas de menos de cinco trabajadores la bonificación adicional tendrá una cuantía máxima de 2 euros por alumno y hora de tutoría, con un máximo de 40 horas por mes y alumno.”</i></p>
    </div>
</div>



<div style="page-break-after: always;"></div>


<div class="mt-2">
    <div class="col-md-12 text-right">
        <img src="img-certificado/avz_logo_horizontal_CMYK_verde-gris_fondo-transparente.png" alt="avzlogo" class="img-fluid fixed-height-img-logo">
    </div>
    <div class="col-md-12">
        <p><b>Tenga en cuenta que para poderse aplicar esta bonificación adicional el tutor de empresa ha debido realizar las siguientes funciones:</b></p>
        <p class="sin-margin-bottom">a) Realizar la comunicación con el centro de formación a través del tutor del centro de formación.</p>
        <p class="sin-margin-bottom">b) Coordinar con el tutor del centro de formación la elaboración del programa de formación correspondiente a la actividad formativa inherente al contrato.</p>
        <p class="sin-margin-bottom">c) Realizar el seguimiento del acuerdo para la actividad formativa, atendiendo al trabajador con la periodicidad que se establezca, durante el periodo de trabajo efectivo en la empresa, con el objeto de valorar el desarrollo del programa y establecer los apoyos formativos necesarios.</p>
        <p class="sin-margin-bottom">d) Colaborar con el tutor del centro de formación en la evaluación del aprendizaje desarrollado durante el tiempo del contrato y al término del mismo.</p>
        <p class="sin-margin-bottom">e) Velar para que el trabajador cumpla los correspondientes protocolos de seguridad y prevención de riesgos laborales asociados a los diferentes puestos de trabajo y aprendizaje, suministrando el asesoramiento necesario.</p>
        <p>f) Elaborar, al finalizar la actividad laboral de la persona trabajadora, un informe sobre el desempeño del puesto de trabajo y los resultados de aprendizaje alcanzados en la empresa.</p>
    </div>

    <div class="row mt-2">
        <table class="mx-auto" style="width: 85%">
            <thead class="letra-color">
                <tr>
                    <th>Mes</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Horas</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonus as $e)
                @php
                    $fechaInicio = \Carbon\Carbon::parse($e->start)->locale('es');
                    $fechaFin = \Carbon\Carbon::parse($e->end)->locale('es');
                    $mesNombre = ucfirst($fechaInicio->translatedFormat('F'));
                    $monthKey = $fechaInicio->format('Y-m');
                    $horasTotales = 0;
                    $diaInicio = $fechaInicio->day;
                    $diaFin = $fechaFin->day;

                    if ($diaInicio == 1 && $diaFin == $fechaFin->daysInMonth) {
                        $horasTotales = 40;
                    } else {
                        if ($fechaInicio->month==2 && $diaInicio==28) {
                            $diaInicio = 30;
                        }
                        if ($diaInicio > 1 && $diaInicio < 30) {
                            $start = 40;
                            for ($i = 2; $i <= $diaInicio; $i++) {
                                if ($start % 4 == 0 && $start > 1) {
                                    $start -= 2;
                                } else { 
                                    if ($start > 1) {
                                        $start -= 1;
                                    }
                                }
                                $horasTotales = $start;
                            }
                        } else if ($diaInicio >= 30) {
                            $horasTotales = 1;
                        }
                        else{
                            if ($fechaFin->month==2 && $diaFin==28) {
                            $diaFin = 30;
                            }
                            if ($diaFin > 1 && $diaFin < 30) {
                                $start = 0;
                                for ($i = 1; $i <= $diaFin; $i++) {
                                    if ($i % 3 == 0) {
                                        $start += 2;
                                    } else {
                                        $start++;
                                    }
                                    $horasTotales = $start;
                                }
                            } else if ($diaFin >= 30) {
                                $horasTotales = 40;
                            }
                        }
                    }
                @endphp
            
                    <tr>
                        <td class="no-vertical-padding">{{$mesNombre}}</td>
                        <td class="no-vertical-padding">{{$e->start}}</td>
                        <td class="no-vertical-padding">{{$e->end}}</td>
                        <td class="no-vertical-padding">{{ $horasTotales }}</td>
                        <td class="no-vertical-padding">
                            @php
                                $multiplier = ($trainingContract->company->average_template < 5) ? 2 : 1.5;
                            @endphp
                            {{ $horasTotales * $multiplier }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<br>
<footer>
    <table class="mt-2" style="border-collapse: collapse; width: 100%;">
        <tr>
            <td width="70%" style="padding: 0; border: none;">
                <p class="pie-fondo" style="margin: 0; padding-left: 0;">info@avzformacion.com - 957 923 473 - 644 680 310</p>
            </td>
            <td width="30%" style="padding: 0; border: none;" class="text-center">
                <img src="img-certificado/eurocert.PNG" alt="eurocert" class="img-fluid fixed-height-img-eurocert">
            </td>
        </tr>
    </table>
 </footer>
</body>
</html>