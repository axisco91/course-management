
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANEXO AL CONTRATO DE TRABAJO DE FORMACIÓN EN ALTERNANCIA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/acuerdo.css">
</head>
<body>

    <!-- PRIMERA PÁGINA -->
    <table width="100%">
        <tr>
            <td width="30%"  style="border: none;">
                <img src="img-acuerdo/Logo V&R grande transparente.png" alt="Logo" style="max-width: 100%;">
            </td>
            <td width="70%"  style="border: none;">
                <h1>ANEXO AL CONTRATO DE TRABAJO DE FORMACIÓN EN ALTERNANCIA</h1>
                <p style="font-size: 14px;">
                    CONVENIO DE COLABORACIÓN SUSCRITO ENTRE EL CENTRO DE FORMACIÓN,
                    LA EMPRESA Y LA PERSONA TRABAJADORA PARA EL DESARROLLO DEL PLAN
                    FORMATIVO INDIVIDUAL
                </p>
            </td>
        </tr>
    </table>
    

        <p>
            Convenio de colaboración sujeto al art.11.2. e) del Real Decreto-ley 32/2021 de 28 de diciembre, de medidas urgentes para
            la reforma laboral y toda la normativa dictada con relación a la actividad formativa y actividad laboral.
        </p>

    <!-- Datos de Centro de Formación -->

        <h3>
            Datos de Centro de Formación:
        </h3>
        <p>
            MV & JAR CONSULTORES, S.L., CON CIF: B72132988, con domicilio de notificaciones en C/ Real Fernando, local 4, código
            postal 11540, Sanlúcar de Barrameda (Cádiz), representada por Don. Manuel Villegas Rosa, mayor de edad con DNI:
            79252530G, en condición de Administrador Único. Centro Acreditado en el Registro Estatal para la impartición de formación
            con el N.º de registro 8000000645, correo electrónico a efectos de notificaciones: info@vrconsultores.es
        </p>


    <!-- Datos Generales -->


        <h1>
            1. DATOS GENERALES
        </h1>
        <div class="row">
            <div class="col-md-12 mx-2 my-2">
                <h1 class="no-line-break">LA ACTIVIDAD FORMATIVA ESTARÁ DIRIGIDA A LA OBTENCIÓN DE </h1>
                <p class="no-line-break">(desglose en apartado 2):</p>
                <p>
                    Título de formación profesional (denominación): _________________________
                </p>
                <p>
                    Certificado de profesionalidad (denominación): _________________________
                </p>
                <p>
                    Certificación académica ______________________ Acreditación parcial acumulable _____________________
                </p>
                <p>
                    X Itinerario del Catálogo de especialidades formativas del Sistema Nacional de Empleo:
                </p> 
                <p>
                <u>{{$occupation->name}} </u>
                </p>
            </div>
        </div>


    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h1>DATOS DE LA EMPRESA:</h1>
            <p>
                Razón social  <u>{{$company->name}}</u> CIF/NIF/NIE  <u>{{$company->nif}}</u>
            </p>
            <p>
                D./Dña. <u>{{$company->legal_representative}}</u> en concepto de <u>
                <?php
                if($company->company_type_id=="Autónomo"){
                    echo "TITULAR";
                }else{
                    echo "ADMINISTRADOR/A";
                }
            ?>
            </u>
            NIF/NIE  <u>{{$company->dni_legal_representative}}</u> 
            </p>
            <p>
                Correo electrónico de la empresa <u>{{$company->email}}</u>  Tfno. Empresa <u>{{$company->telephone}} </u>
            </p>
            
            <p>
                Tutor/a de la empresa – D./Dña. <u> {{$trainingContract->company_tutor}} </u> Horas mensuales <u>40</u> NIF/NIE <u>{{$trainingContract->company_tutor_dni}}</u>
            </p>
            <p>
                Cualificación y/o experiencia profesional adecuada <input type="checkbox" id="opcion1" name="opcion1" checked>
                Empresa con menos de 5 trabajadores <input type="checkbox" id="opcion2" name="opcion2" {{ $company->average_template <= 5 ? 'checked' : '' }}>
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h1> DATOS DEL TRABAJADOR:</h1>
            <p>D./Dña. <u> {{$student->name}} {{$student->surname}}</u> NIF/NIE <u> {{$student->dni}} </u> Fecha de nac. <u> {{$student->date_of_birth}} </u> </p>
            <p>Reúne requisitos de acceso a la Formación de este contrato</p>
            <p>
                Inscrito/a en el sistema Nacional de Garantía Juvenil <input type="checkbox" id="opcion3" name="opcion3" {{ $trainingContract->youth_guarantee == 1 ? 'checked' : '' }}>
            </p>
            <p>
                Trabajador/a con discapacidad <input type="checkbox" id="opcion4" name="opcion4" {{ $trainingContract->disabled == 1 ? 'checked' : '' }}>
            </p>
            <p>
                Trabajador/a en situación de exclusión social en empresas de inserción <input type="checkbox" id="opcion5" name="opcion5" {{ $trainingContract->social_exclusion == 1 ? 'checked' : '' }}>
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h1>DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA:</h1>
            <p>Identificador contrato n.º ____________________ (a consignar una vez comunicada la formalización del contrato)</p>
            <p>Fecha de inicio: <u>{{$trainingContract->beginning}}</u></p>
            <p>Puesto de trabajo u ocupación: <u>{{$occupation->name}} </u> Cód. CNO <u>{{$occupation->cno}} </u></p>
            <p>Provincia del centro de trabajo: <u>{{$province->name}} </u> Horas de contrato, según convenio: <u>{{$trainingContract->annually_day_hours}} </u> </p>
            <p>Convenio aplicable <u> {{ $applicableAgreement ? $applicableAgreement->name : '' }}  {{ $applicableAgreement ? "({$applicableAgreement->agreementType->type})" : '' }} </u></p>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

    <!-- SEGUNDA PÁGINA-->
    <table>
        <tr>
            <td style="border:none; text-align:center">
                <img src="img-acuerdo/Logo V&R grande transparente.png" alt="logo" class="img-fluid fixed-height-img-logo">
            </td>
        </tr>
    </table>

    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h1>DATOS DEL CENTRO DE FORMACIÓN:</h1>
            <p>Razón social <u>{{$company->name}}</u>  CIF/NIF/NIE <u>{{$company->nif}}</u></p>
            <p>Dirección <u>{{$company->address}}</u> CP <u>{{$company->post_code}}</u> Municipio <u>{{$company->population}}</u></p>
            <p>Provincia <u>{{$province->name}}</u> Teléfono <u>{{$company->telephone}} </u>  Correo electrónico <u>{{$company->email}}</u> </p>
            <p>D./Dña. <u>{{$company->legal_representative}}</u> En concepto de <u> (representante)</u>   NIF/NIE <u>{{$company->dni_legal_representative}} </u></p>
        </div>
    </div>


