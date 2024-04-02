<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Domiciliación Bancaria</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .color{
                color: rgb(0, 144, 212); 
            }
        </style>
    </head>
    <body>
        <!-- CABECERA -->
        <div class="flex justify-end">
            <img class="w-80 h-40" src="/Avanza/logo.png" alt="">
        </div>

        <!-- INFORMACIÓN -->
        <section class="w-11/12 mx-auto">
            <h1 class="color text-2xl font-semibold text-start">AUTORIZACION DE PAGO</h1>

            <!-- DATOS DE LA EMPRESA -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS EMPRESA</h3>
            <article class="border border-2 border-gray-400">
                <div class="flex">
                    <div class="w-8/12 border-r-2 border-gray-400 p-2"> 
                        <p class="font-semibold">Razón Social: </p>
                        <input class="bg-gray-200 rounded-sm w-full" type="text">
                    </div>
                    <div class="w-4/12 p-2"> 
                        <p class="font-semibold">C.I.F. O N.I.F.: </p>
                        <input class="bg-gray-200 rounded-sm w-full" type="text">
                    </div>
                </div>
                <div class="w-full flex p-2 border-t-2 border-gray-400"> 
                    <p class="font-semibold">Responsable: </p>
                    <input class="bg-gray-200 rounded-sm w-full ml-2" type="text">
                </div>
                <div class="flex border-t-2 border-gray-400">
                    <div class="w-8/12 flex border-r-2 border-gray-400 p-2"> 
                        <p class="font-semibold">Domicilio: </p>
                        <input class="bg-gray-200 rounded-sm w-full ml-2" type="text">
                    </div>
                    <div class="w-4/12 flex p-2"> 
                        <p class="font-semibold">Teléfono: </p>
                        <input class="bg-gray-200 rounded-sm w-full ml-2" type="text">
                    </div>
                </div>
                <div class="flex border-t-2 border-gray-400">
                    <div class="w-8/12 flex border-r-2 border-gray-400 p-2"> 
                        <p class="font-semibold my-auto">Localidad: </p>
                        <input class="bg-gray-200 rounded-sm w-full ml-2" type="text">
                    </div>
                    <div class="w-4/12 flex"> 
                        <div class="border-r-2 p-2 border-gray-400">
                            <p class="font-semibold">C.P: </p>
                            <input class="bg-gray-200 rounded-sm w-full" type="text">
                        </div>
                        <div class="p-2 w-11/12">
                            <p class="font-semibold">Provincia: </p>
                            <input class="bg-gray-200 rounded-sm w-full" type="text">
                        </div>
                    </div>
                </div>
            </article>

            <!-- DATOS DEL TRABAJADOR -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS DEL TRABAJADOR</h3>
            <article class="border border-2 border-gray-400">
                <div class="flex">
                    <div class="w-10/12 flex border-r-2 border-gray-400 p-2"> 
                        <p class="font-semibold my-auto">Nombre y Apellidos: </p>
                        <input class="bg-gray-200 rounded-sm ml-2 w-9/12" type="text">
                    </div>
                    <div class="w-3/12 p-2"> 
                        <p class="font-semibold">DNI: </p>
                        <input class="bg-gray-200 rounded-sm w-full" type="text">
                    </div>
                </div>
            </article>
            <p class="text-xs italic"> Por los costes establecidos en el art. 8 de la Orden ESS/2518/2013 de 26 de Diciembre (BOE de 11/01/14), por la formación teórica del trabajador contratado en formación</p>

            <!-- DATOS BANCARIOS -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS BANCARIOS</h3>
            <article class="border border-2 border-gray-400">
                <p class="text-center font-bold">IBAN</p>
                <div class="border-t-2 border-gray-400 p-2 text-center">
                    <input class="bg-gray-200 w-full" type="text">
                </div>
            </article>

            <!-- DATOS DE LA FORMACION -->
            <h3 class="color text-lg font-semibold mt-4 text-start">DATOS DE LA FORMACION</h3>
            <article class="border border-2 border-gray-400">
                <div class="flex p-2">
                    <p class="font-semibold">Ocupación: </p>
                    <input class="bg-gray-200 ml-2 rounded-md w-11/12" type="text">
                </div>
                <div class="border-t-2 border-gray-400 p-2 ">
                    <p><span class="font-semibold">Centro: </span>  AVZ FORMACION S.L. (8000001711)</p>
                </div>
            </article>

            <!-- FECHA Y FIRMA -->
            <article>
                <div class="text-center mt-4">
                    <p>En Lucena, a 
                        <input class="border-b-2 border-black w-8" type="text"> 
                        de <input class="border-b-2 border-black w-1/12" type="text"> 
                        de 20 <input class="border-b-2 border-black w-8" type="text">
                    </p>
                </div>
                <div class="text-end mt-10 w-11/12 mb-12">
                    <p>(Firma y sello de la empresa)</p>
                </div>
            </article>

            <!-- CLAUSULAS -->
            <article class="mt-12">
                <div class="border-dashed border-t-2 border-black mb-10">
                    <p class="text-sm mt-4 font-semibold">
                        como titular  de  la  cuenta  de  cargo ,  en  mi  condición  de  cliente  de  Avz  Formación  S.L.,  y haciendo  uso  de  la  facultad  conferida por els
                        artículo 23.1, en  relación  con  los  artículos  33 ,  34  y  37 ,  de  la  Ley  16 /2009 ,  de Servicios  de  pago ,  consiento  y autorizo a Avz 
                        Formación S.L..  a  que  gire,  desde  la  fecha  de  la presente  y  en  adelante ,  en  el  número  de  cuenta  bancaria  indicada en el presente
                        documento ,  todos  los  recibos correspondientes  a  las  facturas  que  se  originen  como  consecuencia  de las relaciones comerciales
                        ligadas  a  la  formación teórica  suscrita  de  los  contratos  de  formación  comunicados  y  vigentes  entre ambas partes.
                    </p>
                </div>
                <div class="mt-10">
                    <p class="mb-3 text-xs">CLAUSULA  INFORMATIVA  EN  MATERIA  DE  PROTECCIÓN  DE  DATOS  PERSONALES</p>
                    <p class="text-xs">
                        De  conformidad con el  Reglamento  UE  2016/679  relativo  a  la  Protección  de  las  Personas  Físicas  en  lo  que  Respecta  al  Tratamiento  de  Datos  Personales  y  con la
                        L.O.  3/2018  de  Protección  de  Datos  Personales  y  Garantía  de  Derechos  Digitales ;  le  informamos  que  los  datos  de  contacto  utilizados  para  la  presente 
                        comunicación  están  incluidos  en  un  fichero  titularidad  de  AVZ  FORMACIÓN  SL;  con  la  finalidad  de  posibilitar  las  comunicaciones  a  través  de  correo  electrónico
                        que  ésta  mantiene  dentro  del  ejercicio  de  su  actividad  (como  clientes ,  proveedores  o  personal ).  La  causa  que  legitima  este  tratamiento  de  datos  es  el
                        consentimiento .  Los  datos  podrán  ser  transmitidos  a  la  entidad  que  presta  el  servicio  de  asesoramiento  laboral ,  fiscal  y  contable  y  en  su  caso  a la entidad  de 
                        almacenamiento  web.  Los datos proporcionados  se  conservarán  mientras  se  mantenga  la  relación  profesional  o  durante  los  años  necesarios  para  cumplir  con  las
                        obligaciones  legales.  Sin  perjuicio  de  ello  se  le  informa  de  que  usted  podrá  ejercitar  los  derechos  de  acceso,  rectificación,  supresión  (derecho  al  olvido),  limitación
                        en  el  tratamiento ,  portabilidad  y  oposición  enviando  una  solicitud  por  escrito,  acompañada  de  una  fotocopia  de  su  DNI  a  la  siguiente  dirección :  CALLE EL PESO
                        35,  3º  D,  LUCENA  (CÓRDOBA)  CP  14900  o  telemáticamente  a  través  del  siguiente  correo  electrónico:  info@avzformacion.com.
                    </p>
                </div>
            </article>
        </section>
    </body>
</html>