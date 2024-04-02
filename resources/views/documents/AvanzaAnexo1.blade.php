<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anexo 1</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body class="p-5">
        <div class="grid grid-col grid-flow-col">
            <img class="w-80 h-28 my-auto mx-auto" src="./Avanza/ministerio.PNG" alt="">
            <div class="my-auto p-3 bg-gray-300 mx-auto">SERVICIO PÚBLICO DE EMPLEO ESTATAL</div>
            <img class="mx-auto" src="./Avanza/logo.png" alt="" width="16%">
        </div>

        <h3 class="text-lg mt-3">ANEXO 1</h3>
        
        <h1 class="text-2xl mt-3">ACUERDO PARA LA ACTIVIDAD FORMATIVA DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</h1>

        <!-- DATOS GENERALES -->
        <section class="mt-3">
            <h1 class="text-2xl">1. DATOS GENERALES</h1>

            {{-- FALTAN LOS CHECKBOX --}}
            <article class="border border-1 border-black mt-4 p-3">
                <div class="w-3/4 mx-auto">
                    <h2 class="text-xl"><strong>LA ACTIVIDAD FORMATIVA ESTARÁ DIRIGIDA A LA OBTENCIÓN DE</strong> (desglose en apartado 2)</h2>
                    <p class="ml-5 mt-3"><input class="w-5 h-5" type="checkbox"> Título de formación profesional (denominación) <input class="border-b-2 border-black w-1/2 ml-2" type="text"></p>
                    <p class="ml-5 mt-3"><input class="w-5 h-5" type="checkbox"> Certificado de profesionalidad (denominación) <input class="border-b-2 border-black w-1/2 ml-2" type="text"></p>
                    <p class="ml-5 mt-3"><input class="w-5 h-5" type="checkbox"> Certificación académica <input class="w-5 h-5 ml-5" type="checkbox"> Acreditación parcial acumulable</p>
                    <p class="ml-5 mt-3"><input class="w-5 h-5" type="checkbox"> Especialidad/es del Cátalogo de especialidades formativas del Sistema Nacional de Empleo</p>
                </div>                
            </article>

            <article class="border border-1 border-black mt-4 p-3">
                <div class="w-3/4 mx-auto">
                    <h2 class="text-xl font-bold">DATOS DE LA EMPRESA</h2>
                    <p class="ml-5 mt-3">Razón social {{$company->name}} CIF/NIF/NIE {{$company->nif}}</p>
                    <p class="ml-5 mt-3">D./Dña. {{$company->legal_representative}} en concepto de 
                        @if($company->company_type_id == "Autónomo")
                            TITULAR
                        @else
                            ADMINISTRADOR
                        @endif
                        NIF/NIE: {{$company->dni_legal_representative}}</p>
                    <p class="ml-5 mt-3">Correo electrónico de la empresa {{$company->email}} Tfno. empresa {{$company->telephone}}</p>
                    <p class="ml-5 mt-3">Tutor/a de la empresa - D./Dña. {{$trainingContract->company_tutor}} NIF/NIE {{$trainingContract->company_tutor_dni}}</p>
                    <p class="ml-5 mt-3"><input class="w-5 h-5" type="checkbox"> Empresa con menos de 5 trabajadores</p>
                </div>                
            </article>

            <article class="border border-1 border-black mt-4 p-3">
                <div class="w-3/4 mx-auto">
                    <h2 class="text-xl font-bold">DATOS DEL TRABAJADOR</h2>
                    <p class="ml-5 mt-3">D./Dña.  {{$trainingContract->student->name}} NIF/NIE {{$trainingContract->student->dni}} Fecha de nacimiento {{$trainingContract->student->date_of_birth}}</p>
                    <p class="ml-5 mt-3">
                        <input class="w-5 h-5" type="checkbox" > 
                        Reúne requisitos de acceso a la Formación de este contrato.
                    </p>
                    <p class="ml-5 mt-3">
                        <input class="w-5 h-5" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}> 
                        Inscrito/a en el Sistema Nacional de Garantía Juvenil.
                    </p>
                    <p class="ml-5 mt-3">
                        <input class="w-5 h-5" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}> 
                        Trabajador/a con dispacidad.
                    </p>
                    <p class="ml-5 mt-3">
                        <input class="w-5 h-5" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}> 
                        Trabajador/a en situación de exclusión social en empresas de inserción.
                    </p>
                </div>                
            </article>

            <article class="border border-1 border-black mt-4 p-3">
                <div class="w-3/4 mx-auto">
                    <h2 class="text-xl font-bold">DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</h2>
                    <p class="ml-5 mt-3">Identificador contrato n.º {{$trainingContract->number_cfa}} (a consignar una vez comunicada la formalización del contrato)</p>
                    <p class="ml-5 mt-3">Fecha de inicio {{$trainingContract->beginning}} Fecha de fin {{$trainingContract->end}}</p>
                    <p class="ml-5 mt-3">Puesto de trabajo u ocupación {{$occupation->name}} Cód. CNO {{$occupation->cno}}</p>
                    <p class="ml-5 mt-3">
                        Provincia del centro de trabajo {{$province->name}} 
                        Horas del contrato: Año 1.º {{$trainingContract->formative_hours_first_year}}
                        Año 2.º {{$trainingContract->formative_hours_second_year}}
                        Año 3.º NO HAY ESTE CAMPO
                    </p>
                    <p class="ml-5 mt-3">Convenio aplicable (convenio) {{$trainingContract->applicableAgreement->name ?? ''}} </p>
                </div>                
            </article>
        </section>

        <!-- ACTIVIDAD FORMATIVA -->
        <section class="mt-3">
            <h1 class="text-2xl">2. ACTIVIDAD FORMATIVA</h1>
            
            <!-- 2.a -->
            <article>
                <h2 class="text-xl ml-5">2. A Formación acreditable</h2>
                <p class="ml-12">(La actividad formativa deberá contener como mínimo un Módulo Formativo completo)</p>

                <table class="m-5 w-11/12 mx-auto border border-2 border-black">
                    <thead>
                        <tr>
                            <th colspan="7" class="font-semibold">Título FP/CP/Módulos profesionales/Módulos formativos/Unidades formativas (todos «completos»)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t-2 border-black text-center">
                            <td class="p-5 font-semibold border-r-2 border-black"></td>
                            <td class="p-5 font-semibold border-r-2 border-black">Código</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Denominación</td>
                            <td class="p-5 font-semibold border-r-2 border-black">N.º Horas</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                            <td class="p-5 font-semibold">Grado título/Nivel CP</td>
                        </tr>

                        
                        <tr class="border-t-2 border-black text-center">
                            <td class="border-r-2 border-black">1</td>
                            <td class="border-r-2 border-black">codigo</td>
                            <td class="border-r-2 border-black">denominación</td>
                            <td class="border-r-2 border-black">horas</td>
                            <td class="border-r-2 border-black">Modalidad</td>
                            <td class="border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td><input type="text">grado</td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <!-- 2.b -->
            <article>
                <h2 class="text-xl ml-5">2. B. Especialidades Formativas</h2>

                <table class="m-5 mx-auto border border-2 border-black">
                    <thead>
                        <tr>
                            <th colspan="6" class="font-semibold">Especialidades formativas (completas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t-2 border-black text-center">
                            <td class="p-5 font-semibold border-r-2 border-black"></td>
                            <td class="p-5 font-semibold border-r-2 border-black">Código</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Denominación</td>
                            <td class="p-5 font-semibold border-r-2 border-black">N.º Horas</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="p-5 font-semibold">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                        </tr>
                        @php
                             $i = 0;    
                        @endphp
                        @foreach($elements as $e)
                            @php
                                $i++; 
                            @endphp
                            <tr class="border-t-2 border-black text-center">
                                <td class="border-r-2 border-black">{{$i}}</td>
                                <td class="border-r-2 border-black">{{$e->training_action->codigo}}</td>
                                <td class="border-r-2 border-black">{{$e->training_action->name}}</td>
                                <td class="border-r-2 border-black">{{$e->training_action->total_hours}}</td>
                                <td class="border-r-2 border-black">{{$e->training_action->modality->name}}</td>
                                <td>{{$e->training_action->webPlatform->name ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>

         <!-- CALENDARIO Y DISTRIBUCIÓN -->
        <section class="mt-3">
            <h1 class="text-2xl">3. CALENDARIO Y DISTRIBUCIÓN</h1>
         
            {{-- RELLENAR TABLA --}}
            <article>
                <table class="m-5 w-11/12 mx-auto border border-2 border-black">
                    <thead>
                        <tr>
                            <th colspan="7" class="font-semibold">N.º DE HORAS DE FORMACIÓN ANUALES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t-2 border-black text-center">
                            <td class="p-5 font-semibold border-r-2 border-black">AÑOS</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Min.%</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Hasta</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Título de Formación Profesiona/Certificado de Profesionalidad</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Certificación académica/Acreditación parcial acumulable</td>
                            <td class="p-5 font-semibold border-r-2 border-black">Especialidad formativa</td>
                            <td class="p-5 font-semibold">TOTAL</td>
                        </tr>
                        <tr class="border-t-2 border-black text-center">
                            <td class="font-semibold border-r-2 border-black">1º</td>
                            <td class="font-semibold border-r-2 border-black">25%</td>
                            <td class="font-semibold border-r-2 border-black">50% <span class="font-normal text-xs">(Garantía Juvenil)</span></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold"></td>
                        </tr>
                        <tr class="border-t-2 border-black text-center">
                            <td class="font-semibold border-r-2 border-black">2º</td>
                            <td class="font-semibold border-r-2 border-black">15%</td>
                            <td class="font-semibold border-r-2 border-black">25% <span class="font-normal text-xs">(Garantía Juvenil)</span></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold border-r-2 border-black"><input class="w-full" type="text"></td>
                            <td class="font-semibold"><input class="w-full" type="text"></td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <article>
                <table class="m-5 w-11/12 mx-auto border border-2 border-black">
                    <thead>
                        <tr>
                            <th colspan="11" class="font-semibold p-3">DISTRIBUCIÓN DE LA ACTIVIDAD LABORAL Y LA ACTIVIDAD FORMATIVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t-2 border-black text-center">
                            <td colspan="5" class="p-3 font-semibold border-r-2 border-black">ACTIVIDAD LABORAL</td>
                            <td colspan="6" class="p-3 font-semibold border-r-2 border-black">ACTIVIDAD FORMATIVA</td>
                        </tr>
                        <tr class="text-center border-t-2 border-black ">
                            <td class="border-r-2 border-black font-semibold">Fecha de inicio</td>
                            <td class="border-r-2 border-black font-semibold">Fecha de fin</td>
                            <td class="border-r-2 border-black font-semibold">Horas semanales de actividad laboral</td>
                            <td class="border-r-2 border-black font-semibold">Días de la semana</td>
                            <td class="border-r-2 border-black font-semibold">Horario</td>
                            <td class="border-r-2 border-black font-semibold">Código formación</td>
                            <td class="border-r-2 border-black font-semibold">Fecha de inicio</td>
                            <td class="border-r-2 border-black font-semibold">Fecha de fin</td>
                            <td class="border-r-2 border-black font-semibold">Horas semanales de actividad formativa</td>
                            <td class="border-r-2 border-black font-semibold">Días de la semana</td>
                            <td class="font-semibold">Horario</td>
                        </tr>
                        @foreach($elements as $e)
                            <tr class="text-center border-t-2 border-black ">
                                <td class="border-r-2 border-black">{{$e->training_contract->beginning}}</td>
                                <td class="border-r-2 border-black">{{$e->training_contract->end}}</td>
                                <td class="border-r-2 border-black">{{$e->training_contract->daily_hours * $daysWeek}}</td>
                                <td class="border-r-2 border-black">
                                    @foreach($dias as $day)
                                        {{$day}}
                                    @endforeach
                                </td>
                                <td class="border-r-2 border-black">{{$e->training_contract->working_hours}}</td>
                                <td class="border-r-2 border-black">{{$e->training_action->codigo}}</td>
                                <td class="border-r-2 border-black">{{$e->training_contract->beginning_formation}}</td>
                                <td class="border-r-2 border-black">{{$e->training_contract->end_formation}}</td>
                                <td class="border-r-2 border-black">horas semanales formativas</td>
                                <td class="border-r-2 border-black">
                                    @foreach($dias as $day)
                                        {{$day}}
                                    @endforeach
                                </td>
                                <td>{{$e->training_contract->training_schedule}}</td>
                            </tr> 
                        @endforeach                                        
                    </tbody>
                </table>
            </article>
        </section>

        <!-- CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA -->
        <section class="mt-3">
            <h1 class="text-2xl">4. CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA</h1>
        
            @foreach($elements as $e)
                <article class="mx-auto mt-5 border border-2 border-black pb-3">
                    <h2 class="text-xl font-semibold ml-5 ">DATOS  DEL CENTRO DE FORMACIÓN</h2>
                    <div class="w-11/12 mx-auto">
                        <p class="ml-5 mt-1">Formación a impartir:  Código {{$e->training_action->codigo}} Denominación {{$e->training_action->name}}</p>
                        <p class="ml-2 mt-1"><input class="w-5 h-5 mr-1" type="checkbox" {{$e->training_action->webPlatform != null ? 'checked' : ''}}> Centro Sistema Educativo  <span class="ml-12 pl-12">Código de centro autorizado 
                            @if($e->training_action->webPlatform != null)
                                {{$e->training_action->webPlatform->codigo}}
                            @endif
                        </span></p>
                        <p class="ml-2 mt-1"><input class="w-5 h-5 mr-1" type="checkbox"> Centro Acreditado  <span class="ml-12">Código de centro en Registro Estatal de Centros de Formación</span></p>
                        <p class="ml-2 mt-1"><input class="w-5 h-5 mr-1" type="checkbox" {{$e->training_action->modality->name == 'Teleformación' ? 'checked' : ''}}> Si la formación se imparte mediante teleformación, especificar código/s del/os Centros Presenciales vinculados:</p>
                        <p class="ml-10"><input class="border-b-2 border-black mr-3" type="text"><input class="border-b-2 border-black mr-3" type="text"><input class="border-b-2 border-black mr-3" type="text"><input class="border-b-2 border-black" type="text"></p>
                        <p class="mt-2">Nombre Centro {{$company->name}} CIF/NIF/NIE {{$company->nif}}</p>
                        <p class="mt-2">URL (Entidades de teleformación) {{$e->training_action->webPlatform->url ?? ''}}</p>
                        <p class="mt-2">Dirección {{$company->address}} CP {{$company->post_code}} Municipio {{$company->population}}</p>
                        <p class="mt-2">Provincia  {{$province->name}} Teléfono {{$company->telephone}} Correo electrónico {{$company->email}}</p>
                        <p class="mt-2">D./Dña. {{$company->legal_representative}} en concepto de 
                            @if($company->company_type_id == "Autónomo")
                                TITULAR
                            @else
                                ADMINISTRADOR
                            @endif
                            NIF/NIE  {{$company->dni_legal_representative}}</p>
                        <p class="mt-2">Tutor/a del centro - D./Dña. {{$e->training_contract->company_tutor}} NIF/NIE {{$e->training_contract->company_tutor_dni}}</p>
                    </div>
                </article>
            @endforeach
        </section>  

        <!-- DATOS DECLARATIVOS Y SOLICITUD -->
        <section class="mt-5">
            <h1 class="text-2xl">5. DATOS DECLARATIVOS Y SOLICITUD</h1>

            <article class="w-11/12 mx-auto mt-5">
                <div>
                    <p>Declaro que el centro de trabajo se encuentra en: {{$company->address}}</p>
                    <p class="mt-4">Declaro bajo mi responsabilidad que son ciertos los datos que se consignan en el presente acuerdo, asumiento en caso 
                        contrario las responsabilidades que pudieran derivarse de su inexactitud.
                    </p>
                    <p class="mt-4">Declaro conocer lo establecido en el artículo 11.2 del Estatuto de los Trabajadores y el Real Decreto 1.529/2012, de 8 de 
                        noviembre  y  demás  normativas  de  desarrollo,  así  como  la  normativa  que  afecta  a  la  actividad  formativa  objeto  de  esta  
                        solicitud.
                    </p>
                    <p class="mt-4">Autorizo al Servicio Público de Empleo de la Comunidad Autónoma y al Servicio Público de Empleo Estatal a que acceda a 
                        las bases de datos de la Administración General del Estado y de las Administraciones de las Comunidades Autónomas, con 
                        garantía de confidencialidad y a los exclusivos efectos de facilitar la verificación de los datos consignados en esta solicitud, 
                        manifestando que quedo enterado de la obligación de informar a los Servicios Públicos de Empleo de cualquier variación 
                        de los mismos que pudiera producirse.
                    </p>
                    <p class="mt-4">Declaro bajo mi responsabilidad, a efectos de lo establecido en el art. 6 del R.D. 1529/2012, de 8 de noviembre, que la 
                        persona trabajadora objeto del contrato pertenece a alguno de los colectivos siguientes:
                    </p>
                </div>

                <div class="mt-10">
                    <p class="ml-4"><input class="w-5 h-5 mr-2" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}>Personas con discapacidad</p>
                    <p class="ml-4"><input class="w-5 h-5 mr-2" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}>Inscrito en el Sistema Nacional de Garantía Juvenil</p>
                    <p class="ml-4"><input class="w-5 h-5 mr-2" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}>Colectivos en situación de exclusión social y que la empresa contratante es una empresa de inserción</p>
                </div>  

                <div class="mt-10">
                    <p>Declaro bajo mi responsabilidad que la persona trabajadora, reúne alguno de los requisitos de acceso a la formación según 
                        lo establecido en el art. 20 del R. D. 34/2008 de 18 de Enero, y/o en la normativa del Sistema Educativo
                    </p>
                </div>

                <div class="mt-10">
                    <p class="ml-4"><input class="w-5 h-5 mr-2" type="checkbox" checked>Acepto y doy mi conformidad con lo aquí declarado.</p>
                </div>  

                <div class="mt-10">
                    <p>Y solicito se dé curso a la presente solicitud de «autorización de inicio de la formación inherente al contrato para la forma-
                        ción y el aprendizaje» ante la autoridad competente para su resolución.
                    </p>
                </div>

                <div class="mt-10">
                    <h2 class="text-xl">CLÁUSULA DE CONFORMIDAD RGPD Y LOPD</h2>
                    <p>De conformidad  con el Reglamento  UE 2016/679 relativo a la Protección  de las Personas  Físicas en lo que Respecta  al Tratamiento 
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

                <div class="mt-10">
                    <p class="font-bold">Datos a efectos de notificación</p>
                    <p class="mt-1"> 
                        Dirección {{$trainingContract->student->direction}} 
                        CP {{$trainingContract->student->post_code}}
                    </p>
                    <p class="mt-1"> 
                        Provincia {{$trainingContract->student->province->name}}
                        Correo Electrónico {{$trainingContract->student->email}}
                        Teléfono de contacto {{$trainingContract->student->telephone}}
                    </p>
                </div>
            </article>
        </section>

        <!-- FORMALIZACIÓN DEL ACUERDO -->
        <section class="mt-5">
            <h1 class="text-2xl font-semibold">6.  FORMALIZACIÓN DEL ACUERDO</h1>

            <article class="w-11/12 mx-auto mt-5">
                <div class="mt-5">
                    <p class="mb-3">A suscribir junto con el contrato de trabajo. Si hay más de un centro de formación, cada uno deberá suscribir este acuerdo.</p>
                    <p class="mt-3 mb-10">Todas las páginas de este acuerdo deberán ir firmadas en el margen izquierdo para mayor seguridad jurídica.</p>
                    <p class="mt-5">Y para que conste, se extiende este acuerdo para la actividad formativa en el lugar y fecha a continuación indicados, firmando las partes.</p>
                </div>

                <div class="mt-10 text-end">
                    <p>
                        En <input class="border-b-2 w-1/3 border-black" type="text">
                        a <input class="border-b-2 w-1/12 border-black" type="text">
                        de <input class="border-b-2 w-1/12 border-black" type="text">
                        de 20<input class="border-b-2 w-1/12 border-black" type="text">
                    </p>
                </div>

                <div class="mt-10">
                    <div class="w-full mt-5 flex justify-end">
                        <div class="flex text-center w-2/3">
                            <p class="mr-5">El/la trabajador/a</p>
                            <p class="ml-5 mr-5">El/la representante legal del/de la menor, si procede</p>
                            <p class="ml-5 mr-5">El/la representate de la empresa</p>
                            <p class="ml-5">El/los representante del/de los Centros de Formación</p>
                        </div>
                    </div>
                    <div class="w-full mt-5 flex justify-end">
                        <div class="flex w-2/3">
                            <input class="mr-5 ml-0 border-b-2 border-black w-24" type="text">
                            <input class="ml-5 mr-5 border-b-2 border-black" type="text">
                            <input class="ml-5 mr-5 border-b-2 border-black" type="text">
                            <input class="ml-5 border-b-2 border-black" type="text">
                        </div>
                    </div>
                    @for($i = 0; $i < 3; $i++)
                        <div class="w-full mt-5 flex justify-end">
                            <input class="mr-9 border-b-2 border-black" type="text">
                        </div>
                    @endfor
                </div>
            </article>
        </section>
    </body>
</html>