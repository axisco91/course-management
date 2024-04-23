<!DOCTYPE htms>
<htms lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anexo 1</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmsASjC" crossorigin="anonymous">
        <style>
            p{
                font-family: 'League Gothic', sans-serif !important;
            }

            .text-2xl{
                font-size: 1.125rem !important;
            }

            .text-xl{
                font-size: 1rem !important;
            }

            .text-lg{
                font-size: 0.95rem !important;
            }
            
            .bg-gray-300 {
                background-color: #C4C3C8;
            }

            .text-sm {
                font-size: 0.8rem;
            }

            .text-xs {
                font-size: 0.7rem;
            }

            .font-bold {
                font-weight: bold;
            }

            .font-semibold {
                font-weight: 600;
            }

            .no-line-break{
                display: inline;
            }

            .font-normal{
                font-weight: normal;
            }

            .border2{
                border-top: 1px solid black !important;
                border-bottom: 1px solid black !important;
                border-left: 1px solid black !important;
                border-right: none !important; 
            }

            .border-top{
                border-top: 1px solid black !important;
            }

            .border-right{
                border-right: 1px solid black !important;
            }

            .underline{
                text-decoration: underline;
            }
            .avoid-page-break {
                page-break-inside: avoid;
            }
        </style>
    </head>
    <body >
        <table style="width: 100%" class="mx-auto">
            <tr>
                <td><img class="my-auto mx-auto" src="./AVZ/ministerio.PNG" alt=""></td>
                <td class="mx-auto">
                    <p class="bg-gray-300 my-auto text-sm p-2 font-bold" style="width: 125%">SERVICIO PÚBLICO DE EMPLEO ESTATAL</p>
                </td>
                <td><img class="mx-5" src="./AVZ/logo.png" alt="" width="90%"></td>
            </tr>
        </table>

        <p class="text-lg font-bold">ANEXO 1</p>
        
        <p class="text-2xl mt-2 font-bold">ACUERDO PARA LA ACTIVIDAD FORMATIVA DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</p>

        <!-- DATOS GENERALES -->
        <section class="mt-2">
            <p class="text-2xl font-bold">1. DATOS GENERALES</p>

            {{-- FALTAN LOS CHECKBOX --}}
            <article class="border border-1 border-dark mt-2">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold">LA ACTIVIDAD FORMATIVA ESTARÁ DIRIGIDA A LA OBTENCIÓN DE <span class="text-sm font-normal">(desglose en apartado 2)</span></p>
                    <input type="checkbox" class="no-line-break"><p class="ms-2 no-line-break text-sm">Título de formación profesional (denominación) </p><br>
                    <input type="checkbox" class="no-line-break"><p class="ms-2 no-line-break text-sm">Certificado de profesionalidad (denominación)   </p><br>
                    <input type="checkbox" class="no-line-break"><p class="ms-2 no-line-break me-2 text-sm">Certificación académica</p> 
                    <input type="checkbox" class="no-line-break"><p class="no-line-break ms-2 text-sm">Acreditación parcial acumulable</p><br>
                    <input type="checkbox" class="no-line-break"><p class="ms-2 no-line-break text-sm">Especialidad/es del Cátalogo de especialidades formativas del Sistema Nacional de Empleo</p>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-2">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold">DATOS DE LA EMPRESA</p>
                    <p class="ms-2 text-sm">Razón social   {{$trainingContract->company->name}}   CIF/NIF/NIE   {{$trainingContract->company->nif}}</p>
                    <p class="ms-2 text-sm">D./Dña.   {{$trainingContract->company->legal_representative}}   en concepto de 
                        @if($trainingContract->company->company_type_id == "Autónomo")
                            TITULAR
                        @else
                            ADMINISTRADOR
                        @endif
                        NIF/NIE: {{$trainingContract->company->dni_legal_representative}}</p>
                    <p class="ms-2 text-sm">Correo electrónico de la empresa {{$trainingContract->company->email}} Tfno. empresa {{$trainingContract->company->telephone}}</p>
                    <p class="ms-2 text-sm">Tutor/a de la empresa - D./Dña. {{$trainingContract->company_tutor}} NIF/NIE {{$trainingContract->company_tutor_dni}}</p>
                    <input class="no-line-break" type="checkbox" {{$trainingContract->company->company_type_id == "Autónomo" ? 'checked' : ''}}><p class="ms-2 text-sm no-line-break"> Empresa con menos de 5 trabajadores</p>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-2 " style="page-break-after: always;">
                <div class="mx-auto" style="width: 95%; ">
                    <p class="text-xl font-bold">DATOS DEL TRABAJADOR</p>
                    <div class="text-sm">
                        <p class="ms-2 no-line-break">D./Dña.  {{$trainingContract->student->name}}  {{$trainingContract->student->surname}} </p>
                        <p class="ms-2 no-line-break">NIF/NIE   {{$trainingContract->student->dni}}</p>
                        <p class="ms-2 no-line-break">Fecha de nacimiento   {{$trainingContract->student->date_of_birth}}</p> <br>
                        <input class="no-line-break" type="checkbox"> <p class="ms-2 no-line-break">Reúne requisitos de acceso a la Formación de este contrato.</p><br>
                        <input class="no-line-break" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}> 
                        <p class="ms-2 no-line-break">Inscrito/a en el Sistema Nacional de Garantía Juvenil.</p><br>
                        <input class="no-line-break" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}>
                        <p class="ms-2 no-line-break">Trabajador/a con dispacidad.</p><br>
                        <input class="no-line-break" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}> 
                        <p class="ms-2 no-line-break">Trabajador/a en situación de exclusión social en empresas de inserción.</p>
                    </div>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-2">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold">DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</p>
                    <div class="text-sm">
                        <p class="ms-2 no-line-break">Identificador contrato n.º</p>
                        @php
                            $cfa = str_split($trainingContract->number_cfa);
                        @endphp
                        <p class="border2 no-line-break">
                            @foreach($cfa as $char)
                                <span style="border-right: 1px solid black;">{{ $char }}</span>
                            @endforeach
                        </p>
                        <p class="no-line-break">(a consignar una vez comunicada la formalización del contrato)</p><br>
                        <p class="ms-2 no-line-break">Fecha de inicio {{$trainingContract->beginning}}</p>
                        <p class="ms-2 no-line-break">Fecha de fin {{$trainingContract->end}}</p><br>
                        <p class="ms-2 no-line-break">Puesto de trabajo u ocupación {{$trainingContract->occupation->name}}</p>
                        <p class="ms-2 no-line-break">Cód. CNO {{$trainingContract->occupation->cno}}</p><br>
                        <p class="ms-2 no-line-break">Provincia del centro de trabajo {{$trainingContract->province->name ?? ''}}</p>
                        <p class="ms-2 no-line-break"> Horas del contrato: Año 1.º {{$trainingContract->formative_hours_first_year}}</p>
                        <p class="ms-2 no-line-break">Año 2.º {{$trainingContract->formative_hours_second_year}}</p><br>
                        <p class="ms-2">Convenio aplicable  {{$trainingContract->applicableAgreement->name ?? ''}} </p>
                    </div>
                </div>                
            </article>
        </section>

        <!-- ACTIVIDAD FORMATIVA -->
        <section class="mt-2"  style="page-break-after: always;">
            <p class="text-2xl font-bold">2. ACTIVIDAD FORMATIVA</p>
            
            <!-- 2.a -->
            <article>
                <p class="text-xl ms-5 font-bold">2. A Formación acreditable</p>
                <p class="text-sm" style="margin-left: 90px">(La actividad formativa deberá contener como mínimo un Módulo Formativo completo)</p>

                <table class="mt-3 mx-auto border border-2 border-dark" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="7" class="font-semibold">Título FP/CP/Módulos profesionales/Módulos formativos/Unidades formativas (todos «completos»)</td>
                        </tr>
                        <tr class="border-top border-2 border-dark text-center">
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark">Código</td>
                            <td class="font-semibold border-right  border-dark">Denominación</td>
                            <td class="font-semibold border-right  border-dark">N.º Horas</td>
                            <td class="font-semibold border-right  border-dark">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="font-semibold border-right  border-dark">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                            <td class="font-semibold">Grado título/Nivel CP</td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td class="border-right  border-dark">1</td>
                            <td class="border-right  border-dark">código</td>
                            <td class="border-right  border-dark">denominación</td>
                            <td class="border-right  border-dark">horas</td>
                            <td class="border-right  border-dark">Modalidad</td>
                            <td class="border-right  border-dark">codigo de centro</td>
                            <td>grado</td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <!-- 2.b -->
            <article>
                <p class="text-xl ms-5 mt-2 font-bold">2. B. Especialidades Formativas</p>

                <table class="mt3 mx-auto border border-2 border-dark" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="6" class="font-semibold">Especialidades formativas (completas)</td>
                        </tr>
                        <tr class="border-top border-2 border-dark text-center">
                            <td class="font-semibold border-right border-dark"></td>
                            <td class="font-semibold border-right border-dark">Código</td>
                            <td class="font-semibold border-right  border-dark">Denominación</td>
                            <td class="font-semibold border-right  border-dark">N.º Horas</td>
                            <td class="font-semibold border-right  border-dark">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="font-semibold">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                        </tr>
                        @php
                             $i = 0;    
                        @endphp
                        @foreach($elements as $e)
                            @php
                                $i++; 
                            @endphp
                            <tr class="border-top  border-dark text-center">
                                <td class="border-right  border-dark">{{$i}}</td>
                                <td class="border-right  border-dark">{{$e->training_action->code}}</td>
                                <td class="border-right  border-dark">{{$e->training_action->name}}</td>
                                <td class="border-right  border-dark">{{$e->training_action->total_hours}}</td>
                                <td class="border-right  border-dark">{{$e->training_action->modality->name}}</td>
                                <td>{{$e->training_action->webPlatform->name ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>

         <!-- CALENDARIO Y DISTRIBUCIÓN -->
        <section>
            <p class="text-2xl font-bold">3. CALENDARIO Y DISTRIBUCIÓN</p>
         
            {{-- RELLENAR TABLA --}}
            <article>
                <table class="mt-2 mx-auto border border-2 border-dark" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="7" class="font-semibold">N.º DE HORAS DE FORMACIÓN ANUALES</td>
                        </tr>
                        <tr class="border-top border-dark text-center">
                            <td class="font-semibold border-right  border-dark">AÑOS</td>
                            <td class="font-semibold border-right  border-dark">Min.%</td>
                            <td class="font-semibold border-right  border-dark">Hasta</td>
                            <td class="font-semibold border-right  border-dark">Título de Formación Profesiona/Certificado de Profesionalidad</td>
                            <td class="font-semibold border-right  border-dark">Certificación académica/Acreditación parcial acumulable</td>
                            <td class="font-semibold border-right  border-dark">Especialidad formativa</td>
                            <td class="font-semibold">TOTAL</td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td class="font-semibold border-right  border-dark">1º</td>
                            <td class="font-semibold border-right  border-dark">25%</td>
                            <td class="font-semibold border-right  border-dark">50% <span class="font-normal text-xs">(Garantía Juvenil)<span></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold"></td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td class="font-semibold border-right  border-dark">2º</td>
                            <td class="font-semibold border-right  border-dark">15%</td>
                            <td class="font-semibold border-right  border-dark">25% <span class="font-normal text-xs">(Garantía Juvenil)</span></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold"></td>
                        </tr>
                    </tbody>
                </table>
            </article>

            {{-- RELLENAR HORAS SEMANALES DE LA TABLA --}}
            <article>
                <table class="mt-2 mx-auto border border-2 border-dark" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="11" class="p-3 font-semibold">DISTRIBUCIÓN DE LA ACTIVIDAD LABORAL Y LA ACTIVIDAD FORMATIVA</td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td colspan="5" class="p-3 font-semibold border-right  border-dark">ACTIVIDAD LABORAL</td>
                            <td colspan="6" class="p-3 font-semibold border-right  border-dark">ACTIVIDAD FORMATIVA</td>
                        </tr>
                        <tr class="text-center border-top  border-dark ">
                            <td class="border-right  border-dark font-semibold">Fecha de inicio</td>
                            <td class="border-right  border-dark font-semibold">Fecha de fin</td>
                            <td class="border-right  border-dark font-semibold">Horas semanales de actividad laboral</td>
                            <td class="border-right  border-dark font-semibold">Días de la semana</td>
                            <td class="border-right  border-dark font-semibold">Horario</td>
                            <td class="border-right  border-dark font-semibold">Código formación</td>
                            <td class="border-right  border-dark font-semibold">Fecha de inicio</td>
                            <td class="border-right  border-dark font-semibold">Fecha de fin</td>
                            <td class="border-right  border-dark font-semibold">Horas semanales de actividad formativa</td>
                            <td class="border-right  border-dark font-semibold">Días de la semana</td>
                            <td class="font-semibold">Horario</td>
                        </tr>
                        @foreach($elements as $e)
                            <tr class="text-center border-top  border-dark ">
                                <td class="border-right  border-dark">{{$e->training_contract->beginning}}</td>
                                <td class="border-right  border-dark">{{$e->training_contract->end}}</td>
                                <td class="border-right  border-dark">
                                    @php
                                        $beginning_formation = \Carbon\Carbon::parse($e->training_contract->beginning_formation);
                                        $end_first_year = $beginning_formation->copy()->addYear();
                                        $beginning = \Carbon\Carbon::parse($e->beginning);
                                        $daily_hours = $beginning->lte($end_first_year) ? $e->training_contract->daily_hours_1 : $e->training_contract->daily_hours_2;
                                        $daily_hours_lab = 8 - $daily_hours;
                                    @endphp
                                    {{$daily_hours_lab * $daysWeek}}
                                </td>
                                <td class="border-right  border-dark">
                                    @foreach($dias as $day)
                                        {{$day}}
                                    @endforeach
                                </td>
                                <td class="border-right  border-dark">{{$e->training_contract->working_hours}}</td>
                                <td class="border-right  border-dark">{{$e->training_action->codigo}}</td>
                                <td class="border-right  border-dark">{{$e->training_contract->beginning_formation}}</td>
                                <td class="border-right  border-dark">{{$e->training_contract->end_formation}}</td>
                                <td class="border-right  border-dark">
                                @php
                                    $beginning_formation = \Carbon\Carbon::parse($e->training_contract->beginning_formation);
                                    $end_first_year = $beginning_formation->copy()->addYear();
                                    $beginning = \Carbon\Carbon::parse($e->beginning);
                                    $daily_hours = $beginning->lte($end_first_year) ? $e->training_contract->daily_hours_1 : $e->training_contract->daily_hours_1;
                                @endphp
                                {{$daily_hours * $daysWeek}}
                                </td>
                                <td class="border-right  border-dark">
                                    @foreach($dias as $day)
                                        {{$day}}
                                    @endforeach
                                </td>
                                <td>{{$e->training_contract->training_schedule}}</td>
                            </tr> 
                        @endforeach                                        
                    </tbody>
                </table>

                <p class="text-xs mt-2">Criterios para la conciliación de las vacaciones a las que tiene derecho la persona trabajadora en la empresa y de los períodos no lectivos en el centro de formación:</p>

                <p class="text-xs mt-2">La actividad formativa se desarrollará de acuerdo a la secuenciación y calendarización que se detallan en la planificación formativa que se acompaña al contrato y/o cada una de sus prórrogas</p>
            </article>
        </section>

        <!-- CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA -->
        <section class="mt-3 avoid-page-break">
            <p class="text-2xl font-bold">4. CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA</p>
        
            @foreach($elements as $e)
                <article class="mx-auto mt-4 border border-2 border-dark pb-3">
                    <p class="text-xl font-semibold ms-5 ">DATOS  DEL CENTRO DE FORMACIÓN</p>
                    <div class="mx-auto">
                        <p class="ms-2 mt-1 no-line-break">Formación a impartir:  Código {{$e->training_action->codigo}}</p>
                        <p class="ms-2 no-line-break">Denominación {{$e->training_action->name}}</p> <br>

                        <input class="ms-2 no-line-break" type="checkbox" {{$e->training_action->webPlatform != null ? 'checked' : ''}}>
                        <p class="ms-2 no-line-break"> Centro Sistema Educativo</p>
                        <p class="ms-2 no-line-break">Código de centro autorizado 
                            @if($e->training_action->webPlatform != null)
                                {{$e->training_action->webPlatform->codigo}}
                            @endif
                        </p><br>

                        <input class="ms-2 no-line-break" type="checkbox">
                        <p class="ms-2 no-line-break"> Centro Acreditado </p>
                        <p class="ms-2 no-line-break">Código de centro en Registro Estatal de Centros de Formación</p><br>

                        <input class="ms-2 no-line-break" type="checkbox" {{$e->training_action->modality->name == 'Teleformación' ? 'checked' : ''}}>
                        <p class="ms-2 no-line-break"> Si la formación se imparte mediante teleformación, especificar código/s del/os Centros Presenciales vinculados:</p><br>
                         (CENTROS PRESENCIALES) {{-- NO SE LO QUE IRÍA AQUÍ --}}
                         <br>
                        <p class="ms-2 mt-1 no-line-break">Nombre Centro {{$trainingContract->company->name}} </p>
                        <p class="ms-2 mt-1 no-line-break">CIF/NIF/NIE {{$trainingContract->company->nif}}</p><br>

                        <p class="ms-2">URL (Entidades de teleformación) {{$e->training_action->webPlatform->url ?? ''}}</p>
                        <p class="ms-2 mt-1 no-line-break">Dirección {{$trainingContract->company->address}}</p>
                        <p class="ms-2 mt-1 no-line-break">CP {{$trainingContract->company->post_code}}</p>
                        <p class="ms-2 mt-1 no-line-break"> Municipio {{$trainingContract->company->population}}</p><br>

                        <p class="ms-2 mt-1 no-line-break">Provincia  {{$trainingContract->province->name ?? ''}}</p>
                        <p class="ms-2 mt-1 no-line-break">Teléfono {{$trainingContract->company->telephone}}</p>
                        <p class="ms-2 mt-1 no-line-break"> Correo electrónico {{$trainingContract->company->email}}</p><br>

                        <p class="ms-2 mt-1 no-line-break">D./Dña. {{$trainingContract->company->legal_representative}} en concepto de 
                            @if($trainingContract->company->company_type_id == "Autónomo")
                                TITULAR
                            @else
                                ADMINISTRADOR
                            @endif
                        </p>
                        <p class="ms-2 mt-1 no-line-break">NIF/NIE  {{$trainingContract->company->dni_legal_representative}}</p><br>
                        <p class="ms-2 mt-1 no-line-break">Tutor/a del centro - D./Dña. {{$e->training_contract->company_tutor}} </p>
                        <p class="ms-2 mt-1 no-line-break">NIF/NIE {{$e->training_contract->company_tutor_dni}}</p><br>
                    </div>
                </article>
            @endforeach
        </section>  

        <!-- DATOS DECLARATIVOS Y SOLICITUD -->
        <section class="mt-5">
            <p class="text-2xl font-bold">5. DATOS DECLARATIVOS Y SOLICITUD</p>

            <article class="mx-auto mt-4 text-sm">
                <div>
                    <p>Declaro que el centro de trabajo se encuentra en: {{$trainingContract->company->address}}</p>
                    <p>Declaro bajo mi responsabilidad que son ciertos los datos que se consignan en el presente acuerdo, asumiento en caso 
                        contrario las responsabilidades que pudieran derivarse de su inexactitud.
                    </p>
                    <p>Declaro conocer lo establecido en el artículo 11.2 del Estatuto de los Trabajadores y el Real Decreto 1.529/2012, de 8 de 
                        noviembre  y  demás  normativas  de  desarrollo,  así  como  la  normativa  que  afecta  a  la  actividad  formativa  objeto  de  esta  
                        solicitud.
                    </p>
                    <p>Autorizo al Servicio Público de Empleo de la Comunidad Autónoma y al Servicio Público de Empleo Estatal a que acceda a 
                        las bases de datos de la Administración General del Estado y de las Administraciones de las Comunidades Autónomas, con 
                        garantía de confidencialidad y a los exclusivos efectos de facilitar la verificación de los datos consignados en esta solicitud, 
                        manifestando que quedo enterado de la obligación de informar a los Servicios Públicos de Empleo de cualquier variación 
                        de los mismos que pudiera producirse.
                    </p>
                    <p>Declaro bajo mi responsabilidad, a efectos de lo establecido en el art. 6 del R.D. 1529/2012, de 8 de noviembre, que la 
                        persona trabajadora objeto del contrato pertenece a alguno de los colectivos siguientes:
                    </p>
                </div>

                <div class="mt-3 text-sm">
                    <input class="no-line-break mr-2" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}><p class="ms-4 no-line-break">Personas con discapacidad</p><br>
                    <input class="no-line-break mr-2" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}><p class="no-line-break ms-4">Inscrito en el Sistema Nacional de Garantía Juvenil</p><br>
                    <input class="no-line-break mr-2" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}><p class="no-line-break ms-4">Colectivos en situación de exclusión social y que la empresa contratante es una empresa de inserción</p><br>
                </div>  

                <div class="mt-3 text-sm">
                    <p>Declaro bajo mi responsabilidad que la persona trabajadora, reúne alguno de los requisitos de acceso a la formación según 
                        lo establecido en el art. 20 del R. D. 34/2008 de 18 de Enero, y/o en la normativa del Sistema Educativo
                    </p>
                </div>

                <div class="mt-3 text-sm">
                    <input class="no-line-break mr-2" type="checkbox" checked><p class="no-line-break ms-4">Acepto y doy mi conformidad con lo aquí declarado.</p>
                </div>  

                <div class="mt-3 text-sm">
                    <p>Y solicito se dé curso a la presente solicitud de «autorización de inicio de la formación inherente al contrato para la forma-
                        ción y el aprendizaje» ante la autoridad competente para su resolución.
                    </p>
                </div>

                <div class="mt-10">
                    <p class="text-xl">CLÁUSULA DE CONFORMIDAD RGPD Y LOPD</p>
                    <p class="text-sm">De conformidad  con el Reglamento  UE 2016/679 relativo a la Protección  de las Personas  Físicas en lo que Respecta  al Tratamiento 
                        de Datos Personales  y con la L.O. 3/2018 de Protección  de Datos Personales  y Garantía  de Derechos  Digitales  ; le informamos  que 
                        los datos de contacto utilizados para la presente comunicación  están incluidos en un fichero titularidad de AVZ FORMACIÓN  SL; con 
                        la finalidad  de posibilitar  las comunicaciones  a través  de correo  electrónico  que ésta mantiene  dentro  del ejercicio  de su actividad  (
                        como clientes , proveedores  o personal). La causa que legitima este tratamiento de datos es el consentimiento . Los datos podrán ser 
                        transmitidos  a la  entidad  que  presta  el  servicio  de  asesoramiento  laboral  , fiscal  y contable  y en  su  caso  a la  entidad  de 
                        almacenamiento  web . Los datos  proporcionados  se conservarán  mientras  se mantenga  la relación  profesional  o durante  los años 
                        necesarios  para cumplir con las obligaciones  legales. Sin perjuicio de ello se le informa de que usted podrá ejercitar los derechos  de 
                        acceso , rectificación , supresión  (derecho  al olvido ), limitación  en el tratamiento  , portabilidad  y oposición  enviando  una solicitud  por 
                        escrito , acompañada  de  una  fotocopia  de  su  DNI  a la siguiente  dirección  : C.El Peso  35 , 3ºD, Lucena  (Córdoba ) CP  14900  o 
                        telemáticamente a través del siguiente correo electrónico: info@avzformacion.com
                    </p>
                </div>

                <div class="mt-3">
                    <p class="font-bold">Datos a efectos de notificación</p>
                    <p class="mt-1"> 
                        Dirección <span class="underline">{{$trainingContract->student->direction}}</span> 
                        CP <span class="underline">{{$trainingContract->student->post_code}}</span>
                    </p>
                    <p class="mt-1"> 
                        Provincia <span class="underline">{{$trainingContract->student->province->name ?? ''}}</span>
                        Correo Electrónico <span class="underline">{{$trainingContract->student->email ?? ''}}</span>
                        Teléfono de contacto <span class="underline">{{$trainingContract->student->telephone ?? ''}}</span>
                    </p>
                </div>
            </article>
        </section>

        <!-- FORMALIZACIÓN DEL ACUERDO -->
        <section class="mt-5">
            <p class="text-2xl font-semibold">6.  FORMALIZACIÓN DEL ACUERDO</p>

            <article class="mx-auto mt-3 text-sm">
                <div>
                    <p class="mb-3">A suscribir junto con el contrato de trabajo. Si hay más de un centro de formación, cada uno deberá suscribir este acuerdo.</p>
                    <p class="mt-3 mb-10">Todas las páginas de este acuerdo deberán ir firmadas en el margen izquierdo para mayor seguridad jurídica.</p>
                    <p class="mt-5">Y para que conste, se extiende este acuerdo para la actividad formativa en el lugar y fecha a continuación indicados, firmando las partes.</p>
                </div>

                <div class="text-end">
                    <p>
                        En Lucena
                        a {{now()->day}}
                        de {{now()->monthName}}
                        de {{now()->year}}
                    </p>
                </div>

                <div>
                    <table class="text-end">
                        <tr>
                            <td>El/la trabajador/a</td>
                            <td>El/la representante legal del/de la menor, si procede</td>
                            <td>El/la representate de la empresa</td>
                            <td>El/los representante del/de los Centros de Formación</td>
                        </tr>
                        <tr>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                        </tr>
                    </table>
                </div>
            </article>
        </section>
    </body>
</htms>