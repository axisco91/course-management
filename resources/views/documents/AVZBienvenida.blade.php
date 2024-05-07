<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Guia_Bienvenida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bienvenida.css">

</head>
<body>
    <table>
        <tr>
            <td width="55%"  style="border: none; text-align:left;">
            </td>
            <td width="45%"  style="border: none;">
                <div class="col-md-12">
                    <img src="img-bienvenida/logo.png" alt="logoAzul" class="img-fluid fixed-height-img-logo">
                </div>
            </td>
        </tr>
    </table>

    <div class="container mt-3">
        <p>Estimado/a {{$trainingContract->student->name}} {{$trainingContract->student->surname}}.</p> 
    </div>

    <p>Ante todo quisiera darle la bienvenida a nuestro Centro de Formación Avz Formación para realizar la Actividad Formativa obligatoria vinculada a su Contrato de Formación en Alternancia:</p>

    <table>
        @foreach ($elements as $e)
        <tr>
            <td width="60%"  style="border: none; text-align:left;">
                {{$e->training_action->code}} {{$e->training_action->name}}
            </td>
            <td width="50%"  style="border: none;">
                {{ \Carbon\Carbon::parse($e->beginning)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($e->end)->format('d/m/y') }}
            </td>
        </tr>
        @endforeach
    </table>

    <p>Le recordamos que la fecha de inicio de la formación asociada a su contrato de formación y aprendizaje es el <b><u>{{ \Carbon\Carbon::parse($trainingContract->beginning)->locale('es')->isoFormat('D [de] MMMM [de] Y') }}</u></b> y, por lo tanto, a partir de esa fecha deberá acceder diariamente a la plataforma de teleformación para su realización. La dirección de la citada plataforma es:</p>    <p class="text-center"><b><a href="http://campus.avzformacion.com">http://campus.avzformacion.com</a></b></p>
    <p>y deberá acceder con los siguientes datos de acceso:</p>
    <p class="text-center">- Usuario: <b>{{$trainingContract->student->user}}</b> </p>
    <p class="text-center">- Contraseña: <b>{{$trainingContract->student->password}}</b> </p>
    
    <p><u>Al inicio de la formación</u>, y como requisito obligatorio establecido por el Servicio Público de Empleo, deberá realizar un breve <b>Módulo de Competencia Digital</b>. Por tanto, dispone de plazo hasta ese día para realizar el Test de Evaluación y enviar la Actividad de Aprendizaje Evaluable correspondiente al citado módulo.</p>
    
    
    <div style="position: absolute; bottom: 0; width: 100%;">
        <table class="mt-2" style="border-collapse: collapse; width: 100%;">
            <tr>
                <td width="70%" style="padding: 0; border: none;">
                    <p class="pie-fondo" style="margin: 0; padding-left: 0;">info@avzformacion.com - 957 923 473 - 644 680 310</p>
                </td>
                <td width="30%" style="padding: 0; border: none;" class="text-center">
                    <img src="img-bienvenida/eurocert.PNG" alt="eurocert" class="img-fluid fixed-height-img-eurocert">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    <table>
        <tr>
            <td width="55%"  style="border: none; text-align:left;">
            </td>
            <td width="45%"  style="border: none;">
                <div class="col-md-12">
                    <img src="img-bienvenida/logo.png" alt="logoAzul" class="img-fluid fixed-height-img-logo">
                </div>
            </td>
        </tr>
    </table>

    <p>Le recordamos que la formación que va a realizar es <b>OBLIGATORIA</b> y <u>deberá desarrollarla diariamente y en el horario formativo especificado en su contrato de formación</u>. Por ello es de vital importancia <u>que se conecte de forma diaria al curso a la plataforma de teleformación</u>, de tal forma que proceda a la visualización de los contenidos interactivos, realice <u>todas</u> las actividades evaluables y tenga una participación activa a través de las herramientas de comunicación de la plataforma: foros, chat, correo electrónico de la plataforma, etc. Así <u>su tiempo de conexión a la plataforma de teleformación deberá estar en consonancia con la duración teórica del módulo/unidadformativa que esté desarrollando</u>.</p>
    {{--FALTAN DATOS--}} 
    <p>Nuestro Centro de Formación pone a su disposición un <i>Tutor Formativo</i> personal para que pueda dirigirse a él, a través del e-mail plataforma de teleformación o mediante la siguiente dirección de correo electrónico <b><a href="mailto:tutorias@avzformacion.com">tutorias@avzformacion.com</a></b>, cada vez que le surja una duda o consulta sobre el temario que estás estudiando. En su caso este tutor es <b> D/Dª _____________________________________________ {{--FALTAN DATOS--}} </b>. Así mismo usted cuenta con un <i>Tutor Laboral</i> dentro de su empresa, para cualquier duda que le surja en la puesta en práctica de la formación dentro de su actividad laboral, <b> D/Dª {{$trainingContract->company_tutor}} </b>.</p>
    <p>Además podrás contactar con nuestro <i>Equipo de Administración y Seguimiento Pedagógico</i> enviando un correo electrónico a la dirección de <b><a href="mailto:tutorias@avzformacion.com">tutorias@avzformacion.com</a></b> o a través del teléfono <b>910600410</b>, en horario de <b>Lunes a Jueves de 10 a 14 h. y de 16 a 19 h. y los Viernes de 9 a 15 h.</b>, para cualquier aclaración necesaria referente al desarrollo del curso, la mecánica de acceso a la plataforma o realización de la formación.</p>
    <p>Deseando que la formación a desarrollar sea de su agrado le saluda atentamente.</p>

    <table>
        <tr>
            <td width="75%"  style="border: none; text-align:left;">
            </td>
            <td width="45%"  style="border: none;">
                <div class="col-md-12">
                    <img src="img-bienvenida/bienvenidaAvz.PNG" alt="firma" class="img-fluid fixed-height-img-logo">
                    <p>La Dirección de <b>Avz Formación</b></p>
                </div>
            </td>
        </tr>
    </table>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table class="mt-2" style="border-collapse: collapse; width: 100%;">
            <tr>
                <td width="70%" style="padding: 0; border: none;">
                    <p class="pie-fondo" style="margin: 0; padding-left: 0;">info@avzformacion.com - 957 923 473 - 644 680 310</p>
                </td>
                <td width="30%" style="padding: 0; border: none;" class="text-center">
                    <img src="img-bienvenida/eurocert.PNG" alt="eurocert" class="img-fluid fixed-height-img-eurocert">
                </td>
            </tr>
        </table>
    </div>
</body>
</html>