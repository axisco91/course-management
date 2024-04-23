<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solicitud Para CFA</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            .bg-black{
                background-color: rgb(55, 49, 52) !important; 
            }

            .border-black{
                border-color: rgb(55, 49, 52) !important; 
            } 

            .bg-gris{
                background-color: rgb(181, 179, 179); 
            }

            .text-naranja{
                color: rgb(233, 119, 33); 
            }

            .bg-naranja{
                background-color: rgb(233, 119, 33); 
            }

            .font-bold{
                font-weight: bold; 
            }

            .font-semibold{
                font-weight: 600; 
            }

            .border-right{
                border-right: 2px solid black; 
            }

            .border-left{
                border-left: 2px solid black; 
            }

            .text-lg{
                font-size: 1.125rem; 
            }

            .text-sm{
                font-size: 0.875rem; 
            }

            .text-xs{
                font-size: 0.75rem; 
            }
        </style>
    </head>
    <body>
        <!-- CABECERA -->
        <table class="mt-2" style="width: 90%">
            <tr>
                <td class="text-start" style="width: 50%">
                    <img style="width: 100%" src="./AVZ/logo-naranja.png" alt="">
                </td>
                <td class="text-end">
                    <h3 class="font-bold text-lg">SOLICITUD PARA CFA (421)</h3>
                    <p class="ps-3">Fecha: {{$fechaActual}}</p>
                </td>
            </tr>
        </table>
        
        <div class="bg-black" style="width: 100%; height: 5px"></div>

        <!-- INFORMACIÓN -->
        <section>
            <!-- ASESORÍA -->
            <article class="mt-2">
                <table style="width: 100%">
                    <tr>
                        <td class="bg-gris" style="width: 20%; height: 10px"></td>
                        <td class="font-semibold ps-2">ASESORÍA</td>
                    </tr>
                </table>

                <table class="border border-2 border-black mt-2" style="width: 100%;">
                    <tr class="p-2">
                        <td class="px-1">Nombre de la asesoría {{$trainingContract->advisor->name ?? ''}}  </td>
                        <td class="border-left border-2 border-black px-1"> CIF {{$trainingContract->company->advisor->nif}}  </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-right border-left px-1">Persona de contacto  {{$trainingContract->company->advisor->legal_representative}} </td>
                        <td class="border-right px-1"> Email  {{$trainingContract->company->advisor->email}}  </td>
                    </tr>
                </table>
                
                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Dirección  {{$trainingContract->company->advisor->address}}  </td>
                        <td class="border-right px-1"> CP   {{$trainingContract->company->advisor->post_code}} </td>
                    </tr>
                </table>
                
                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Localidad   {{$trainingContract->company->advisor->population}} </td>
                        <td class="border-right px-1"> Provincia   {{$trainingContract->province->name}} </td>
                        <td class="border-right px-1"> Teléfono  {{$trainingContract->company->advisor->telephone}} </td>
                        <td class="border-right px-1"> Fax   </td>
                    </tr>
                </table>
            </article>

            <!-- DATOS DE EMPRESA -->
            <article class="mt-2">
                <table style="width: 100%">
                    <tr>
                        <td class="bg-gris" style="width: 20%; height: 10px"></td>
                        <td class="font-semibold ps-2">DATOS DE EMPRESA</td>
                    </tr>
                </table>

                <table class="border border-2 border-black mt-2" style="width: 100%;">
                    <tr class="p-2">
                        <td class="px-1">Nombre comercial   </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Razón Social   {{$trainingContract->company->name}}</td>
                        <td class="border-right px-1">CIF/NIF   {{$trainingContract->company->nif}} </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Dirección   {{$trainingContract->company->address}}</td>
                        <td class="border-right px-1"> CP   {{$trainingContract->company->post_code}} </td>
                        <td class="border-right px-1"> Teléfono   {{$trainingContract->company->telephone}} </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Localidad   {{$trainingContract->company->population}}</td>
                        <td class="border-right px-1"> Provincia   {{$trainingContract->province->name ?? ''}} </td>
                        <td class="border-right px-1"> CNAE   {{$trainingContract->company->cnae->name ?? ''}} </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Cuenta cotización S.S. <span class="text-naranja text-xs">(1)</span>   </td>
                        <td class="border-right px-1">Nº Trabajadores <span class="text-xs text-naranja">(2)</span></td>
                        </td>
                        <td class="border-right px-1">De 1 a 4 <input class="ml-2 w-5 h-5 my-auto" type="checkbox" {{$trainingContract->company->companyType && $trainingContract->company->companyType->name == 'Autónomo' ? 'checked' : ''}}></td>
                        <td class="border-right px-1">más de 4 <input class="ml-2 w-5 h-5 my-auto" type="checkbox" {{$trainingContract->company->companyType && $trainingContract->company->companyType->name != 'Autónomo' ? 'checked' : ''}}></td>
                        <td class="border-right px-1">Jornada anual según convenio 
                            <span class="text-xs text-naranja">(3)</span> 
                            <span class="text-xs">(1800 si no lo especifica)</span>
                               {{$trainingContract->annually_day_hours ?? '1800'}}
                        </td>
                    </tr>
                </table>
                
                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">Representante legal   {{$trainingContract->company->legal_representative}}</td>
                        <td class="border-right px-1">NIF/NIE   {{$trainingContract->company->dni_legal_representative}} </td>
                    </tr>
                </table>
                    
                <table class="border-bottom border-2 border-black">
                    <tr class="p-2">
                        <td class="border-left border-right px-1">IBAN</td>
                        @php
                            $iban = str_replace(' ', '', $trainingContract->company->iban);
                            $ibanArray = str_split($iban);
                        @endphp

                        @foreach($ibanArray as $char)
                            <td class="px-1 border-right">{{ $char }}</td>
                        @endforeach
                    </tr>
                </table>
            </article>

            <!-- DATOS DEL ALUMNO -->
            <article class="mt-2" style="page-break-after: always;">
                <table style="width: 100%">
                    <tr>
                        <td class="bg-gris" style="width: 20%; height: 10px"></td>
                        <td class="font-semibold ps-2">DATOS DEL ALUMNO</td>
                    </tr>
                </table>

                <table class="border border-2 border-black mt-2" style="width: 100%">
                    <tr>
                        <td class="border-left border-right px-1">Nombre y apellidos   {{$trainingContract->student->name}} {{$trainingContract->student->surname}}</td>
                        <td class="border-right px-1">DNI/NIE   {{$trainingContract->student->dni}}</td>
                    </tr>
                </table>

                <table class="border border-2 border-black" style="width: 100%">
                    <tr>
                        <td class="border-left border-right px-1">Dirección   {{$trainingContract->student->direction ?? ''}}</td>
                        <td class="border-right px-1">Localidad   {{$trainingContract->student->population}}</td>
                        <td class="border-right px-1">Provincia   {{$trainingContract->student->province->name}}</td>
                    </tr>
                </table>

                <table class="border border-2 border-black" style="width: 100%">
                    <tr>
                        <td class="border-left border-right px-1">Fecha de nacimiento   {{$trainingContract->student->date_of_birth}}</td>
                        <td class="border-right px-1">Nacionalidad   {{$trainingContract->student->nationality ?? ''}}</td>
                        <td class="border-right px-1">Número S.S   {{$trainingContract->student->social_security_number ?? ''}}</td>
                        <td class="border-right px-1">Teléfono <span class="text-xs text-naranja">(4)</span>   {{$trainingContract->student->telephone ?? ''}}</td>
                    </tr>
                </table>

                <table class="border border-2 border-black" style="width: 100%">
                    <tr>
                        <td class="border-left border-right px-1">Email <span class="text-xs text-naranja">(4)</span>   {{$trainingContract->student->email}}</td>
                        <td class="border-right px-1">Estudios Terminados <span class="text-xs text-naranja">(5)</span>   {{$trainingContract->student->levelStudy->name}}</td>
                    </tr>
                </table>

                <table class="border border-2 border-black" style="width: 100%">
                    <tr>
                        <td class="border-left border-right px-1">¿Sistema de Garantía Juvenil? <span class="text-xs text-naranja">(6)</span></td>
                        <td class="border-right px-1">Si <input type="checkbox" {{$trainingContract->youth_guarantee == 1 ? 'checked' : ''}}></td>
                        <td class="border-right px-1">No <input type="checkbox" {{$trainingContract->youth_guarantee == 0 ? 'checked' : ''}}></td>
                        <td class="border-right px-1">¿Trabajador con Discapacidad?</td>
                        <td class="border-right px-1">Si <input type="checkbox" {{$trainingContract->disabled == 1 ? 'checked' : ''}}></td>
                        <td class="border-right px-1">No <input type="checkbox" {{$trainingContract->disabled == 0 ? 'checked' : ''}}></td>
                        <td class="border-right px-1">¿Exclusión Social?</td>
                        <td class="border-right px-1">Si <input type="checkbox" {{$trainingContract->social_exclusion == 1 ? 'checked' : ''}}></td>
                        <td class="border-right px-1">No <input type="checkbox" {{$trainingContract->social_exclusion == 0 ? 'checked' : ''}}></td>
                    </tr>
                </table>
            </article>

            <!-- DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA -->
            <article class="mt-2">
                <table style="width: 100%">
                    <tr>
                        <td class="bg-gris" style="width: 20%; height: 10px"></td>
                        <td class="font-semibold ps-2">DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</td>
                    </tr>
                </table>

                <table class="border border-2 border-black mt-2" style="width: 100%;">
                    <tr>
                        <td class="border-right px-1">Duración <span class="text-xs text-naranja">(7)</span>   {{$trainingContract->formation_hours}} </td>
                        <td class="border-right px-1">Fecha de inicio <span class="text-xs text-naranja">(8)</span>   {{$trainingContract->beginning_formation}} </td>
                        <td class="border-right px-1">¿Bonificado? <span class="text-xs text-naranja">(9)</span></td>
                        <td class="border-right px-1">SI  <input type="checkbox"></td>
                        <td class="border-right px-1">NO  <input type="checkbox"></td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Ocupación <span class="text-xs text-naranja">(10)</span>   {{$trainingContract->occupation->name}} </td>
                        <td class="border-right px-1">Nº Convenios Colectivos <span class="text-xs text-naranja">(11)</span>   {{$trainingContract->applicableAgreement->code ?? ''}}</td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Horario Formativo <span class="text-xs text-naranja">(12)</span>   {{$trainingContract->training_schedule}} </td>
                        <td class="border-right px-1">Horario Laboral <span class="text-xs text-naranja">(13)</span>   {{$trainingContract->working_hours}}</td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Dirección del Centro de Trabajo <span class="text-xs text-naranja">(14)</span>   {{$trainingContract->center_of_work}} </td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Vacaciones <span class="text-xs text-naranja">(15)</span></td>
                        <td class="border-right px-1">Periodo de prueba<span class="text-xs text-naranja">(16)</span></td>
                    </tr>
                </table>

                <table class="border-bottom border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Tutor de empresa <span class="text-xs text-naranja">(17)</span>   {{$trainingContract->company_tutor}} </td>
                        <td class="border-right px-1">DNI Tutor<span class="text-xs text-naranja">(18)</span>   {{$trainingContract->company_tutor_dni}}</td>
                    </tr>
                </table>

                {{-- ESTOS DATOS NO ESTÁN EN LA BASE DE DATOS --}}
                <table class="border-bottom border-2 border-black mb-1" style="width: 100%;">
                    <tr>
                        <td class="border-left border-right px-1">Teléfono Tutor<span class="text-xs text-naranja">(19)</span>   </td>
                        <td class="border-right px-1">Email Tutor<span class="text-xs text-naranja">(20)</span>   </td>
                    </tr>
                </table>

                <p class="text-sm">Cualificación profesor/tutor: <span class="text-xs text-naranja">(19)</span></p>
                <table class="border border-2 border-black" style="width: 100%;">
                    <tr>
                        <td class="border-right px-1">Experiencia</span>   </td>
                        <td class="px-1">Formación   </td>
                    </tr>
                </table>

                <div class="mt-2 mx-auto ">
                    <p class="text-xs">
                        Con la finalidad de cumplir lo que establece la Ley Orgánica de Protección de Datos (LOPD) 3/2018 y el RGPD 2016/679 de 27 de abril de 2016, le informamos que los datos personales que aparecen en
                        este documento están incluidos en un fichero bajo la responsabilidad de AVZ FORMACION. Los datos no serán cedidos ni comunicados a terceros, salvo en los supuestos legalmente establecidos.
                        Puede ejercer sus derechos de acceso, rectificación, cancelación, oposición y portabilidad directamente en info@avzformacion.com.
                    </p>
                </div>
            </article>

            <!-- Ayuda para completar el formulario -->
            <article class="mt-5 w-11/12 mx-auto">
                <h2 class="font-bold text-xl text-naranja">Ayuda para completar el formulario</h2>

                <div class="text-sm">
                    <p>
                        <span class="text-naranja font-bold">1.</span> <span class="font-bold">CCC Formación:</span> Indica el Código de Cuenta de Cotización específico para formación, que debe solicitarse previamente al
                        alta en Seguridad Social. Es el que deberá utilizar para dar el alta de todos los trabajadores con contrato de formación.
                    </p>
                    <p>
                        <span class="text-naranja font-bold">2.</span> <span class="font-bold">Nº de trabajadores plantilla:</span> Marca la opción de 1 a 4, 
                        si tu empresa tiene como máximo 4 trabajadores (bonificación por tutorización de 80€ mensual), y más de 4, cuando la plantilla sea superior (bonificación de 60€ mensual).
                    </p>
                    <p >
                        <span class="text-naranja font-bold">3.</span> <span class="font-bold">Jornada anual según convenio:</span> 
                            La jornada anual que puede tener como máximo el contrato de formación para cada
                            ocupación debe ser consultada siempre en el convenio colectivo. Si no se establece, será de 1.800 horas, según el Estatuto de
                            Trabajadores. El 25% ( 1ª año de contrato ) o 15% ( 2º y 3º) sobre esta cantidad, serán las de formación que recibirá el
                            trabajador en modalidad de teleformación, y la cantidad total máxima que podrá bonificar la empresa en concepto de
                            formación.
                    </p>
                    <p>
                        <span class="text-naranja font-bold">4.</span> <span class="font-bold">Teléfono y email del alumno:</span>
                            Imprescindibles para que podamos realizar la formación del trabajador. Necesitamos el
                            teléfono personal y email para poder contactar con él fuera de su horario de trabajo y enviarle información sobre el curso.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">5.</span> <span class="font-bold">Estudios terminados:</span> 
                            Especifica el nivel académico del alumno, que deberá acreditarse adjuntando una copia de su
                            titulación. Muy importante: el trabajador no podrá tener formación oficial relacionada con el puesto de trabajo a desempeñar.
                    </p>
                    <p>
                        <span class="text-naranja font-bold">6.</span> <span class="font-bold">Inscrito en garantía juvenil:</span> 
                        Indicar si el alumno está inscrito en garantía juvenil.
                    </p>
                    <p>
                        <span class="text-naranja font-bold">7.</span> <span class="font-bold">Duración:</span> 
                            1 año, pudiendo prorrogarse hasta 3 años, siempre que en convenio colectivo no se indique lo contrario. Si
                            permite una duración de 6 meses, se recomienda establecer un año igualmente, con el fin de evitar tener que repetir el proceso
                            de autorización a los 6 meses del contrato.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">8.</span> <span class="font-bold">Fecha de inicio:</span> 
                            Fecha en que se va a iniciar el contrato de formación. Deberá indicarse previendo al menos el margen de 1
                            mes, para que haya tiempo suficiente para solicitar la autorización de inicio de la actividad formativa.
                    </p>
                    <div >
                        <p>
                            <span class="text-naranja font-bold">9.</span> <span class="font-bold">Bonificado:</span>
                            Marca la casilla que corresponda. El contrato tendrá derecho a la reducción de las cuotas de los seguros
                            sociales del trabajador, siempre que se cumplan los siguientes requisitos:
                        </p>
                        <p class="ml-3">- Trabajador inscrito como demandante de empleo.</p>
                        <p class="ml-3">- No tener deuda con Seguridad Social o Hacienda.</p>
                        <p class="ml-3">- El trabajador no debe provenir de un contrato indefinido en otra empresa en los tres meses previos a la formalización del
                            contrato.
                        </p>
                        <p class="ml-3">
                            - El trabajador no debe haber trabajado para la misma empresa en los 24 meses previos con un contrato indefinido o en
                            los últimos seis meses con un contrato temporal o formativo.
                        </p>
                        <p class="ml-3">- El trabajador no será un familiar del empresario o miembro de la sociedad que le contrata.</p>
                        <p class="ml-3">- La empresa no pueden haber tenido despidos reconocidos improcedentes o colectivos en contratos bonificados.</p> 
                        <p>Consulta con nuestro equipo tu caso en particular.</p>
                    </div>
                    <p >
                        <span class="text-naranja font-bold">10.</span> <span class="font-bold">Ocupación:</span> 
                            Ocupación que va a desempeñar el trabajador en la empresa, que deberá estar directamente relacionada
                            con la formación que va a recibir durante su contrato.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">11.</span> <span class="font-bold">Convenio colectivo:</span> Indica el convenio colectivo de referencia para la empresa, ya que en éste se dan condiciones básicas
                        para su contrato.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">12.</span> <span class="font-bold">Horario formativo:</span> 
                            Margen temporal que va a dedicar el trabajador a la semana para formarse (10 horas). Es muy
                            importante el horario que aquí se indique, porque será el notificado en la solicitud de autorización. El SEPE comprobará que el
                            trabajador se esté formando en ese periodo. Durante esas horas, el trabajador no podrá estar trabajando bajo ningún
                            concepto.
                            El horario podrá estar comprendido entre las 08:00 y 22:00 horas, en el que el trabajador tendrá un tutor a su disposición en
                            nuestro centro de formación.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">13.</span> <span class="font-bold">Horario de trabajo:</span> 
                            Horas de trabajo efectivo que va a desempeñar. El trabajador no podrá realizar trabajo nocturno, entre
                            las 22:00 y 06:00 horas, rotativo, ni a turnos.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">14.</span> <span class="font-bold">Dirección del centro de trabajo:</span>
                            La dirección del centro de trabajo donde va a trabajar el alumno es fundamental, se
                            presentará la solicitud en la Delegación Territorial de Empleo de su misma provincia.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">15.</span> <span class="font-bold">Vacaciones:</span> 
                            El período vacacional estipulado en el contrato.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">16.</span> <span class="font-bold">Período de prueba:</span> 
                            El periodo de prueba es un tiempo durante el cual la empresa y el trabajador se prueban mutuamente.
                            La empresa decide si el trabajador se ajusta al trabajo y el trabajador ve si lo que le ofrece la empresa es lo que buscaba, o si
                            las condiciones son las prometidas.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">17.</span> <span class="font-bold">Tutor de empresa:</span> 
                            Nombre de la persona que va a realizar el seguimiento del alumno en el mismo centro de trabajo y
                            horario.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">18.</span> <span class="font-bold">Teléfono y email del tutor:</span> 
                            Nuestros tutores estarán en continua comunicación con él, por ello, necesitan su teléfono
                            directo y email para poder coordinar la labor formativa.
                    </p>
                    <p >
                        <span class="text-naranja font-bold">19.</span> <span class="font-bold">Cualificación del tutor:</span>
                            Marca la casilla que proceda e indica la formación y/o experiencia profesional que le capacita para
                            tutorizar al trabajador en su puesto de trabajo.
                    </p>
                </div>
            </article>

            <footer class="bg-naranja flex justify-end p-2 mt-3 pe-12">
                <p class="text-white font-semibold">info@avzformacion.com - 957 923 473 - 644 680 310</p>
            </footer>
        </section>
    </body>
</html>