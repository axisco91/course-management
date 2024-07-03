<!DOCTYPE htms>
<html lang="en">
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
                font-size: 1rem !important;
            }

            .text-xl{
                font-size: 0.9rem !important;
            }

            .text-lg{
                font-size: 0.8rem !important;
            }
            
            .text-md{
                font-size: 0.75rem !important;
            }

            .bg-gray-300 {
                background-color: #C4C3C8;
            }

            .text-sm {
                font-size: 0.7rem;
            }

            .text-xs {
                font-size: 0.65rem;
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

            .border-bottom{
                border-bottom: 1px solid black !important;
            }

            .border-right{
                border-right: 1px solid black !important;
            }

            .border-left{
                border-left: 1px solid black !important;
            }

            .underline{
                text-decoration: underline;
            }

            .avoid-page-break {
                page-break-inside: avoid;
            }

            .parrafo{
                margin-top: -8px !important; 
            }

            u {
                color: black;
            }

         
        header {
            position: fixed;
            top: -45px;
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
        }

        body {
            margin-top: 50px;
        }
        .no-border-top {
            border-top: none !important;
        }
        .no-border-left {
            border-left: none !important;
        }
        .no-border-right {
            border-right: none !important;
        }

        </style>
    </head>
    <body >
        <header class="mb-5">
            <table style="width: 100%" class="mx-auto mb-0">
                <tr>
                    <td><img class="my-auto mx-auto" src="./AVZ/ministerio.PNG" alt=""></td>
                    <td class="mx-auto">
                        <p class="bg-gray-300 my-auto text-sm p-2 font-bold" style="width: 125%">SERVICIO PÚBLICO DE EMPLEO ESTATAL</p>
                    </td>
                    <td><img class="mx-5" src="./AVZ/logo.png" alt="" width="80%"></td>
                    <td><img class="mx-5" src="./AVZ/euroCert.png" alt="" width="60%"></td>
                </tr>
            </table>
        </header>
        <main>
        <p class="text-lg font-bold">ANEXO 1</p>
        
        <p class="text-lg font-bold">ACUERDO PARA LA ACTIVIDAD FORMATIVA DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</p>

        <!-- DATOS GENERALES -->
        <section>
            <p class="text-lg font-bold">1. DATOS GENERALES</p>

            <article class="border border-1 border-dark mt-1">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold mb-0">LA ACTIVIDAD FORMATIVA ESTARÁ DIRIGIDA A LA OBTENCIÓN DE <span class="text-sm font-normal">(desglose en apartado 2)</span></p>
                    
                    <div class="text-lg">
                        <input type="checkbox" class="no-line-break mb-0"><span class="ms-2 no-line-break mb-0">Título de formación profesional (denominación) </span><br>
                        <input type="checkbox" class="no-line-break"><span class="ms-2 no-line-break">Certificado de profesionalidad (denominación)</span><br>
                        <input type="checkbox" class="no-line-break"><span class="ms-2 no-line-break me-2">Certificación académica</span><br>
                        <input type="checkbox" class="no-line-break"><span class="no-line-break ms-2">Acreditación parcial acumulable</span><br>    
                        <input type="checkbox" class="no-line-break" checked><span class="ms-2 no-line-break">Especialidad/es del Cátalogo de especialidades formativas del Sistema Nacional de Empleo</span>
                    </div>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-1">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold mb-0">DATOS DE LA EMPRESA</p>
                    <div class="text-lg">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 12%">Razón social</td>
                                <td class="border-bottom ms-2" style="width: 63%">{{$trainingContract->company->name}}</td>
                                <td style="11.5%" class="ms-2">CIF/NIF/NIE</td>
                                <td style="13.5%" class="border-bottom ms-2">{{$trainingContract->company->nif}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 7.5%">D./Dña.</td>
                                <td class="border-bottom ms-2" style="width: 34.5%">{{$trainingContract->company->legal_representative}}</td>
                                <td style="width: 12.5%" class="ms-2">en concepto de </td>
                                <td class="border-bottom" style="width: 29.5%">@if($trainingContract->company->companyType->name == "Autónomo")
                                        TITULAR
                                    @else
                                        ADMINISTRADOR
                                    @endif
                                </td>
                                <td style="width: 5%" class="ms-2">NIF/NIE: </td>
                                <td class="border-bottom ms-2" style="width: 11%">{{$trainingContract->company->dni_legal_representative}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <td>
                                <td style="width: 28%">Correo electrónico de la empresa</td>
                                <td class="border-bottom ms-2" style="width: 47%">{{$trainingContract->company->email}}</td>
                                <td style="width: 13%" class="ms-2">Tfno. empresa</td>
                                <td class="border-bottom ms-2" style="width: 12%">{{$trainingContract->company->telephone}}</td>
                            </td>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                {{--No se calcula bien--}}
                                {{-- @php
                                    $fechaInicio = \Carbon\Carbon::parse($trainingContract->start)->locale('es');
                                    $monthKey = $fechaInicio->format('Y-m');
                                    $sumaHoras += $monthlyFormationHours->{$monthKey} ?? 0;
                                @endphp --}}
                                <td style="width: 25%">Tutor/a de la empresa - D./Dña.</td>
                                <td class="border-bottom ms-2" style="width: 18.5%">{{$trainingContract->company_tutor}}</td>
                                <td style="width: 15%" class="ms-2">Horas mensuales</td>
                                <td class="border-bottom ms-2" style="width: 11.5%">40</td>
                                <td style="width: 8%" class="ms-2">NIF/NIE</td>
                                <td class="border-bottom ms-2" style="width: 17%">{{$trainingContract->company_tutor_dni}}</td>
                            </tr>
                        </table>
                        
                        <input class="no-line-break mb-0 ms-1" type="checkbox" {{$trainingContract->company->companyType->name == "Autónomo" ? 'checked' : ''}}><span class="ms-2 no-line-break"> Empresa con menos de 5 trabajadores</span>
                    </div>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-1">
                <div class="mx-auto" style="width: 95%; ">
                    <p class="text-xl font-bold mb-0">DATOS DEL TRABAJADOR</p>
                    <div class="text-lg">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 10%">D./Dña.</td>
                                <td class="border-bottom" style="width: 43%">{{$trainingContract->student->name}} {{$trainingContract->student->surname}} </td>
                                <td style="width: 8%">NIF/NIE</td>
                                <td class="border-bottom" style="width: 12%">{{$trainingContract->student->dni}}</td>
                                <td style="width: 20%">Fecha de nacimiento</td>
                                <td class="border-bottom" style="width: 7%">{{ \Carbon\Carbon::parse($trainingContract->student->date_of_birth)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                        <input class="no-line-break mb-0" type="checkbox" checked><p class="ms-2 mb-0 no-line-break">Reúne requisitos de acceso a la Formación de este contrato.</p><br>
                        <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}> 
                        <p class="ms-2 mb-0 no-line-break">Inscrito/a en el Sistema Nacional de Garantía Juvenil.</p><br>
                        <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}>
                        <p class="ms-2 no-line-break mb-0">Trabajador/a con dispacidad.</p><br>
                        <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}> 
                        <p class="ms-2 no-line-break mb-0">Trabajador/a en situación de exclusión social en empresas de inserción.</p>
                    </div>
                </div>                
            </article>

            <article class="border border-1 border-dark mt-1">
                <div class="mx-auto" style="width: 95%">
                    <p class="text-xl font-bold mb-0">DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</p>
                    <div class="text-lg">
                        <table style="width: 100%">
                            <tr>
                                <td>Identificador contrato n.º</td>
                                <p class="border2 no-line-break ps-1">
                                    @for($i = 0; $i < 16; $i++)
                                        <span style="border-right: 1px solid black; width:4.5px; padding-right: 2px; padding-left: 2px">  </span>
                                    @endfor
                                </p>
                                <td>(a consignar una vez comunicada la formalización del contrato)</td>
                            </tr>
                        </table>
                        <table style="width: 50%">
                            <tr>
                                <td style="width: 25%">Fecha de inicio</td>
                                <td class="border-bottom" >{{ \Carbon\Carbon::parse($trainingContract->beginning)->format('d/m/Y') }}</td>
                                <td style="width: 23%">Fecha de fin</td>
                                <td class="border-bottom">{{ \Carbon\Carbon::parse($trainingContract->end)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 25%">Puesto de trabajo u ocupación</td>
                                <td class="border-bottom">{{$trainingContract->occupation->name}}</td>
                                <td style="width: 10%">Cód. CNO</td>
                                <td class="border-left border-top border-bottom" style="width: 20%">
                                    @php
                                        $cnoArray = str_split($trainingContract->occupation->cno);
                                    @endphp

                                    <span class="border-right" style="padding-top: 7px; padding-bottom: 3.5px; padding-right: 3.1px; padding-left: 3px">
                                        {{$trainingContract->occupation->cno}}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 25%">Provincia del centro de trabajo</td>
                                <td class="border-bottom">{{$trainingContract->province->name ?? ''}}</td>
                                <td style="width: 22%">Horas del contrato: Año 1.º</td>
                                <td class="border-bottom" style="width: 8%">{{$trainingContract->formative_hours_first_year}}</td>
                                <td  style="width: 8%">Año 2.º</td>
                                <td class="border-bottom" style="width: 8%">{{$trainingContract->formative_hours_second_year}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%" class="mb-2">
                            <tr>
                                <td style="width: 18%">Convenio aplicable</td>
                                <td class="border-bottom" style="width: 85%">{{$trainingContract->company->agreement ?? ''}}</td>
                            </tr>
                        </table>
                    </div>
                </div>                
            </article>
        </section>

        <!-- ACTIVIDAD FORMATIVA -->
        <section class="mt-1" >
            <p class="text-lg font-bold mb-0">2. ACTIVIDAD FORMATIVA</p>
            
            <!-- 2.a -->
            <article>
                <p class="text-xl ms-5 font-bold mb-0">2. A Formación acreditable</p>
                <p class="text-sm" style="margin-left: 90px">(La actividad formativa deberá contener como mínimo un Módulo Formativo completo)</p>

                <table class="mt-1 mx-auto border border-2 border-dark text-sm" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="7" class="font-semibold">Título FP/CP/Módulos profesionales/Módulos formativos/Unidades formativas (todos «completos»)</td>
                        </tr>
                        <tr class="border-top border-2 border-dark text-center">
                            <td class="font-semibold border-right border-dark"></td>
                            <td class="font-semibold border-right border-dark">Código</td>
                            <td class="font-semibold border-right border-dark">Denominación</td>
                            <td class="font-semibold border-right border-dark">N.º Horas</td>
                            <td class="font-semibold border-right border-dark">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="font-semibold border-right border-dark">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                            <td class="font-semibold">Grado título/Nivel CP</td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td class="border-right  border-dark">1</td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </article>


            <div style="position: absolute; bottom: 0; width: 100%;">
                <table style="margin: auto;">
                    <tr>
                        <td style="border: none; text-align: center; margin: 0; padding: 0;">
                            @php
                                $page=1;
                            @endphp
                            <p class="text-lg">{{ $page }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="page-break-after: always;"></div>


            <!-- 2.b -->
            <article>
                <p class="text-xl ms-5 mt-2 font-bold">2. B. Especialidades Formativas</p>
            
                <table class="mt3 mx-auto border border-2 border-dark text-sm" style="width: 100%">
                    <tbody>
                        <tr>
                            <td colspan="6" class="font-semibold">Especialidades formativas (completas)</td>
                        </tr>
                        <tr class="border-top border-2 border-dark text-center">
                            <td class="font-semibold border-right border-dark"></td>
                            <td class="font-semibold border-right border-dark">Código</td>
                            <td class="font-semibold border-right border-dark">Denominación</td>
                            <td class="font-semibold border-right border-dark">N.º Horas</td>
                            <td class="font-semibold border-right border-dark">Modalidad (Presencial, Teleformación, Distancia1)</td>
                            <td class="font-semibold">Código de Centro educativo autorizado / Código del Centro acreditado en Registro Estatal</td>
                        </tr>
            
                        @php
                            $i = 0;   
                        @endphp
                        @foreach($elements as $e)
                            @if($i != 0 && $i % 18 == 0)
                                <tr class="border-top border-dark">
                                    <td colspan="6" style="height: 0px;"></td>
                                </tr>
                                <div style="position: absolute; bottom: 0; width: 100%;">
                                    <table style="margin: auto;">
                                        <tr>
                                            <td style="border: none; text-align: center; margin: 0; padding: 0;">
                                                @php
                                                    $page++;
                                                @endphp
                                                <p class="text-lg">{{ $page }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <tr style="page-break-after: always;"></tr>
                                <tr class="border-bottom border-dark">
                                    <td colspan="6" style="height: 0px;"></td>
                                </tr>
                            @endif
                            @php
                                $i++;
                            @endphp
                            <tr class="border-top border-dark text-center {{ $i % 18 == 1 ? 'no-border-top' : '' }}">
                                <td class="border-right border-dark {{ $i % 18 == 1 ? 'no-border-left' : '' }}">{{$i}}</td>
                                <td class="border-right border-dark">{{$e->training_action->code}}</td>
                                <td class="border-right border-dark">{{$e->training_action->name}}</td>
                                <td class="border-right border-dark">{{$e->training_action->total_hours}}</td>
                                <td class="border-right border-dark">{{$e->training_action->modality->name}}</td>
                                <td>{{$e->training_action->webPlatform->name ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        </section>

         <!-- CALENDARIO Y DISTRIBUCIÓN -->
        <section style="page-break-after: always">
            <p class="text-lg font-bold">3. CALENDARIO Y DISTRIBUCIÓN</p>
         
            <article>
                <table class="mt-2 mx-auto border border-2 border-dark text-sm" style="width: 100%">
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
                            <td class="font-semibold border-right  border-dark">35%</td>
                            <td class="font-semibold border-right  border-dark">50% <span class="font-normal text-xs">(Garantía Juvenil)<span></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark">{{$trainingContract->percentage_first_year}}</td>
                            <td class="font-semibold">{{$trainingContract->percentage_first_year}}</td>
                        </tr>
                        <tr class="border-top  border-dark text-center">
                            <td class="font-semibold border-right  border-dark">2º</td>
                            <td class="font-semibold border-right  border-dark">15%</td>
                            <td class="font-semibold border-right  border-dark">25% <span class="font-normal text-xs">(Garantía Juvenil)</span></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark"></td>
                            <td class="font-semibold border-right  border-dark">{{$trainingContract->percentage_second_year}}</td>
                            <td class="font-semibold">{{$trainingContract->percentage_second_year}}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="position: absolute; bottom: 0; width: 100%;">
                    <table style="margin: auto;">
                        <tr>
                            <td style="border: none; text-align: center; margin: 0; padding: 0;">
                                @php
                                    $page++;
                                @endphp
                                <p class="text-lg">{{ $page }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </article>

            <article style="page-break-before: always">
                
                <table class="mt-2 mx-auto border border-2 border-dark text-sm" style="width: 100%">
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
                        <tr class="text-center border-top  border-dark ">
                            <td class="border-right  border-dark">{{$trainingContract->beginning}}</td>
                            @php
                                $segundoAnyo = false;
                                $beginning_formation = \Carbon\Carbon::parse($trainingContract->beginning_formation);
                                $end_first_year = $beginning_formation->copy()->addYear()->format('Y-m-d');
                                // Si la fecha de fin de la formación es mayor a la fecha de fin del primer año
                                // entonces vamos a añadir 
                                if($trainingContract->end_formation > $end_first_year)
                                    $segundoAnyo = true;
                                
                            @endphp
                            <td class="border-right  border-dark">{{$segundoAnyo ? $end_first_year : $trainingContract->end}}</td>
                            <td class="border-right  border-dark">
                                @php
                                    $hoursWeek = 0;

                                    // Si es 1800 horas anuales serán 40 horas semanales
                                    if($trainingContract->annually_day_hours == 1800)
                                        $hoursWeek = 40; 

                                    // Si no son 1800 horas pero no llega a ser un año serán 40 horas semanales
                                    elseif ($trainingContract->annually_day_hours != 1800 && $end_first_year > $trainingContract->end) {
                                        $hoursWeek = 40; 
                                    }

                                    // Si no pues hacemos una regla de tres
                                    else {
                                        $hoursWeek = ($trainingContract->annually_day_hours * 40)/ 1800;
                                    }
                                    // Calculo el porcentaje de horas laborales del primer año
                                    $porcentajeLaboral_first_year = 100 - $trainingContract->percentage_first_year;                                    
                                @endphp
                                {{($porcentajeLaboral_first_year * $hoursWeek) / 100}}
                            </td>
                            <td class="border-right  border-dark">
                                @foreach($dias as $day)
                                    {{$day}}
                                @endforeach
                            </td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark">{{$trainingContract->beginning_formation}}</td>
                            <td class="border-right  border-dark">{{$segundoAnyo ? $end_first_year : $trainingContract->end_formation}}</td>
                            <td class="border-right  border-dark">{{$trainingContract->percentage_first_year * $hoursWeek / 100}}</td>
                            <td class="border-right  border-dark">
                                @foreach($dias as $day)
                                    {{$day}}
                                @endforeach
                            </td>
                            <td></td>
                        </tr>           
                        @if($segundoAnyo)
                        <tr class="text-center border-top  border-dark ">
                            @php
                                $beginning_formation = \Carbon\Carbon::parse($trainingContract->beginning_formation);
                                $end_first_year = $beginning_formation->copy()->addYear();
                                $start_second_year = $end_first_year->copy()->addDay()->format('Y-m-d');
                            @endphp
                            <td class="border-right  border-dark">{{$start_second_year}}</td>
                            <td class="border-right  border-dark">{{$trainingContract->end}}</td>
                            <td class="border-right  border-dark">
                                @php
                                $hoursWeek = 0;
                                    // Si es 1800 horas anuales serán 40 horas semanales
                                    if($trainingContract->annually_day_hours == 1800)
                                        $hoursWeek = 40; 

                                    // Si no pues hacemos una regla de tres
                                    else {
                                        $hoursWeek = ($trainingContract->annually_day_hours * 40)/ 1800;
                                    }
                                    // Calculo el porcentaje de horas laborales del primer año
                                    $porcentajeLaboral_second_year = 100 - $trainingContract->percentage_second_year;                                    
                                @endphp
                                {{($porcentajeLaboral_second_year * $hoursWeek) / 100}}
                            </td>
                            <td class="border-right  border-dark">
                                @foreach($dias as $day)
                                    {{$day}}
                                @endforeach
                            </td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark"></td>
                            <td class="border-right  border-dark">{{$start_second_year}}</td>
                            <td class="border-right  border-dark">{{$trainingContract->end_formation}}</td>
                            <td class="border-right  border-dark">{{$trainingContract->percentage_second_year * $hoursWeek / 100}}</td>
                            <td class="border-right  border-dark">
                                @foreach($dias as $day)
                                    {{$day}}
                                @endforeach
                            </td>
                            <td></td>
                        </tr>    
                        @endif                             
                    </tbody>
                </table>

                <p class="text-xs mt-1 mb-0">Criterios para la conciliación de las vacaciones a las que tiene derecho la persona trabajadora en la empresa y de los períodos no lectivos en el centro de formación:</p>
                <p class="text-xs mt-0 mb-0">La actividad formativa se desarrollará de acuerdo a la secuenciación y calendarización que se detallan en la planificación formativa que se acompaña al contrato y/o cada una de sus prórrogas</p>
                <p class="text-xs mt-0 mb-0">https://www.sepe.es</p>

                <div style="position: absolute; bottom: 0; width: 100%;">
                    <table style="margin: auto;">
                        <tr>
                            <td style="border: none; text-align: center; margin: 0; padding: 0;">
                                @php
                                    $page++;
                                @endphp
                                <p class="text-lg">{{ $page }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </article>
        </section>

        <!-- CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA -->
        <section>
            <p class="text-lg font-bold">4. CENTROS IMPARTIDORES DE LA ACTIVIDAD FORMATIVA</p>
            @php
                $i = 0;
            @endphp
            @foreach($elements as $e)
                @if($i != 0 && $i % 4 == 0)

                    <div style="position: absolute; bottom: 0; width: 100%;">
                        <table style="margin: auto;">
                            <tr>
                                <td style="border: none; text-align: center; margin: 0; padding: 0;">
                                    @php
                                        $page++;
                                    @endphp
                                    <p class="text-lg">{{ $page }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="page-break-after: always;"></div>
                    
                @endif
                @php
                    $i++;
                @endphp
                <article class="mx-auto mt-1 border border-2 border-dark pb-1" >
                    <p class="text-sm font-semibold ms-2 mb-0 ">DATOS  DEL CENTRO DE FORMACIÓN</p>
                    <div class="mx-auto text-xs">
                        <table class="ms-4" style="width: 95%">
                            <tr>
                                <td style="width: 21%">Formación a impartir:  Código</td>
                                <td class="border-bottom" style="width: 13%">{{$e->training_action->codigo}}</td>
                                <td style="width: 11%">Denominación</td>
                                <td class="border-bottom no-linea-break">{{$e->training_action->name}}</td>
                            </tr>
                        </table>

                        <div class="ms-2">
                            <input class="mb-0 no-line-break" type="checkbox">
                            <p class="ms-2 mb-0 no-line-break"> Centro Sistema Educativo</p>
                            <p class="ms-2 mb-0 no-line-break">Código de centro autorizado </p>
                        </div>

                        <div class="ms-2">
                            <input class="mb-0 no-line-break" type="checkbox" checked>
                            <p class="ms-2 mb-0 no-line-break"> Centro Acreditado </p>
                            <p class="ms-2 mb-0 no-line-break">Código de centro en Registro Estatal de Centros de Formación
                                @if($e->training_action->webPlatform != null)
                                    {{$e->training_action->webPlatform->codigo}}
                                @endif    
                            </p>
                        </div>

                        <div class="ms-2">
                            <input class="mt-0 mb-0 no-line-break" type="checkbox">
                            <p class="ms-2 mt-0 mb-0 no-line-break"> Si la formación se imparte mediante teleformación, especificar código/s del/os Centros Presenciales vinculados:</p>
                        </div>

                        <table class="ms-3" style="width: 95%">
                            <tr>
                                <td style="width: 13%">Nombre Centro</td>
                                <td class="border-bottom">AVZ FORMACION, SL</td>
                                <td style="width: 12%">CIF/NIF/NIE</td>
                                <td class="border-bottom" style="width: 15%">B16826638L</td>
                            </tr>
                        </table>

                        <table class="ms-3" style="width: 95%">
                            <tr>
                                <td style="width: 25%">URL (Entidades de teleformación)</td>
                                <td class="border-bottom">avzformacion.com/aula</td>
                            </tr>
                        </table>

                        <table class="ms-3" style="width: 95%">
                            <tr>
                                <td style="width: 9%">Dirección</td>
                                <td class="border-bottom">C\ EL PESO 35, 3º D</td>
                                <td style="width: 5%">CP</td>
                                <td class="border-bottom">14900</td>
                                <td style="width: 9%">Municipio</td>
                                <td class="border-bottom">LUCENA</td>
                            </tr>
                        </table>

                        <table class="ms-3" style="width: 95%">
                            <tr>
                                <td style="width: 9%">Provincia</td>
                                <td class="border-bottom">CÓRDOBA</td>
                                <td style="width: 8%">Teléfono</td>
                                <td class="border-bottom">910600410</td>
                                <td style="width: 13%">Correo electrónico</td>
                                <td class="border-bottom">info@avzformacion.com</td>
                            </tr>
                        </table>

                        <table class="ms-3" style="width: 95%">
                            <tr>
                                <td style="width: 6%">D./Dña.</td>
                                <td class="border-bottom" style="width: 29%">ANTONIO JOSE JIMENEZ AGRAZ</td>
                                <td style="width: 13%">en concepto de</td>
                                <td class="border-bottom">ADMINISTRADOR</td>
                                <td style="width: 6%">NIF/NIE</td>
                                <td class="border-bottom">50614013Y</td>
                            </tr>
                        </table>
                        {{-- HAY QUE CAMBIAR LA BASE DE DATOS --}}
                        <table class="ms-3" style="width: 95%">
                            <td style="width: 20%">Tutor/a del centro - D./Dña.</td>
                            <td class="border-bottom" style="width: 54%">{{$e->training_tutor}}</td>
                            <td style="width: 6%">NIF/NIE</td>
                            <td class="border-bottom" style="width: 20%">{{$e->training_tutor_dni}}</td><br>
                        </table>
                    </div>
                </article>
            @endforeach
            <div style="position: absolute; bottom: 0; width: 100%;">
                <table style="margin: auto;">
                    <tr>
                        <td style="border: none; text-align: center; margin: 0; padding: 0;">
                            @php
                                $page++;
                            @endphp
                            <p class="text-lg">{{ $page }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </section>  

        <!-- DATOS DECLARATIVOS Y SOLICITUD -->
        <section class="mt-3" style="page-break-before: always;">

            <p class="text-lg font-bold">5. DATOS DECLARATIVOS Y SOLICITUD</p>

            <article class="mx-auto text-sm">
                <div>
                    <p class="mb-0">Declaro que el centro de trabajo se encuentra en: <span class="underline">{{$trainingContract->company->address}}</span></p>
                    <p class="mb-0">Declaro bajo mi responsabilidad que son ciertos los datos que se consignan en el presente acuerdo, asumiento en caso 
                        contrario las responsabilidades que pudieran derivarse de su inexactitud.
                    </p>
                    <p class="mb-0">Declaro conocer lo establecido en el artículo 11.2 del Estatuto de los Trabajadores y el Real Decreto 1.529/2012, de 8 de 
                        noviembre  y  demás  normativas  de  desarrollo,  así  como  la  normativa  que  afecta  a  la  actividad  formativa  objeto  de  esta  
                        solicitud.
                    </p>
                    <p class="mb-0">Autorizo al Servicio Público de Empleo de la Comunidad Autónoma y al Servicio Público de Empleo Estatal a que acceda a 
                        las bases de datos de la Administración General del Estado y de las Administraciones de las Comunidades Autónomas, con 
                        garantía de confidencialidad y a los exclusivos efectos de facilitar la verificación de los datos consignados en esta solicitud, 
                        manifestando que quedo enterado de la obligación de informar a los Servicios Públicos de Empleo de cualquier variación 
                        de los mismos que pudiera producirse.
                    </p>
                    <p class="mb-0">Declaro bajo mi responsabilidad, a efectos de lo establecido en el art. 6 del R.D. 1529/2012, de 8 de noviembre, que la 
                        persona trabajadora objeto del contrato pertenece a alguno de los colectivos siguientes:
                    </p>
                </div>

                <div class="text-sm ms-2">
                    <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}><p class="ms-2 no-line-break mb-0">Personas con discapacidad</p><br>
                    <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}><p class="no-line-break ms-2 mb-0">Inscrito en el Sistema Nacional de Garantía Juvenil</p><br>
                    <input class="no-line-break mb-0" type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}><p class="no-line-break ms-2 mb-0">Colectivos en situación de exclusión social y que la empresa contratante es una empresa de inserción</p><br>
                </div>  

                <div class="text-sm">
                    <p class="mb-0">Declaro bajo mi responsabilidad que la persona trabajadora, reúne alguno de los requisitos de acceso a la formación según 
                        lo establecido en el art. 20 del R. D. 34/2008 de 18 de Enero, y/o en la normativa del Sistema Educativo
                    </p>
                </div>

                <div class="ms-2 text-sm">
                    <input class="no-line-break mb-0" type="checkbox" checked><p class="no-line-break ms-2 mb-0">Acepto y doy mi conformidad con lo aquí declarado.</p>
                </div>  

                <div class="text-sm">
                    <p>Y solicito se dé curso a la presente solicitud de «autorización de inicio de la formación inherente al contrato para la forma-
                        ción y el aprendizaje» ante la autoridad competente para su resolución.
                    </p>
                </div>

                <div>
                    <p class="text-lg mb-0">CLÁUSULA DE CONFORMIDAD RGPD Y LOPD</p>
                    <p class="text-sm mb-0">De conformidad  con el Reglamento  UE 2016/679 relativo a la Protección  de las Personas  Físicas en lo que Respecta  al Tratamiento 
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

                <div>
                    <p class="font-bold mb-0">Datos a efectos de notificación</p>
                    <p class="mt-1 mb-0"> 
                        Dirección <span class="underline">C/PEDRO ANGULO 6</span> 
                        CP <span class="underline">14900</span>
                    </p>
                    <p class="mt-1"> 
                        Provincia <span class="underline">CÓRDOBA</span>
                        Correo Electrónico <span class="underline">info@avzformacion.com</span>
                        Teléfono de contacto <span class="underline">910600410</span>
                    </p>
                </div>
            </article>
            <div style="position: absolute; bottom: 0; width: 100%;">
                <table style="margin: auto;">
                    <tr>
                        <td style="border: none; text-align: center; margin: 0; padding: 0;">
                            @php
                                $page++;
                            @endphp
                            <p class="text-lg">{{ $page }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- FORMALIZACIÓN DEL ACUERDO -->
        <section class="mt-3" style="page-break-before: always ">

            <p class="text-lg font-semibold">6.  FORMALIZACIÓN DEL ACUERDO</p>

            <article class="mx-auto mt-1 text-sm">
                <div>
                    <p class="mb-3">A suscribir junto con el contrato de trabajo. Si hay más de un centro de formación, cada uno deberá suscribir este acuerdo.</p>
                    <p class="mt-3 mb-10">Todas las páginas de este acuerdo deberán ir firmadas en el margen izquierdo para mayor seguridad jurídica.</p>
                    <p class="mt-5">Y para que conste, se extiende este acuerdo para la actividad formativa en el lugar y fecha a continuación indicados, firmando las partes.</p>
                </div>

                @php
                    $meses = [
                        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                    ];
                    $dia = \Carbon\Carbon::parse($trainingContract->beginning)->format('d');
                    $num_mes = \Carbon\Carbon::parse($trainingContract->beginning)->format('m');
                    $anio = \Carbon\Carbon::parse($trainingContract->beginning)->format('Y');

                    $mes = $meses[$num_mes - 1];
                @endphp
                <div class="text-end">
                    <p>
                        En LUCENA, a {{$dia}} de {{$mes}} de {{$anio}}
                    </p>
                </div>

                <div>
                    <table class="text-end">
                        <tr>
                            <td class="p-4 text-center">El/la trabajador/a</td>
                            <td class="p-4 text-center">El/la representante legal del/de la menor, si procede</td>
                            <td class="p-4 text-center">El/la representate de la empresa</td>
                            <td class="p-4 text-center">El/los representante del/de los Centros de Formación</td>
                        </tr>
                        <tr>
                            <td class="pe-4 text-center">{{$trainingContract->student->name}}  {{$trainingContract->student->surname}}</td>
                            <td class="pe-4 text-center"></td>
                            <td class="pe-4 text-center">{{$trainingContract->company->legal_representative}}</td>
                            <td class="text-center">ANTONIO JOSÉ JIMÉNEZ AGRAZ</td>
                        </tr>
                    </table>
                </div>
            </article>
            <div style="position: absolute; bottom: 0; width: 100%;">
                <table style="margin: auto;">
                    <tr>
                        <td style="border: none; text-align: center; margin: 0; padding: 0;">
                            @php
                                $page++;
                            @endphp
                            <p class="text-lg">{{ $page }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </section>
        </main>
    </body>
</html>