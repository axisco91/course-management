<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Domiciliación Bancaria</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            .color {
                color: rgb(0, 144, 212);
            }

            .border-gray-400 {
                border-color: rgb(163, 163, 163) !important;
            }

            .campos {
                border-left: 2px solid rgb(163, 163, 163);
                padding-top: 2px; /* ajustado */
                padding-bottom: 2px; /* ajustado */
                padding-left: 4px; /* ajustado */
                margin-left: 4px; /* ajustado */
            }

            .font-semibold {
                font-weight: 600;
            }

            .italic {
                font-style: italic;
            }

            .text-xs {
                font-size: 0.55rem; /* ajustado */
            }

            .border-top-dotted {
                border-top: 2px dotted rgb(0, 0, 0);
                border-right: none;
                border-left: none;
                border-bottom: none;
            }

            .text-sm {
                font-size: 0.65rem; /* ajustado */
            }

            .p-1 {
                padding: 2px !important; /* ajustado */
            }

            .mt-4, .my-4 {
                margin-top: 0.5rem !important; /* ajustado */
            }

            .mt-10 {
                margin-top: 1rem !important; /* ajustado */
            }

            .mb-12 {
                margin-bottom: 1rem !important; /* ajustado */
            }

            h1, h3, p {
                margin: 0.25rem 0; /* ajustado */
            }
        </style>
    </head>
    <body>
        <!-- CABECERA -->
        <div class="text-end">
            <img src="./AVZ/logo.png" alt="" style="width: 20%"> <!-- ajustado -->
        </div>

        <!-- INFORMACIÓN -->
        <section class="mx-auto">
            <h1 class="color text-2xl font-semibold">AUTORIZACION DE PAGO</h1>

            <!-- DATOS DE LA EMPRESA -->
            <h3 class="color text-lg font-semibold mt-4">DATOS EMPRESA</h3>
            <article class="border border-2 border-gray-400">
                <div>
                    <div aria-colspan="2" class="p-1">
                        <p>
                            <span><strong>Razón Social:</strong> {{$trainingContract->company->name}} </span>
                            <span class="campos font-semibold"> C.I.F. O N.I.F.:<span style="font-weight: normal"> {{$trainingContract->company->nif}}</span> </span>
                        </p>
                    </div>
                </div>
                <div class="p-1 border-top border-2 border-gray-400">
                    <p><strong>Responsable:</strong> {{$trainingContract->company->legal_representative}} </p>
                </div>
                <div class="border-top border-2 border-gray-400">
                    <div aria-colspan="2" class="p-1">
                        <p style="width: 100%">
                            <span> <strong>Domicilio:</strong> {{$trainingContract->company->address}} </span>
                            <span class="campos"> <strong>Teléfono:</strong> {{$trainingContract->company->telephone}}</span>
                        </p>
                    </div>
                </div>
                <div aria-colspan="3" class="border-top border-2 border-gray-400">
                    <p class="p-1">
                        <span> <strong>Localidad:</strong> {{$trainingContract->company->population}} </span>
                        <span class="campos"> <strong>C.P:</strong>{{$trainingContract->company->post_code}} </span>
                        <span class="campos"> <strong>Provincia:</strong> {{$trainingContract->company->province->name}}</span>
                    </p>
                </div>
            </article>

            <!-- DATOS DEL TRABAJADOR -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS DEL TRABAJADOR</h3>
            <article class="border border-2 border-gray-400">
                <div class="flex">
                    <div aria-colspan="2" class="w-10/12 flex border-r-2 border-gray-400 p-1">
                        <p>
                            <span><strong>Nombre y Apellidos:</strong> {{$trainingContract->student->name}} {{$trainingContract->student->surname}} </span>
                            <span class="campos" > <strong>DNI:</strong> {{$trainingContract->student->dni}} </span>
                        </p>
                    </div>
                </div>
            </article>
            <p class="text-xs italic"> Por los costes establecidos en el art. 8 de la Orden ESS/2518/2013 de 26 de Diciembre (BOE de 11/01/14), por la formación teórica del trabajador contratado en formación</p>

            <!-- DATOS BANCARIOS -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS BANCARIOS</h3>
            <article class="border border-2 border-gray-400">
                <p class="text-center font-semibold">IBAN </p>
                <div class="border-top border-2 border-gray-400 p-1 text-center">
                    {{$trainingContract->company->iban ?? ''}}
                </div>
            </article>

            <!-- DATOS DE LA FORMACION -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS DE LA FORMACION</h3>
            <article class="border border-2 border-gray-400">
                <div class="flex p-1">
                    <p class="font-semibold">Ocupación: <span style="font-weight: normal">{{$trainingContract->occupation->name}}</span> </p>
                </div>
                <div class="border-top border-2 border-gray-400 p-1">
                    <p><span class="font-semibold">Centro: </span>  AVZ FORMACION S.L. (8000001711)</p>
                </div>
            </article>

            <!-- FECHA Y FIRMA -->
            <article style="margin-top: 6pc; margin-bottom: 6pc;">
            @php
    $beginningDate = DateTime::createFromFormat('Y-m-d', $trainingContract->beginning);
    $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $formattedDate = $formatter->format($beginningDate);