<!-- ACTIVIDAD FORMATIVA -->

    <h1>
        2. ACTIVIDAD FORMATIVA
    </h1>

    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h3>2.A. Formación acreditable</h3>
            <p>(La actividad deberá contener como mínimo un Módulo Formativo completo) </p>
            <table style="width: 95%">
                <thead>
                    <tr>
                        <th colspan="7">Título FP/CP/Módulo profesionales/Módulos formativos/Unidades formativas (todos “completos”)</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Denominación</th>
                        <th>N.º Horas</th>
                        <th>Modalidad (Presencial,Teleformación) </th>
                        <th>Código de Centro educativo
                            autorizado/ Código del Centro
                            acreditado en Registro Estatal
                        </th>
                        <th>Grado título
                            Nivel CP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mx-2 my-2">
            <h3>2.B. Especialidades formativas</h3>
            <table style="width: 95%" id="tablaEspecialidades">
                <thead>
                    <tr>
                        <th colspan="6">Especialidades formativas</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Denominación</th>
                        <th>N.º Horas</th>
                        <th>Modalidad (Presencial,Teleformación) </th>
                        <th>Código de Centro
                            inscrito en Registro
                            Estatal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($elements as $e)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$e->training_action->code}}</td>
                            <td>{{$e->training_action->name}}</td>
                            <td>{{$e->training_action->total_hours}}</td>
                            <td>TELEFORMACIÓN</td>
                            <td>{{$e->training_action->webPlatform->code}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="page-break-after: always;"></div>



    <table>
        <tr>
            <td style="border:none; text-align:center">
                <img src="img-acuerdo/Logo V&R grande transparente.png" alt="logo" class="img-fluid fixed-height-img-logo">
            </td>
        </tr>
    </table>


    <h1>
        3. DISTRIBUCIÓN DE LA ACTIVIDAD FORMATIVA Y DE LA ACTIVIDAD LABORAL MÁXIMA PREVISTA
    </h1>
    <div class="row">
        <div class="mx-2 my-2">
            <table style="width: 80%;margin: 0 auto;">
                <thead>
                    <tr>
                        <th colspan="3">N.º DE HORAS ANUALES DE ACTIVIDAD FORMATIVA</th>
                    </tr>
                    <tr>
                        <th>AÑOS</th>
                        <th>MÁXIMO</th>
                        <th>HORAS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1º</td>
                        <td>{{$trainingContract->percentage_first_year}}%</td>
                        <td>{{$trainingContract->formative_hours_first_year}}</td>
                    </tr>
                    <tr>
                        <td>2º</td>
                        <td>{{$trainingContract->percentage_second_year}}%</td>
                        <td>{{$trainingContract->formative_hours_second_year}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mx-2 my-2">
            <table style="width: 80%;margin: 0 auto;">
                <thead>
                    <tr>
                        <th colspan="3">N.º DE HORAS ANUALES DE ACTIVIDAD LABORAL</th>
                    </tr>
                    <tr>
                        <th>AÑOS</th>
                        <th>MÁXIMO</th>
                        <th>HORAS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1º</td>
                        <td>{{100-$trainingContract->percentage_first_year}}%</td>
                        <td>{{$trainingContract->annually_day_hours-$trainingContract->formative_hours_first_year}}</td>
                    </tr>
                    <tr>
                        <td>2º</td>
                        <td>{{100-$trainingContract->percentage_second_year}}%</td>
                        <td>------------------------</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-12 mx-2 my-2">
            <p>
                La actividad formativa se desarrollará de acuerdo con la secuenciación y calendarización que se detallan en la planificación
                formativa que se acompaña al contrato y/o cada una de sus prórrogas
            </p>
            <p class="no-margin-bottom">
                Criterios para la conciliación de las vacaciones a las que tiene derecho la persona trabajadora en la empresa y de los
                períodos no lectivos en el centro de formación:
            </p>
            <p>DURANTE EL DISFRUTE DE LAS VACACIONES LEGALMENTE ESTABLECIDAS NO SE ORGANIZARÁ NINGUNA ACTIVIDAD DE
                FORMACIÓN
            </p>
            <p class="no-margin-bottom">
                Criterio para el establecimiento de la jornada y horario en el centro y en la empresa:
            </p>
            <p>
                LA JORNADA Y EL HORARIO SE DESARROLLARÁN SEGÚN SE DETALLA EN EL CONTRATO LABORAL Y EN LA PLANIFICACIÓN
                DE LA ACTIVIDAD FORMATIVA.
            </p>
        </div>
    </div>


<!-- ITINERARIO FORMATIVO INDIVIDUAL -->

<div style="page-break-after: always;"></div>


    <div class="col-md-12 d-flex justify-content-center">
        <img src="img-acuerdo/Logo V&R grande transparente.png" alt="logo" class="img-fluid fixed-height-img-logo">
    </div>
    <h1>
        4. ITINERARIO FORMATIVO INDIVIDUAL
    </h1>
    <h3>
        4.1. DATOS CENTROS Y ACTIVIDAD FORMATIVA
    </h3>
    <div class="row">
            <?php
            
            $contenedor = '';
        
            foreach ($elements as $e) {
                $titulo_representante = ($company->company_type_id == "Autónomo") ? "TITULAR" : "ADMINISTRADOR/A";
                $bloqueHTML = '
                    <div class="col-md-12 mx-2 my-2">
                        <h3>DATOS CENTRO/S IMPARTIDORES DE LA ACTIVIDAD FORMATIVA</h3>
                        <p>
                            Formación a impartir: Código <u>' . $e->training_action->code .'</u>  Denominación: <u> '. $e->training_action->name .' </u> 
                        </p>
                        <p>
                            Centro Acreditado/Inscrito.Código de centro en Registro Estatal de centros de Formación <u>' .  $e->training_action->webPlatform->code   .'</u>
                        </p>
                        <p>
                            Nombre Centro: <u>'. $company->name . '</u>  CIF/NIF/NIE <u>'. $company->nif. '</u>
                        </p>
                        <p>
                            URL <u>'. $e->training_action->webPlatform->url.'</u>
                        </p>
                        <p>
                            Dirección: <u>'.$company->address.'</u> CP: <u> '.$company->post_code.'</u> Municipio: <u>'.$company->population.' </u>
                        </p>
                        <p>
                            Provincia: <u>'.$province->name.'</u> Teléfono: <u>'.$company->telephone.'</u> Correo electrónico: <u>'.$company->email.'</u>
                        </p>
                        <p>
                            D./Dña.: <u>'.$company->legal_representative. '</u> en concepto de <u>'. $titulo_representante.'</u> NIF/NIE: <u>'.$company->dni_legal_representative.' </u>
                        </p>
                    </div>
                ';
                $contenedor .= $bloqueHTML;
            }
            echo $contenedor;
            ?>
    </div>

    <div style="page-break-after: always;"></div>

    <table>
        <tr>
            <td style="border:none; text-align:center">
                <img src="img-acuerdo/Logo V&R grande transparente.png" alt="logo" class="img-fluid fixed-height-img-logo">
            </td>
        </tr>
    </table>
        <p>
            Se medirán aquellos objetivos observables que se correspondan con las diferentes tareas que componen la actividad
            laboral, de acuerdo con las realizaciones profesionales y según los criterios de realización estandarizados de la empresa.
            Asociado a la consecución de cada objetivo, se medirán las realizaciones profesionales de las actividades más significativas
            del puesto de trabajo a desempeñar.
        </p>
        <h3>
            4.2. MECANISMOS DE COORDINACIÓN
        </h3>
        <div class="row">
            <div class="col-md-12 mx-2 my-2">
                <p>La persona que ejerza la tutoría en la empresa será responsable del seguimiento del convenio de colaboración para la
                    actividad formativa anexo al contrato, de la coordinación de la actividad laboral con la actividad formativa, y de la
                    comunicación con el centro de formación; además, deberá elaborar, al finalizar la actividad laboral de la persona
                    trabajadora, un informe sobre el desempeño del puesto de trabajo.
                </p>
                <p>
                    El centro formativo designará una persona, profesora o formadora, como tutora responsable de la programación y
                    seguimiento de la formación, a través de la Jefatura de Estudios, así como de la coordinación de la evaluación con los
                    Profesores y/o tutores que intervienen. Asimismo, esta persona será la interlocutora con la empresa para el desarrollo de
                    la actividad formativa y laboral establecida en el convenio.
                </p>
            </div>
        </div>

        <h3>
            4.3. MECANISMOS DE TUTORÍA Y SUPERVISIÓN
        </h3>
        <div class="row">
            <div class="col-md-12 mx-2 my-2">
                <p>El seguimiento formativo por parte de la persona tutora/formadora del centro de formación se desarrolla a través de la
                    mensajería de la plataforma de teleformación (entrega de claves, mensajes de bienvenida, retroalimentación de las tareas
                    y actividades, avisos en caso de no conexión y/o realización de la actividad formativa en los plazos determinados), llamadas
                    telefónicas y correo electrónico.
                </p>
                <p>
                    Por su parte, el tutor de la empresa realizará un seguimiento atendiendo a las realizaciones profesionales que se desarrollen
                    en el puesto de trabajo que irá destinado a recoger evidencias, de un lado, de los resultados de aprendizaje en orden a la
                    adquisición de los conocimientos, capacidades cognitivas y prácticas, y, de otro lado, en referencia al desarrollo de las
                    habilidades de gestión, personales y sociales.
                </p>
                <p>Se establece un mecanismo permanente de comunicación y seguimiento entre el centro de formación y la empresa,
                    mediante el cual la persona tutora-formadora mantendrá informada a la persona tutora de la empresa sobre la evolución
                    del alumno. La persona tutora-formadora del centro de formación facilitará a la tutoría de empresa el informe para la
                    evaluación del desempeño de la actividad práctica.
                </p>
            </div>
        </div>

        <h3>
            4.4. SISTEMAS DE EVALUACIÓN DE LA ACTIVIDAD LABORAL DESARROLLADA
        </h3>
        <div class="row">
            <div class="col-md-12 mx-2 my-2">
                <p class="no-margin-bottom">La evaluación se realizará de forma sistemática y continua, durante todo el desarrollo del proceso de aprendizaje
                    correspondiente, estará orientada a conseguir evidencias de realizaciones o resultados profesionales, y tendrá por objeto
                    conocer la competencia profesional adquirida.
                </p>
                <p>
                    La actividad laboral desempeñada se valorará a la finalización de cada función, tarea o bloque de conocimiento necesarios
                    para el desarrollo integral del puesto de trabajo mediante un Informe sobre el desempeño del puesto de trabajo de los
                    resultados de aprendizaje alcanzados en la empresa. La evaluación se expresará a través de los siguientes indicadores:
                </p>
                <p class="no-margin-bottom">1. El alumno-trabajador no sabe hacerlo.</p>
                <p class="no-margin-bottom" >2. El alumno-trabajador lo puede hacer con ayuda.</p>
                <p class="no-margin-bottom">3. El alumno-trabajador lo puede hacer sin necesitar ayuda.</p>
                <p>4. El alumno-trabajador lo puede hacer sin necesitar ayuda, e incluso podría formar a otro trabajador o trabajadora.
                    En el caso de que no proceda evaluar algún indicador de logro, se dejará en blanco.</p>
            </div>
        </div>

        <div style="page-break-after: always;"></div>


        <table>
            <tr>
                <td style="border:none; text-align:center">
                    <img src="img-acuerdo/Logo V&R grande transparente.png" alt="logo" class="img-fluid fixed-height-img-logo">
                </td>
            </tr>
        </table>
        
        <h1>
            5. DATOS DECLARATIVOS Y FORMALIZACIÓN DEL ACUERDO
        </h1>
        <h3>
            Declaro que:
        </h3>
        <div class="col-md-12 mx-2 my-2">
            <ul>
                <li>El centro de trabajo se encuentra en: <u>{{$company->address}}  ({{$company->post_code}} {{$company->population}})</u></li>
                <li>Son ciertos los datos que se consignan en el presente acuerdo, asumiendo en caso contrario las responsabilidades que pudieran derivarse de su inexactitud.</li>
                <li>Conozco lo establecido en el artículo 11.2 del Estatuto de los Trabajadores y el Real Decreto 1.529/2012, de 8 de noviembre y demás normativas de desarrollo, así como la normativa que afecta a la actividad formativa objeto de esta solicitud.</li>
                <li>Que autorizo/a al Servicio Público de Empleo de la Comunidad Autónoma y al Servicio Público de Empleo Estatal a que acceda a las bases de datos de la Administración General del Estado y de las Administraciones de las Comunidades Autónomas, con garantía de confidencialidad y a los exclusivos efectos de facilitar la verificación de los datos consignados en esta solicitud, manifestando que quedo enterado de la obligación de informar a los Servicios Públicos de Empleo de cualquier variación de los mismos que pudiera producirse.</li>
                <li>A efectos de lo establecido en el art. 6 del R.D. 1529/2012, de 8 de noviembre, la persona trabajadora objeto del contrato pertenece a alguno de los colectivos siguientes:</li>
            </ul>
            <ul style="list-style-type: none;">
                <li>
                    <label for="opcion6">
                    <input type="checkbox" id="opcion6" name="opcion6" {{ $trainingContract->disabled == 1 ? 'checked' : '' }}>
                    </label>
                    Personas con discapacidad
                </li>
                <li>
                    <label for="opcion7">
                        <input type="checkbox" id="opcion7" name="opcion7" {{ $trainingContract->youth_guarantee == 1 ? 'checked' : '' }}>
                    </label>
                        Inscrito en el Sistema Nacional de Garantía
                </li>
                <li>
                    <label for="opcion7">
                        <input type="checkbox" id="opcion7" name="opcion7" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}>
                    </label>
                    Colectivos en situación de exclusión social y que la empresa contratante es una empresa de inserción
                </li>
            </ul>
        </div>
        <h3>
            El/los centros formativos declaran:
        </h3>

        <div class="col-md-12 mx-2 my-2">
            <ul>
                <li>Que la persona trabajadora, reúne alguno de los requisitos de acceso a la formación según lo establecido en el
                    art. 20 del R. D. 34/2008 de 18 de enero, y/o en la normativa del Sistema Educativo.
                </li>
                <li>Que autoriza al Servicio Público de Empleo de la Comunidad Autónoma y al Servicio Público de Empleo Estatal a
                    que acceda a las bases de datos de la Administración General del Estado y de las Administraciones de las
                    Comunidades Autónomas, con garantía de confidencialidad y a los exclusivos efectos de facilitar la verificación de
                    los datos consignados en esta solicitud, manifestando que quedo enterado de la obligación de informar a los
                    Servicios Públicos de Empleo de cualquier variación de los mismos que pudiera producirse.
                </li>
            </ul>
            <p>
                Y para que conste, se extiende el presente acuerdo para la actividad formativa en el lugar y fecha a
                continuación indicados, firmando las partes.
            </p>
            @php
                $nombre_mes = now()->translatedFormat('F');
                $dia = now()->format('d');
                $anio = now()->format('Y');
            @endphp
            
            <p>
                En <u>{{$company->population}}</u>  a <u>{{ $dia }}</u> de <u>{{ $nombre_mes }}</u> de 2024
            </p>
            <div style="margin: 20px;">
                <table width="100%" style="margin=10px;">
                    <tr>
                        <td width="25%" style="border: none;">
                            El/la trabajador/a
                        </td>
                        <td width="25%" style="border: none;">
                            El/la representante legal del/de la menor, si procede
                        </td>
                        <td width="25%" style="border: none;">
                            El/la representante de la empresa
                        </td>
                        <td width="25%" style="border: none;">
                            El/los representante/s de los centro/s de formación
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" style="border: none; padding-top: 130px;">
                            <u>{{$student->name}} {{$student->surname}}</u>
                        </td>
                        <td width="25%" style="border: none; padding-top: 130px;">
                            <u>{{$student->legal_guardian_name}}</u>
                        </td>
                        <td width="25%" style="border: none; padding-top: 130px;">
                            <u>{{$company->legal_representative}}</u>
                        </td>
                        <td width="25%" style="border: none; padding-top: 130px;">
                            <u>{{$trainingContract->company_tutor}}</u>
                        </td>
                    </tr>
                </table>
            </div>
            

            <p class="no-margin-bottom">
                Si hay más de un centro de formación, cada uno deberá suscribir este acuerdo.
            </p>
            <p>
                Todas las páginas de este acuerdo deberán ir firmadas en el margen izquierdo para mayor seguridad jurídica.
            </p>
        </div>


    <!-- PROTECCIÓN DE DATOS -->

        <h1>
            6. PROTECCIÓN DE DATOS
        </h1>
        <div class="col-md-12 mx-2 my-2">
            <p>MV & JAR Consultores, S.L. es el Responsable del tratamiento de los datos personales proporcionados y le informa que
                estos datos serán tratados de conformidad con lo dispuesto en el Reglamento (UE) 2016/679 de 27 de abril de 2016 (GDPR),
                con la finalidad de mantener una relación de servicios de formación y conservarlos mientras exista un interés mutuo para
                mantener el fin del tratamiento y cuando ya no sea necesario para tal fin, se suprimirán con medidas de seguridad
                adecuadas para garantizar la seudonimización de los datos o la destrucción total de los mismos. Los datos podrán ser
                comunicados a terceros para la prestación del servicio o por obligación legal. Asimismo, se informa que puede ejercer los
                derechos de acceso, rectificación, portabilidad, supresión, limitación y oposición dirigiéndose a MV & JAR Consultores, S.L.
                en C/ Real Fernando, local 4, - 11540 Sanlúcar de Barrameda (Cádiz). E-mail: info@vrconsultores.es y el de reclamación a
                www.agpd.es.
            </p>
        </div>
</body>
</html>
