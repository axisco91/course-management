<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Bonificaciones - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="p-5">

    <!-- LOGO -->
    <div class="flex justify-end">
        <img width="150" src="./V&R/logo.png" alt="">
    </div>

    <section>
        <!-- PRIMERA PARTE -->
        <h1 class="font-bold text-2xl pb-3">CERTIFICADO DE BONIFICACIONES</h1>

        <article class="mt-4 mb-3" >
            MV & JAR CONSULTORES, S.L., CON CIF: B72132988, con domicilio de notificaciones en C/ Real Fernando, local 4, código postal 11540,  Sanlúcar de Barrameda (Cádiz), representada por Don. Manuel 
            Villegas Rosa, mayor de edad con DNI: 79252530G, Centro Acreditado en el Registro Estatal para la 
            impartición  de  formación  con  el  N.º  de  registro  8000000645,  correo  electrónico  a  efectos  de 
            notificaciones: info@vrconsultores.es, <span class="font-bold">CERTIFICA</span>: 
            
            <div class="mt-3">
                La contratación de los servicios de formación teórica en la modalidad de TELEFORMACIÓN inherente a 
                un contrato para la Formación y el Aprendizaje en Alternancia con el siguiente detalle: 
                <p>Empresa: <span class="underline">{{$company->name}}</span>– CIF <span class="underline">{{$company->nif}}</span> </p>
                <p>Trabajador/a: <span class="underline">{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</span> </p>
                <p>Contratación con inicio el <span class="underline">{{$trainingContract->beginning}}</span>, y finalización el <span class="underline">{{$trainingContract->end}}</span>. </p>
            </div>    
            
            <div class="mt-3">
                A continuación, le detallamos los importes a bonificarse en los seguros sociales del mes, correspondiente 
                a la formación:
            </div>
        </article>

        <!-- TABLA -->
        <table class="mx-auto">
            <thead>
                <tr class="font-bold border-4 border-green-600">
                    <th>NOMBRE TRABAJADOR/A</th>
                    <th>DNI</th>
                    <th>F.INICIO</th>
                    <th>F.FIN</th>
                    <th>HORAS</th>
                    <th>IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elements as $e)
                    <tr class="border-b-2 border-green-600 text-center">
                        <td>{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</td>
                        <td class="border-l-2 border-r-2 border-green-600">{{$trainingContract->student->dni}}</td>
                        <td class="border-l-2 border-r-2 border-green-600">{{$e->beginning}}</td>
                        <td class="border-l-2 border-r-2 border-green-600">{{$e->end}}</td>
                        <td class="border-l-2 border-r-2 border-green-600">
                            @foreach($monthlyFormationHours as $month => $hours)
                                {{$month}}
                                {{$hours}}
                            @endforeach    
                        </td>
                        <td >(importe)</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3 mr-5 pr-5">
            <p class="text-lg">Total, bonificaciones de la empresa: (suma horas)h (suma importe)€</p>
        </div>

        <!-- SEGUNDA PARTE -->
        <h2 class="font-bold text-xl pb-3">IMPORTANTE:</h2>

        <article class="mt-4 mb-3">
            Si durante la duración del contrato surge cualquier situación que suponga una modificación una baja 
            anticipada o una suspensión temporal del contrato recogidas en el artículo 2.2.b de la ley 3/2012: baja 
            por IT, baja por maternidad, paternidad, riesgo durante la lactancia, adopción o acogimiento; es necesario 
            que nos informe de manera inmediata; para que podamos paralizar y/o reanudar correctamente la 
            formación. Al mismo tiempo, nos debe remitir los documentos justificativos de dicha situación para 
            proceder a comunicarla al Servicio Público de Empleo competente.
            <p>Y para que sirva al interesado como justificante, se expide el presente documento en Sanlúcar de Barrameda, a 05 de febrero de 2024.</p>
        </article>

        <!-- FIRMA -->
        <img width="150" src="./V&R/firma.PNG" alt="">
        <p class="mt-2">Manuel Villegas Rosa Gerencia.</p>
    </section>

    <footer class="pt-5 mt-5 text-center">
        <p>MV & JAR CONSULTORES, S.L. – B72132988 – C/ REAL FERNANDO, Local 4 – 11.540 SANLÚCAR DE BDA. (CÁDIZ) </p>
        <p>Contacto: Silvia Arcas - 651 926 502 – 956 367 562 – <a href="#" class="text-blue-500 underline hover:text-blue-700">silvia.arcas@vrconsultores.es</a> – vrconsultores.es</p>
    </footer>
</body>
</html>