@endphp
<div class="text-center mt-4">
    <p>En Lucena, a {{$formattedDate}}</p>
</div>
            </article>

            <!-- CLAUSULAS -->
            <article class="mt-12">
                <div class="border-top-dotted" style="margin-bottom: 20px">
                    <p class="text-sm mt-4 font-semibold" style="font-size: 0.55rem; line-height: 1;">
                        como titular  de  la  cuenta  de  cargo ,  en  mi  condición  de  cliente  de  Avz  Formación  S.L.,  y haciendo  uso  de  la  facultad  conferida por els
                        artículo 23.1, en  relación  con  los  artículos  33 ,  34  y  37 ,  de  la  Ley  16 /2009 ,  de Servicios  de  pago ,  consiento  y autorizo a Avz
                        Formación S.L..  a  que  gire,  desde  la  fecha  de  la presente  y  en  adelante ,  en  el  número  de  cuenta  bancaria  indicada en el presente
                        documento ,  todos  los  recibos correspondientes  a  las  facturas  que  se  originen  como  consecuencia  de las relaciones comerciales
                        ligadas  a  la  formación teórica  suscrita  de  los  contratos  de  formación  comunicados  y  vigentes  entre ambas partes.
                    </p>
                </div>
                <div class="mt-10">
                    <p class="mb-3 text-xs" style="font-size: 0.55rem; line-height: 1;">CLAUSULA  INFORMATIVA  EN  MATERIA  DE  PROTECCIÓN  DE  DATOS  PERSONALES</p>
                    <p class="text-xs" style="font-size: 0.55rem; line-height: 1;">
                        De  conformidad con el  Reglamento  UE  2016/679  relativo  a  la  Protección  de  las  Personas  Físicas  en  lo  que  Respecta  al  Tratamiento  de  Datos  Personales  y  con la
                        L.O.  3/2018  de  Protección  de  Datos  Personales  y  Garantía  de  Derechos  Digitales ;  le informamos que los datos de contacto utilizados para la presente
                        comunicación están  incluidos  en  un  fichero  titularidad  de  AVZ  FORMACIÓN  SL;  con  la  finalidad  de  posibilitar  las  comunicaciones  a  través  de  correo  electrónico
                        que  ésta  mantiene  dentro  del  ejercicio  de  su  actividad  (como  clientes ,  proveedores  o  personal ).  La  causa  que  legitima  este  tratamiento  de  datos  es  el
                        consentimiento .  Los  datos  podrán  ser  transmitidos  a  la  entidad  que  presta  el  servicio de  asesoramiento  laboral ,  fiscal  y  contable y  en  su caso  a la entidad  de
                        almacenamiento  web.  Los datos proporcionados  se  conservarán  mientras  se  mantenga  la  relación  profesional  o  durante  los  años  necesarios  para  cumplir  con  las
                        obligaciones  legales.  Sin  perjuicio  de  ello  se  le  informa  de  que  usted  podrá  ejercitar  los  derechos  de  acceso,  rectificación,  supresión  (derecho  al  olvido),  limitación
                        en  el  tratamiento ,  portabilidad  y  oposición enviando  una  solicitud  por  escrito,  acompañada  de  una  fotocopia  de  su  DNI  a  la  siguiente  dirección :  CALLE BALLESTEROS
                        17,  LUCENA (CÓRDOBA)  CP 14900  o  telemáticamente  a través  del  siguiente  correo  electrónico:  info@avzformacion.com.
                    </p>
                </div>
            </article>
        </section>
    </body>
</html>
