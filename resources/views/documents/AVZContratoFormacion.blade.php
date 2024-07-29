<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato de Formación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/contrato.css">
</head>
<body>
<!-- PRIMERA PÁGINA -->
    <table>
        <tr>
            <td width="55%"  style="border: none; text-align:left;">
                <img src="AVZ/mtes.PNG" alt="mtes" class="img-fluid fixed-height-img-mtes">
            </td>
            <td width="45%"  style="border: none;">
                <div class="col-md-12">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe">
                </div>
                <div class="col-md-12">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue">
                </div>
            </td>
        </tr>
    </table>

    <h1>CONTRATO DE TRABAJO DE FORMACIÓN EN ALTERNACIA</h1>

    <h1>DATOS DE LA EMPRESA</h1>
    <table>
        <tr>
            <td width="15%" class="left-align">
                <p class="no-margin-bottom"> CIF/NIF/NIE</p>
                <p class="empty-paragraph">{{$trainingContract->company->nif}}</p>
            </td>
            <td width="85%" style="border: none;"></td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td width="50%;" class="left-align">
                <p class="no-margin-bottom"> D./DÑA.</p>
                <p class="empty-paragraph">{{$trainingContract->company->legal_representative}}</p>
            </td>
            <td width="10%;" class="left-align">
                <p class="no-margin-bottom"> NIF/NIE</p>
                <p class="empty-paragraph">{{$trainingContract->company->dni_legal_representative}}</p>
            </td>
            <td width="40%;" class="left-align">
                <p class="no-margin-bottom"> EN CONCEPTO (1)</p>
                <p class="empty-paragraph">
                    @if($trainingContract->company->company_type_id=="Autónomo")
                        TITULAR
                    @else
                        ADMINISTRADOR/A
                    @endif
                </p>
            </td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td width="50%" class="left-align">
                <p class="no-margin-bottom"> NOMBRE O RAZÓN SOCIAL DE LA EMPRESA</p>
                <p class="empty-paragraph">{{$trainingContract->company->name}}</p>
            </td>
            <td width="50%" class="left-align">
                <p class="no-margin-bottom"> DOMICILIO SOCIAL</p>
                <p class="empty-paragraph">{{$trainingContract->company->address}}</p>
            </td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td width="40%" class="left-align">
                <p class="no-margin-bottom"> PAIS</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">ESPAÑA</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split(str_replace(' ', '', 724)) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
                <div style="clear: both;"></div> {{--  se utiliza a menudo para prevenir que los elementos flotantes se desborden fuera de su contenedor --}}
            </td>
            <td width="47%" class="left-align">
                <p class="no-margin-bottom"> MUNICIPIO</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">{{$trainingContract->company->population}}</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split($trainingContract->company->population_code) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
            </td>
            <td width="13%" class="left-align">
                <p class="no-margin-bottom"> C.POSTAL</p>
                <p class="empty-paragraph">
                    @foreach(str_split(str_replace(' ', '', $trainingContract->company->post_code)) as $numero)
                        <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                    @endforeach
                </p>
            </td>
        </tr>
    </table>


    <h1>DATOS DE LA CUENTA DE COTIZACIÓN</h1>
    <table>
        <tr>
            <td width="13%" class="left-align">
                <p class="no-margin-bottom"> RÉGIMEN</p>
                <p class="empty-paragraph">
                    @if(!empty($trainingContract->company->regimen))
                        @foreach(str_split(str_replace(' ', '', $trainingContract->company->regimen)) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    @else
                        @foreach(range(0, 3) as $i)
                            <span style="border: 1px solid black; padding: 2px 8px; margin: -2px;">&nbsp;</span>
                        @endforeach
                    @endif
                </p>
            </td>
            <td width="27%" class="left-align">
                <p class="no-margin-bottom"> CÓDIGO CUENTA COTIZACIÓN</p>
                <p class="empty-paragraph">
                    @foreach(str_split(str_replace(' ', '', $trainingContract->company->quote)) as $numero)
                        <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                    @endforeach
                </p>
            </td>
            <td width="60%" class="left-align">
                <p class="no-margin-bottom"> ACTIVIDAD ECONÓMICA</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 90%;">
                        {{ substr($trainingContract->company->cnae->name ?? '', 6) }}
                    </span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split(substr($trainingContract->company->cnae->name ?? '', 0, 2)) as $char)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $char }}</span>
                        @endforeach
                    </span>
                </p>
                <div style="clear: both;"></div>
            </td>
        </tr>
    </table>

    <h1>DATOS DEL CENTRO DE TRABAJO</h1>
    <table>
        <tr>
            <td width="40%" class="left-align">
                <p class="no-margin-bottom"> PAIS</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">ESPAÑA</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split(str_replace(' ', '', 724)) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
                <div style="clear: both;"></div> {{--  se utiliza a menudo para prevenir que los elementos flotantes se desborden fuera de su contenedor --}}
            </td>
            <td width="60%" class="left-align">
                <p class="no-margin-bottom"> MUNICIPIO</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">{{$trainingContract->company->population}}</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split($trainingContract->company->population_code) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
            </td>
        </tr>
    </table>

    <h1 >DATOS DEL/DE LA TRABAJADORA</h1>
    <table>
        <tr>
            <td width="40%" class="left-align">
                <p class="no-margin-bottom"> D./DÑA.</p>
                <p class="empty-paragraph">{{$trainingContract->student->name}} {{$trainingContract->student->surname}}</p>
            </td>
            <td width="10%" class="left-align">
                <p class="no-margin-bottom"> NIF/NIE</p>
                <p class="empty-paragraph">{{$trainingContract->student->dni}}</p>
            </td>
            <td width="20%" class="left-align">
                <p class="no-margin-bottom"> FECHA NACIMIENTO</p>
                <p class="empty-paragraph">{{ \Carbon\Carbon::parse($trainingContract->student->date_of_birth)->format('d/m/Y') }}</p>
            </td>
            <td width="30%" class="left-align">
                <p class="no-margin-bottom"> Nº AFILIACIÓN SEGURIDAD SOCIAL</p>
                <p class="empty-paragraph">
                    @foreach(str_split($trainingContract->student->social_security_number) as $number)
                        @if(!empty($number) || $number === '0')
                            <span style="border: 1px solid black; padding: 1px 4px; margin: -2px;">{{ $number }}</span>
                        @endif
                    @endforeach
                </p>
            </td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td width="70%" class="left-align">
                <p class="no-margin-bottom"> NIVEL FORMATIVO</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">{{$trainingContract->student->levelStudy->name}}</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(range(0,1) as $i)
                            <span style="border: 1px solid black; padding: 2px 8px; margin: -2px;">&nbsp;</span>
                        @endforeach
                    </span>
                </p>
                <div style="clear: both;"></div> {{--  se utiliza a menudo para prevenir que los elementos flotantes se desborden fuera de su contenedor --}}
            </td>
            <td width="30%" class="left-align">
                <p class="no-margin-bottom"> NACIONALIDAD</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">{{$trainingContract->student->nationality}}</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split($trainingContract->student->nationality_code) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td width="70%" class="left-align">
                <p class="no-margin-bottom"> MUNICIPIO DEL DOMICILIO</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">{{$trainingContract->student->population}}</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split($trainingContract->student->population_code) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
            </td>
            <td width="30%" class="left-align">
                <p class="no-margin-bottom"> PAÍS DOMICILIO</p>
                <p>
                    <span class="empty-paragraph" style="float: left; width: 80%;">ESPAÑA</span>
                    <span class="empty-paragraph" style="float: right;">
                        @foreach(str_split(str_replace(' ', '', 724)) as $numero)
                            <span style="border: 1px solid black; padding: 2px 4px; margin: -2px;">{{ $numero }}</span>
                        @endforeach
                    </span>
                </p>
                <div style="clear: both;"></div> {{-- se utiliza a menudo para prevenir que los elementos flotantes se desborden fuera de su contenedor --}}
          
            </td>
        </tr>
    </table>

    <p>
        con la asistencia legal, en su caso, de D./Dña.
        <span class="dots">{{$trainingContract->student->legal_guardian_dni}}
        </span> con NIF/NIE
        <span class="dots">{{$trainingContract->student->legal_guardian_name}}
        </span>, en calidad de (2)
        <span class="dots">Padre, madre, tutor/a o persona o institución que le tenga a su cargo
        </span>
    </p>

    <h1 class="text-center m-0 p-0">DECLARAN</h1>
    <h3 class="no-line-break">PRIMERA: </h3>

    <p class="no-line-break">: este contrato tiene por objeto compatibilizar la actividad laboral retribuida con los correspondientes procesos formativos en el ámbito de la formación profesional, los estudios universitarios o el Catálogo de Especialidades Formativas del Sistema Nacional de Empleo.</p>

    <br>

    <h3 class="no-line-break">SEGUNDA: </h3>
    <p class="no-line-break">que el/la trabajador/a es: </p>
    <br>

        
    
    @php
        // Calcular la edad del estudiante
        $birthDate = new DateTime($trainingContract->Student->date_of_birth);
        $today = new DateTime();
        $interval = $today->diff($birthDate);
        $age = $interval->y;

        // Determinar si el checkbox debe estar marcado
        $isChecked = $age >= 16 && $age <= 30 ? 'checked' : '';
    @endphp

    <input class="no-line-break" type="radio" id="opcion1" name="trabajador" value="mayor_16_30" {{ $isChecked }}>
    <p class="no-line-break">Mayor de 16 hasta 30 años inclusive.</p>
    <br>

    <input class="no-line-break" type="radio" id="opcion2" name="trabajador2" value="estudios_universitarios" {{$trainingContract->professional_certificate == 1 ? 'checked' : ''}}>
    <p class="no-line-break">Trabajador/a contratado/a en el marco de estudios universitarios, formación profesional o certificados de profesionalidad nivel 3. (3)</p>
    <br>

    <input class="no-line-break" type="radio" id="opcion3" name="trabajador3" value="discapacidad" {{ $trainingContract->disabled == 1 ? 'checked' : '' }}>
    <p class="no-line-break">Trabajador/a con discapacidad(3) (4).</p>
    <br>
        

    <input class="no-line-break" type="radio" id="opcion4" name="trabajador4" value="alumnos">
    <p class="no-line-break">Alumnos/as participantes en un programa público de empleo y formación al amparo de lo previsto en el artículo 13.3.b) de la Ley 3/2023, de 28 de febrero (3).</p>
    <br>

    <input class="no-line-break" type="radio" id="opcion5" name="trabajador5" value="exclusion_social" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}>
    <p class="no-line-break">Trabajador/a en situación de exclusión social, y el contrato se realiza en una empresa de inserción (3).</p>
    <br>

    <input class="no-line-break" type="radio" id="opcion6" name="trabajador6" value="capacidad_limite">
    <p class="no-line-break">Trabajador/a con capacidad intelectual límite (5).</p>
    <br>

    <h3 class="no-line-break">TERCERA: </h3>
    <p class="no-line-break">que el/la trabajador/a carece de cualificación profesional reconocida por las titulaciones o certificados requeridos para concertar un contrato
    formativo para la obtención de la práctica profesional.
    </p>
    
    <hr>
    <ul class="no-bullets small-text">
        <li>(1) Director/a, Gerente, etc.</li>
        <li>(2) Padre, madre, tutor/a o persona o institución que le tenga a su cargo.</li>
        <li>(3) Sin límite de edad.</li>
        <li>(4) Se aportará la certificación que acredite al trabajador la condición de persona con discapacidad expedido por el Organismo Oficial correspondiente.</li>
        <li>(5) Se aportará la certificación que acredite al trabajador la condición de persona con capacidad intelectual límite expedido por el Organismo Oficial correspondiente.</li>
    </ul>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td style="border: none; text-align: center; margin: 0; padding: 0;">
                    @php
                        $page=1;
                        echo $page;
                    @endphp
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>


    <!-- SEGUNDA PAGINA -->
    <div class="row">
        <div class="col-md-8">
            <img src="AVZ/mtes.PNG" alt="mtes" class="img-fluid fixed-height-img-mtes2">
        </div>
    </div>
    <h3 class="no-line-break">CUARTA: </h3>
    <p class="no-line-break">que si el contrato está vinculado a estudios de formación profesional o universitarios, el trabajador no ha tenido otro contrato formativo previo en una formación del mismo nivel formativo y del mismo sector productivo.</p>
    <br>

    <h3 class="no-line-break">QUINTA: </h3>
    <p class="no-line-break">que el/la trabajador/a no a desempeñado el puesto de trabajo correspondiente a este contrato en esta empresa por un tiempo superior a 6 meses.</p>
    <br>

    <h3 class="no-line-break">SEXTA: </h3>
    <p class="no-line-break">que se reúnen los requisitos exigidos para la celebración del presente contrato y en consecuencia acuerdan formalizarlo con arreglo a las presentes:</p>
    <br>

    <h1 class="text-center">CLAUSULAS</h1>

    <h3 class="no-line-break">PRIMERA: </h3>

    <p class="no-line-break">el contrato tiene por objeto la cualificación profesional en régimen de alternancia de:</p>
    <br>

    <ul class="no-bullets">
        <li>a) Actividad laboral (6) <span class="dots">{{$trainingContract->occupation->name}}</span>
                CNO: <span class="dots">{{(substr($trainingContract->occupation->cno, 0, 4))}} </span> incluido en el grupo profesional de (7) <span class="dots"> aprendices</span>	, de acuerdo con el sistema de calificación vigente en la empresa. En el centro de trabajo ubicado en (calle, número y localidad) <span class="dots"> {{$trainingContract->company->address}} ({{$trainingContract->company->post_code}} {{$trainingContract->company->population}}) </span> Siendo el/la tutor/a designado por la entidad de formación D/Dña (8). <span class="dots">  {{$trainingContract->training_tutor}} </span> , cuya cualificación profesional es (9) <span class="dots"> Tutor Experto </span> Siendo el/la tutor/a designado por la empresa D/Dña. <span class="dots">{{$trainingContract->company_tutor}} </span></li>
        <li>b) La actividad formativa vinculada al contrato es <span class="dots">{{$trainingContract->occupation->name}}	</span>, de acuerdo con el convenio de colaboración suscrito por la empresa con el centro o entidad formativa y que se incorpora como anexo en este contrato (10).</li>
    </ul>

    <h3 class="no-line-break">SEGUNDA: </h3>
    <p class="no-line-break">la jornada total será de (11) <span class="dots">{{$trainingContract->annually_day_hours}}</span> horas <span class="dots">anual </span>	De ellas, el número de horas dedicadas a la actividad formativa
    será de <span class="dots"> {{$trainingContract->bonus_hours_first_year}} </span> horas, que representan un <span class="dots">{{$trainingContract->percentage_first_year}} </span> por ciento de la jornada máxima prevista en el convenio colectivo de
    <span class="dots">{{$trainingContract->company->agreement ?? ''}}</span>
    <br>El tiempo efectivo de trabajo se prestará en el horario (12)<br>
    <span class="dots">

    DEL {{ \Carbon\Carbon::parse($trainingContract->beginning)->format('d/m/Y') }} AL {{ \Carbon\Carbon::parse($trainingContract->end)->format('d/m/Y') }} {{$trainingContract->end}}:{{$trainingContract->working_hours}}<br>
        
    </span>
    <br>
    La actividad formativa se impartirá de acuerdo al siguiente calendario:
    <span class="dots">
    <br>
    DEL {{ \Carbon\Carbon::parse($trainingContract->beginning_formation)->format('d/m/Y') }} AL {{ \Carbon\Carbon::parse($trainingContract->end_formation)->format('d/m/Y') }}:{{$trainingContract->training_schedule}}
    </span>
    <br>
    reflejado en el anexo del plan formativo individual.
    </p>
    <br>

    <input type="checkbox" id="opcion1" name="opcion1" class="no-line-break">
    <p class="no-line-break">TRABAJO A DISTANCIA, siempre que se garantice como mínimo un 50 % de prestación de servicio presencial (13).</p>

    <br>

    
    @php
        use Carbon\Carbon;
        $fechaInicio = Carbon::parse($trainingContract->beginning);
        $fechaFin = Carbon::parse($trainingContract->end)->addDay(); //para que salga el total correctamente
        $diff = $fechaFin->diff($fechaInicio);
        $duracionContrato = '';
        if ($diff->y > 0) {
            $duracionContrato .= $diff->y . ' años, ';
        }
        if ($diff->m > 0) {
            $duracionContrato .= $diff->m . ' meses, ';
        }
        if ($diff->d > 0) {
            $duracionContrato .= $diff->d . ' días';
        }
        $duracionContrato = rtrim($duracionContrato, ', '); // Remove trailing comma and space
    @endphp

    <h3 class="no-line-break">TERCERA: </h3>
    <p class="no-line-break"> la duración del presente contrato será de (14) <span class="dots"> {{$duracionContrato}}</span> y se extenderá desde <span class="dots"> {{ \Carbon\Carbon::parse($trainingContract->beginning)->format('d/m/Y') }} </span> hasta <span class="dots">{{ \Carbon\Carbon::parse($trainingContract->end)->format('d/m/Y') }}</span> </p>
    <br>

    <h3 class="no-line-break">CUARTA: </h3>
    <p class="no-line-break"> el/la trabajador/a percibirá por la prestación de sus servicios una retribución de (15) <span class="dots"> s/convenio </span>euros brutos (16) <span class="dots"> s/convenio </span> </p>
    <br>

    <h3 class="no-line-break">QUINTA: </h3>
    <p class="no-line-break"> la duración de las vacaciones anuales será (17) <span class="dots"> s/convenio </span></p>
    <br>

    <h3 class="no-line-break">SEXTA: </h3>
    <p class="no-line-break"> la empresa se obliga a proporcionar trabajo efectivo relacionado con las actividades formativas y a facilitar la asistencia a las mismas. El trabajador/a se compromete a prestar el trabajo efectivo y recibir la formación relacionada.</p>
    <br>
    <hr>

    <ul class="no-bullets small-text">
        <li>(6)	Indicar puesto de trabajo y ocupación según Clasificación Nacional de Ocupaciones vinculados a la formación. Las funciones pueden ser todas las del grupo
        profesional o solamente alguna de ellas.</li>
        <li>(7)	Señalar el grupo profesional que corresponda, según el sistema de clasificación profesional vigente en la empresa.</li>
        <li>(8)	Nombre y apellidos del tutor.</li>
        <li>(9)	Señalar el nivel profesional del tutor, según el sistema de clasificación profesional vigente en la empresa.</li>
        <li>(10) En el plan formativo individual se debe especificar el contenido de la formación, el calendario, las actividades y los requisitos de tutoría para el cumplimiento de sus
        objetivos.</li>
        <li>(11) La jornada y el total de horas de trabajo efectivo se puede expresar en horas al día, semana, mes o año, siempre que en ambos casos se utilice la misma referencia. Máximo de 65 % de la jornada prevista en el convenio, o en su defecto de la jornada máxima legal, en el primer año de contrato y el 85 % en el segundo.</li>
        <li>(12) Indicar los días de trabajo efectivo y el horario.</li>
        <li>(13) El trabajo a distancia se regula por lo dispuesto en la Ley 10/2021, de 9 de julio, y requiere la firma del correspondiente acuerdo.</li>
        <li>(14) Mínimo 3 meses, máximo 2 años. En caso de personas con discapacidad o personas con capacidad intelectual límite, máximo 4 años.</li>
        <li>(15) La fijada en convenio colectivo, sin que en su defecto pueda ser inferior al Salario Mínimo Interprofesional (SMI), en proporción al tiempo de trabajo efectivo.</li>
        <li>(16) semanales, mensuales o anuales.</li>
        <li>(17) Mínimo: 30 días naturales.</li>
    </ul>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <hr>
    <div style="page-break-after: always;"></div>
    <!-- TERCERA PÁGINA -->

    <div class="row">
        <div class="col-md-8">
            <img src="AVZ/mtes.PNG" alt="mtes" class="img-fluid fixed-height-img-mtes2">
        </div>
    </div>

    <h3 class="no-line-break">SEPTIMA: </h3>
    <p class="no-line-break"> El presente contrato dará derecho a una bonificación de 91 euros al mes durante su vigencia, incluidas las prórrogas. También dará derecho a una bonificación de 28 euros en las cuotas de la persona trabajadora en la Seguridad Social por los conceptos de recaudación conjunta (artículo
    23 del Real Decreto-ley 1/2023, de 10 de enero. En caso de personas con discapacidad, se podrá optar por aplicar la bonificación del 50 % en la cotización establecida en la disposición adicional vigésima del Estatuto de los Trabajadores.</p>
    <br>

    <h3 class="no-line-break">OCTAVA: </h3>
    <p class="no-line-break"> El presente contrato se extinguirá por la expiración del tiempo convenido, incluyendo, en su caso, las prórrogas que se puedan acordar, así
    como las demás causas previstas en el artículo 49 del Estatuto de los Trabajadores.</p>
    <br>

    <h3 class="no-line-break">NOVENA: </h3>
    <p class="no-line-break"> En lo no previsto en este contrato, se estará a la legislación vigente que resulte de aplicación y particularmente a lo dispuesto en el artículo 11 del Estatuto de los Trabajadores. Asimismo le será de aplicación lo dispuesto en el Convenio Colectivo de
    <span class="dots"> {{ $trainingContract->applicableAgreement ? $$trainingContract->applicableAgreement->name : '' }}  {{ $trainingContract->applicableAgreement ? ($trainingContract->applicableAgreement->agreementType->type) : '' }} </span></p>
    <br>

    <h3 class="no-line-break">DECIMA: </h3>
    <p class="no-line-break"> El contenido del presente contrato se comunicará al Servicio Público de Empleo de <span class="dots">CONTRAT@</span> en el plazo de los 10 días hábiles siguientes a su concertación. El/la empresario/a comunicará el fin de la relación laboral al Servicio Público de Empleo de <span class="dots">CONTRAT@</span> en el plazo de los 10 días hábiles siguientes a su terminación.</p>
    <br>

    <h3 class="no-line-break">UNDECIMA: </h3>
    <p class="no-line-break"> ESTE CONTRATO PODRÁ SER COFINANCIADO POR EL FONDO SOCIAL EUROPEO.</p>
    <br>

    <h3 class="no-line-break">DUODECIMA: </h3>
    <p class="no-line-break"> PROTECCIÓN DE DATOS. - De conformidad con el Reglamento UE 2016/679 relativo a la Protección de las Personas Físicas en lo que Respecta al Tratamiento de Datos Personales y con la L.O. 3/2018 de Protección de Datos Personales y Garantía de Derechos Digitales ; le informamos que los datos de contacto utilizados para la presente comunicación están incluidos en un fichero titularidad de AVZ FORMACIÓN SL y AVANZA ASESORAMIENTO GLOBAL; con la finalidad de posibilitar las comunicaciones a través de correo electrónico que ésta mantiene dentro del ejercicio de su actividad (como clientes, proveedores o personal). La causa que legitima este tratamiento de datos es el consentimiento. Los datos podrán ser transmitidos a la entidad que presta el servicio de asesoramiento laboral, fiscal y contable y en su caso a la entidad de almacenamiento web.
    </p>
    <br>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    <td width="4%" style="border: none; text-align:center;">
                        @php
                            $page++;
                            echo $page;
                        @endphp
                    </td>
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    

    <!-- CUARTA PÁGINA -->
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>

    <div class="mt-4 borde-redondeado">
        <p> Que el CONTRATO DE FORMACIÓN EN ALTERNANCIA que se celebra (marque la casilla que corresponda) se realiza con las siguientes cláusulas
        específicas:</p>
        @php
            // Verificar si los demás checkboxes están desmarcados
            $areOthersUnchecked = $trainingContract->youth_guarantee != 1 && $trainingContract->social_exclusion != 1 && $trainingContract->disabled != 1;

            // Si los demás checkboxes están desmarcados, marcar este checkbox
            $isChecked = $areOthersUnchecked ? 'checked' : '';
        @endphp

        <input type="checkbox" id="opcion1" name="opcion1" class="no-line-break" {{ $isChecked }}>
        <p class="no-line-break">PARA LA FORMACIÓN EN ALTERNANCIA ORDINARIO	. . . . .  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . pág. 5  </p>
        <br>
        <input type="checkbox" id="opcion2" name="opcion2" class="no-line-break"  {{ $trainingContract->social_exclusion == 1 ? 'checked' : '' }}>
        <p class="no-line-break">PARA PERSONAS TRABAJADORAS EN SITUACIÓN DE EXCLUSIÓN SOCIAL EN EMPRESAS DE INSERCIÓN . . . . . . . . . . . . . . . pág. 6  </p>
        <br>
        <input type="checkbox" id="opcion3" name="opcion3" class="no-line-break"  {{ $trainingContract->disabled == 1 ? 'checked' : '' }}>
        <p class="no-line-break">PARA PERSONAS CON DISCAPACIDAD EN CENTROS ESPECIALES DE EMPLEO . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . pág. 7  </p>
        <br>
        <input type="checkbox" id="opcion4" name="opcion4" class="no-line-break">
        <p class="no-line-break">PARA PERSONAS MAYORES DE 52 AÑOS BENEFICIARIAS DE LOS SUBSIDIOS POR DESEMPLEO . . . . . . . . . . . . . . . . . . . . . . . . pág. 8 </p>
        <br>
        <input type="checkbox" id="opcion5" name="opcion5" class="no-line-break">
        <p class="no-line-break">PARA PERSONAS PARTICIPANTES EN EL PROGRAMA FOMENTO DEL EMPLEO AGRARIO . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . pág. 9 </p>
        <br>
        <p> y cumple los requisitos exigidos en la norma regulatoria.
        </div>
                
    </div>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    <!-- QUINTA PÁGINA -->
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>

   <!-- TEMPORAL: Marcado con bonificación de cuotas a la seguridad social -->
<!-- TEMPORAL: Marcado con bonificación de cuotas a la seguridad social -->
<div class="mt-4 borde-redondeado">
    <input type="checkbox" id="formacion1" name="formacion1" class="no-line-break" checked>
    <h1 class="no-line-break">PARA LA FORMACIÓN EN ALTERNANCIA ORDINARIO</h1>

    <div class="mt-2">
        <table style="width: 100%;">
            <tr>
                <td style="width: 73%; border: none; vertical-align: middle;" class="left-align">
                    <input type="radio" id="opcion1" name="radio" class="no-line-break" 
                           @if(!$trainingContract->bonification) checked @endif>
                    <p class="no-line-break">SIN BONIFICACIÓN DE CUOTAS A LA SEGURIDAD SOCIAL</p>
                    <hr style="margin: 0; padding-right: 0;">
                </td>
                <td style="width: 27%; border: none;">
                    <table style="width: 100%; border: none; margin: 0; padding: 0;">
                        <tr>
                            <td style="border: none; text-align: right; padding: 0;">
                                <p style="margin: 0;">CÓDIGO CONTRATO</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 0;">
                                <div class="border-div">
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo1" name="tiempo" class="no-line-break"
                                               @if(!$trainingContract->bonification) checked @endif>
                                        TIEMPO COMPLETO <span class="dots" style="float: right; margin-right: 10px;">421</span>
                                    </p>
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo2" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL <span class="dots" style="float: right; margin-right: 10px;">521</span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="mt-2">
        <table style="width: 100%;">
            <tr>
                <td style="width: 73%; border: none; vertical-align: middle;" class="left-align">
                    <input type="radio" id="opcion2" name="radio" class="no-line-break" 
                           @if($trainingContract->bonification && !$trainingContract->disabled) checked @endif>
                    <p class="no-line-break">CON BONIFICACIÓN DE CUOTAS A LA SEGURIDAD SOCIAL (1)</p>
                    <hr style="margin: 0; padding-right: 0;">
                </td>
                <td style="width: 27%; border: none;">
                    <table style="width: 100%; border: none; margin: 0; padding: 0;">
                        <tr>
                            <td style="border: none; text-align: right; padding: 0;">
                                <p style="margin: 0;">CÓDIGO CONTRATO</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 0;">
                                <div class="border-div">
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo3" name="tiempo" class="no-line-break"
                                               @if($trainingContract->bonification && !$trainingContract->disabled) checked @endif>
                                        TIEMPO COMPLETO <span class="dots" style="float: right; margin-right: 10px;">450</span>
                                    </p>
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo4" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL <span class="dots" style="float: right; margin-right: 10px;">550</span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-2">
        <table style="width: 100%;">
            <tr>
                <td style="width: 73%; border: none; vertical-align: middle;" class="left-align">
                    <input type="radio" id="opcion3" name="radio" class="no-line-break" 
                           @if($trainingContract->bonification && $trainingContract->disabled) checked @endif>
                    <p class="no-line-break">CON BONIFICACIÓN DE CUOTAS A LA SEGURIDAD SOCIAL PARA PERSONAS CON DISCAPACIDAD (2)</p>
                    <hr style="margin: 0; padding-right: 0;">
                </td>
                <td style="width: 27%; border: none;">
                    <table style="width: 100%; border: none; margin: 0; padding: 0;">
                        <tr>
                            <td style="border: none; text-align: right; padding: 0;">
                                <p style="margin: 0;">CÓDIGO CONTRATO</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 0;">
                                <div class="border-div">
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo5" name="tiempo" class="no-line-break"
                                               @if($trainingContract->bonification && $trainingContract->disabled) checked @endif>
                                        TIEMPO COMPLETO <span class="dots" style="float: right; margin-right: 10px;">450</span>
                                    </p>
                                    <p style="text-align: left;">
                                        <input type="radio" id="tiempo6" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL <span class="dots" style="float: right; margin-right: 10px;">550</span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>



    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

    <!-- SEXTA PÁGINA -->
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>

    <div class="borde-redondeado">
        <input type="checkbox" id="formacion2" name="formacion2" class="no-line-break" {{ $trainingContract->social_exclusion == 1 ? 'checked' : '' }}>
        <h1 class="no-line-break">CLÁUSULAS ESPECÍFICAS PARA LA FORMACIÓN EN ALTERNANCIA DE PERSONAS TRABAJADORAS EN SITUACIÓN DE EXCLUSIÓN SOCIAL EN EMPRESAS DE INSERCIÓN</h1>
        <div>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 73%; border: none;"></td>
                    <td style="width: 27%; border: none;">
                        <table style="width: 100%; border: none; margin: 0; padding: 0;">
                            <tr>
                                <td style="border: none; text-align: right; padding: 0;">
                                    <p style="margin: 0;">CÓDIGO CONTRATO</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 0;">
                                    <div class="border-div">
                                        <p style="text-align: left;"><input type="radio" id="opcion1" name="tiempo" class="no-line-break">
                                        TIEMPO COMPLETO   <span class="dots" style="float: right; margin-right: 10px;">450</span></p>
                                        <p style="text-align: left;"><input type="radio" id="opcion2" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL   <span class="dots" style="float: right; margin-right: 10px;">550</span></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        
        <input type="checkbox" id="formacion3" name="formacion3" class="no-line-break">
        <p class="no-line-break">Que el/la trabajador/a está desempleado/a y se encuentra Incluido/a en alguna de las situaciones contempladas en el artículo 2.1 de la Ley 44/2007, de 13 de diciembre, que regula las empresas de inserción, y que acredita mediante certificación emitida por los Servicios Sociales competentes de (1):</p>
       
        <hr style="border-top: 1px dotted #999;">

        <input type="checkbox" id="formacion3" name="formacion3" class="no-line-break">
        <p class="no-line-break">Que pertenece al colectivo de:</p>
        <br>


        <input class="no-line-break" type="radio" id="opcionn1" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Perceptores/as de Rentas Mínimas de Inserción, o cualquier otra prestación de igual o similar naturaleza, según la denominaciónadoptada en cada Comunidad Autónoma, miembros de la unidad de convivencia beneficiarios de ella.</p>
        <br>


        <input class="no-line-break" type="radio" id="opcionn2" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Personas que no puedan acceder a las prestaciones a las que se hace referencia en el párrafo anterior, por alguna de las siguientes causas:
            <ul class="no-margin-bottom">
                <li>
                    Falta de período exigido de residencia o empadronamiento, o para la constitución de la Unidad Perceptora.
                </li>
                <li>
                    Haber agotado el período máximo de percepción legalmente establecido.
                </li>
            </ul>
        </p>

        <input class="no-line-break" type="radio" id="opcionn3" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Jóvenes mayores de dieciocho años y menores de treinta procedentes de Instituciones de Protección de Menores.</p>
        <br>

        <input class="no-line-break" type="radio" id="opcionn4" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Personas con problemas de drogodependencia u otros trastornos adictivos que se encuentren en procesos de rehabilitación o reinserción social.</p>
        <br>

        <input class="no-line-break" type="radio" id="opcionn5" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Internos/as de centros penitenciarios cuya situación penitenciaria les permita acceder a un empleo y cuya relación laboral no esté incluida en el ámbito de aplicación de la relación laboral especial regulada en el artículo 1 del Real Decreto 782/2001, de 6 de julio, así como liberados/as condicionales y ex reclusos/as.</p>
        <br>

        <input class="no-line-break" type="radio" id="opcionn6" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Menores internos incluidos en el ámbito de aplicación de la Ley Orgánica 5/2000, de 12 de enero, reguladora de la responsabilidad penal de los menores, cuya situación les permita acceder a un empleo y cuya relación laboral no esté incluida en el ámbito de aplicación de la relación laboral especial a que se refiere el artículo 53.4 del reglamento de la citada Ley, aprobado por el Real Decreto 1074/2004, de 30 de julio, así como los/as que se encuentren en situación de libertad vigilada y los/as exinternos/as.</p>
        <br>

        <input class="no-line-break" type="radio" id="opcionn7" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Personas procedentes de centros de alojamiento alternativo autorizado por las Comunidades Autónomas y las ciudades de Ceuta y Melilla.</p>
        <br>

        <input class="no-line-break" type="radio" id="opcionn8" name="trabajador1">
        <p class="no-margin-bottom no-line-break">Personas procedentes de servicios de prevención e inserción social autorizados/as por las Comunidades Autónomas y las ciudades de Ceuta y Melilla.</p>
        <br>
            
        <p class="no-margin-bottom">Señalar lo que proceda:</p>

        <table>
            <tr>
                <td width="5%" class="left-align" style="border: none;">
                    <input type="radio" id="opcionnn1" name="trabajador2">
                </td>
                <td width="95%" class="left-align" style="border: none;">
                    <p class="no-margin-bottom">Personas procedentes de centros de alojamiento alternativo autorizado por las Comunidades Autónomas y las ciudades de Ceuta y Melilla.</p>
                </td>
            </tr>

            <tr>
                <td width="5%" class="left-align" style="border: none;">
                    <input type="radio" id="opcionnn2" name="trabajador2">
                </td>
                <td width="95%" class="left-align" style="border: none;">
                    <p class="no-margin-bottom">Personas procedentes de servicios de prevención e inserción social autorizados/as por las Comunidades Autónomas y las ciudades de Ceuta y Melilla.</p>
                </td>
            </tr>
        </table>

        <p class="no-margin-bottom">Si el contrato se celebra a tiempo parcial la bonificación se reducirá proporcionalmente en función de la jornada establecida en el contrato, sin que esta pueda ser inferior, a efectos de la aplicación de los correspondientes incentivos, al 50 % de la jornada a tiempo completo de una persona trabajadora comparable. El citado límite de duración mínima de la jornada a tiempo parcial no resultará de aplicación al colectivo de personas con discapacidad. (Artículo 10.2 del Real Decreto-ley 1/2023, de 10 enero).</p>
        
        <p>En lo no previsto en este contrato, se estará a la legislación vigente que resulte de aplicación, y en particular, a lo dispuesto en el Estatuto de los Trabajadores, en la Ley 44/2007, de 13 de diciembre, en los artículos 5 al 9 de la Ley 43/2006, de 29 de diciembre y en el Real Decreto-ley 1/2023, de 10 de enero.</p>


        <ul class="no-bullets small-text no-line-break no-margin-bottom">
            <hr>
            <li>(1)	Indicar el organismo oficial que emite la certificación.</li>
        </ul>
    </div>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>
<!-- SEPTIMA PÁGINA -->

    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>
    <div class="mt-4 borde-redondeado">
        <input type="checkbox" id="formacion3" name="formacion3" class="no-line-break" {{ $trainingContract->disabled == 1 ? 'checked' : '' }}>
        <h1 class="no-line-break">CLÁUSULAS ESPECÍFICAS PARA LA FORMACIÓN EN ALTERNANCIA DE PERSONAS CON DISCAPACIDAD EN CENTROS ESPECIALES DE EMPLEO</h1>
        <br><br>
        <div class="mt-2">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 73%; border: none;"></td>
                    <td style="width: 27%; border: none;">
                        <table style="width: 100%; border: none; margin: 0; padding: 0;">
                            <tr>
                                <td style="border: none; text-align: right; padding: 0;">
                                    <p style="margin: 0;">CÓDIGO CONTRATO</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 0;">
                                    <div class="border-div">
                                        <p style="text-align: left;"><input type="radio" id="opcion1" name="tiempo" class="no-line-break">
                                        TIEMPO COMPLETO   <span class="dots" style="float: right; margin-right: 10px;">421</span></p>
                                        <p style="text-align: left;"><input type="radio" id="opcion2" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL   <span class="dots" style="float: right; margin-right: 10px;">521</span></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>


        <p>Se establece un período de adaptación al trabajo que a su vez tendrá el carácter de período de prueba de (1) <span class="dots">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> en las condiciones siguientes (2)
            <span class="fondo-azul">.............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................</span>
        </p>
        <p>Para lograr la adecuación del puesto de trabajo a las características del/de la trabajador/a, la empresa se compromete a realizar las siguientes adaptaciones al puesto de trabajo
            <span class="fondo-azul">.............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................</span>
            y/o en caso de que el contrato sea a distancia se realizarán los servicios de ajuste de personal y social siguientes
            <span class="fondo-azul">.............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................</span>
        </p>

        <p>
            Los centros especiales de empleo que contraten temporalmente a personas con discapacidad, tendrán derecho durante toda la vigencia del contrato a las bonificaciones del 100 % de la cuota empresarial a la Seguridad Social, incluidas las de accidente de trabajao y enfermedad profesional y las cuotas de recaudación conjunta de acuerdo con lo establecido en la Ley 43/2006, de 29 de diciembre y el Real Decreto-ley 1/2023, de 10 de enero.
        </p>
        <p>
            Asimismo podrá tener derecho a las subvenciones correspondientes de acuerdo con lo establecido en el Real Decreto 818/2021, de 28 de
            septiembre, en los términos que determine el servicio público de empleo competente.
        </p>
        <p>
        En lo no previsto en este contrato se estará a la legislación vigente que resulte de aplicación, y en particular en el Real Decreto 1368/1985, de 17 de julio, en la Ley 43/2006, de 29 de diciembre, en el Real Decreto-ley 1/2023, de 10 de enero y en el Estatuto de los Trabajadores.
        </p>

        <ul class="no-bullets small-text">
            <hr>
            <li>(1)	No podrá exceder de 6 meses.</li>
            <li>(2)	Las condiciones del período de adaptación al trabajo serán las determinadas, en su caso, por el Equipo Multiprofesional.</li>
        </ul>

    </div>
    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>
    <div style="page-break-after: always;"></div>

<!--OCTAVA PÁGINA -->
            
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>

    <div class="mt-4 borde-redondeado">
        <input type="checkbox" id="formacion4" name="formacion4" class="no-line-break">
        <h1 class="no-line-break">CLÁUSULAS ESPECÍFICAS PARA LA FORMACIÓN EN ALTERNANCIA DE PERSONAS MAYORES DE 52 AÑOS BENEFICIARIAS DE SUBSIDIOS POR DESEMPLEO</h1>
        <br><br>
        <div class="mt-2">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 73%; border: none;"></td>
                    <td style="width: 27%; border: none;">
                        <table style="width: 100%; border: none; margin: 0; padding: 0;">
                            <tr>
                                <td style="border: none; text-align: right; padding: 0;">
                                    <p style="margin: 0;">CÓDIGO CONTRATO</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 0;">
                                    <div class="border-div">
                                        <p style="text-align: left;"><input type="radio" id="opcion1" name="tiempo" class="no-line-break">
                                        TIEMPO COMPLETO   <span class="dots" style="float: right; margin-right: 10px;">421</span></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <p> El/la trabajador/a:</p>
        <p>Que es persona mayor de 52 años, se encuentra inscrito/a en el Servicio Público de Empleo y es beneficiario/a de cualquiera de los subsidios por desempleo:</p>
        
        <table>
            <tr>
                <td width="5%" class="left-align" style="border: none;">
                    <input type="radio" id="opcionnn1" name="trabajador3">
                </td>
                <td width="95%" class="left-align" style="border: none;">
                    <label for="opcionnn1" class="radio-label"><p>Recogidos en el artículo 274 del Texto Refundido de la Ley General de la Seguridad Social.</p></label>
                </td>
            </tr>
            <tr>

            <tr>
                <td width="5%" class="left-align" style="border: none;">
                    <input type="radio" id="opcionnn2" name="trabajador3">
                </td>
                <td width="95%" class="left-align" style="border: none;">
                    <label for="opcionnn2" class="radio-label"><p>Trabajadores/as eventuales incluidos en el Régimen Especial Agrario de la Seguridad Social (REASS)</p></label>
                </td>
            </tr>
        </table>

        <p>La Entidad Gestora de las prestaciones abonará mensualmente al/a la trabajador/a el 50 % de la cuantía del subsidio durante la vigencia del contrato, con el límite máximo del doble del período pendiente de percibirlo. El/la empresario/a, durante este tiempo tendrá cumplida la obligación del pago del salario que corresponda al/a la trabajador/a, completando la cuantía del subsidio recibido por el/la trabajador/a hasta el importe de dicho salario, siendo responsable de las cotizaciones a la Seguridad Social por todas las contingencias y por el total del salario indicado, incluyendo el importe del subsidio.</p>
        <p>En el supuesto de trabajadores/as incluidos en el REASS, la Entidad Gestora abonará al/a la trabajador/a el 50 % del importe de la cuota fija del REASS durante la vigencia del contrato y el/la empresario/a será responsable de la cotización por jornadas reales al REASS por las contingencias que correspondan.</p>
    </div>
    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>

    <div style="page-break-after: always;"></div>

<!-- Novena Página -->
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>

    <div class="mt-4 borde-redondeado">
        <input type="checkbox" id="formacion4" name="formacion4" class="no-line-break">
        <h1 class="no-line-break">CLÁUSULAS ESPECÍFICAS PARA PERSONAS TRABAJADORAS PARTICIPANTES EN EL PROGRAMA DE FOMENTO DE EMPLEO AGRARIO</h1>
        <br><br>
        <div class="mt-2">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 73%; border: none;"></td>
                    <td style="width: 27%; border: none;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="border: none; text-align: right;">
                                    <p style="margin: 0;">CÓDIGO CONTRATO</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: none;">
                                    <div class="border-div">
                                        <p style="text-align: left;"><input type="radio" id="opcion1" name="tiempo" class="no-line-break">
                                        TIEMPO COMPLETO   <span class="dots" style="float: right; margin-right: 10px;">421</span></p>
                                        <p style="text-align: left;"><input type="radio" id="opcion2" name="tiempo" class="no-line-break">
                                        TIEMPO PARCIAL   <span class="dots" style="float: right; margin-right: 10px;">521</span></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <p>Que este contrato se realiza en el marco del Programa de Fomento del Empleo Agrario. (Real Decreto 939/1997, de 20 de junio).</p>
        <p>Que el empleador es corporación local.</p>
        <p>Datos de la oferta de trabajo presentada en la oficina de empleo:
            <span class="fondo-azul">.............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................
            .............................................................................................................................................................................................................................................</span>
        </p>
        <div class="col-md-12">
            <ul class="no-bullets">
                <li>
                    Número de expediente del Programa de Fomento de Empleo Agrario: <span class="fondo-azul">.............................................</span>
                </li>
                <li>
                    Provincia: <span class="fondo-azul">................................................................</span>
                </li>
                <li>
                Localidad obra: <span class="fondo-azul">............................................................................................................................................................................................... </span>
                </li>
                <li>
                    Año: <span class="fondo-azul">..............</span>
                </li>
                <li>
                    Ent. Grupo: <span class="fondo-azul">............................................................................</span>
                </li>
                <li>
                    Programa: <span class="fondo-azul">.............................................................................</span>
                </li>
                <li>
                Número de sección: <span class="fondo-azul">............................................................................</span>
                </li>
            </ul>
        </div>

        <p>El presente contrato se regulará por lo dispuesto en la legislación vigente que resulte de aplicación y particularmente por el artículo 11 del Estatuto de los Trabajadores.</p>
    </div>

    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>
    <div style="page-break-after: always;"></div>

<!-- DECIMA PÁGINA -->
    <table>
        <tr>
            <td style="text-align: center; border: none;">
                <img src="AVZ/mtesescudo.PNG" alt="mtesescudo" class="img-fluid fixed-height-img-mtes3">
            </td>
        </tr>
    </table>
    <div class="mt-4 borde-redondeado">
        <h1 class="text-center">CLAUSULAS ADICIONALES</h1>
        <p class="text-center"><span class="fondo-azul">........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
            ........................................................................................................................................................................................................................................
        </span></p>

        <p>Y para que conste, se extiende este contrato por triplicado ejemplar en el lugar y fecha a continuación indicados, firmando las partes interesadas.</p>
        @php
    // Verificar si $trainingContract->beginning está definido y tiene el formato correcto
    if (isset($trainingContract->beginning) && preg_match('/\d{4}-\d{2}-\d{2}/', $trainingContract->beginning)) {
        // Convertir la cadena de fecha en un objeto DateTime
        $beginningDate = DateTime::createFromFormat('Y-m-d', $trainingContract->beginning);

        // Formatear la fecha al español
        $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $formatter->setPattern('d \'de\' MMMM \'de\' Y');
        $fechaFormateada = $formatter->format($beginningDate);

        // Separar el día, mes y año de la fecha formateada
        $fechaPartes = explode(' ', $fechaFormateada);
        $dia = $fechaPartes[0];
        $nombre_mes = $fechaPartes[2];
        $anio = $fechaPartes[4];
    } else {
        // Proporcionar valores por defecto si $trainingContract->beginning no está definido o no tiene el formato correcto
        $nombre_mes = 'mes desconocido';
        $dia = '00';
        $anio = '0000';
    }
@endphp

<p>En <span class="dots">{{$trainingContract->company->population}} </span> a <span class="dots">{{$dia}} </span> de <span class="dots">{{$nombre_mes}}</span> de <span class="dots">{{$anio}}</span></p>

   

        <div style="height:250px">
            <table>
                <tr>
                    <td style="width: 33%; border:none;">
                        <p>El/la trabajador/a</p>
                    </td>
                    <td style="width: 33%; border:none;">
                        <p>El/la representante de la empresa</p>
                    </td>
                    <td style="width: 33%; border:none;">
                        <p>El/la representante legal del/la menor, si procede</p>
                    </td>
                </tr>
            </table>
        </div>

        <h1 class="text-center">IMPORTANTE</h1>
        <h1 class="text-center">(TODAS LAS PÁGINAS CUMPLIMENTADAS DE ESTE CONTRATO DEBERÁN IR FIRMADAS EN EL MARGEN IZQUIERDO PARA MAYOR SEGURIDAD JURÍDICA)</h1>
    </div>
    <div style="position: absolute; bottom: 0; width: 100%;">
        <table>
            <tr>
                <td width="48%" style="border: none; text-align:left;">
                    <img src="AVZ/ue.PNG" alt="ue" class="img-fluid fixed-height-img-ue2">
                </td>
                <td width="4%" style="border: none; text-align:center;">
                    @php
                        $page++;
                        echo $page;
                    @endphp
                </td>
                <td width="48%" style="border: none; text-align:right;">
                    <img src="AVZ/sepe.PNG" alt="sepe" class="img-fluid fixed-height-img-sepe2">
                </td>
            </tr>
        </table>
    </div>
    <br>


   
</body>
</html>
