<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solicitud Para CFA</title>
        <script src="https://cdn.tailwindcss.com"></script>
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
        </style>
    </head>
    <body>
        <!-- CABECERA -->
        <div class="grid grid-cols grid-flow-col m-5">
            <img class="w-80 h-40" src="/Avanza/logo-naranja.png" alt="">
            <div class="my-auto text-center">
                <h3 class="font-bold text-lg">SOLICITUD PARA CFA (421)</h3>
                <p class="ml-12 pl-12">Fecha: <input class="border-b-2 border-black" type="text"></p>
            </div>
        </div>
        <hr class="h-4 bg-black">

        <!-- INFORMACIÓN -->
        <section>
            <!-- ASESORÍA -->
            <article class="mt-5">
                <div class="flex">
                    <div class="h-6 w-44 bg-gris mr-2"></div>
                    <p class="font-semibold">ASESORÍA</p>
                </div>

                <div class=" w-11/12 mt-4 mx-auto border border-2 border-black">
                    <div class="flex">
                        <div class="flex w-3/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Nombre de la asesoría </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">CIF</p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-7/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Persona de contacto </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-5/12 p-2">
                            <p class="text-sm text-center">Email </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-5/6 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Dirección </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/6 p-2">
                            <p class="text-sm text-center">CP </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Localidad </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Provincia </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Teléfono </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">Fax </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                </div>
            </article>

            <!-- DATOS DE EMPRESA -->
            <article class="mt-5">
                <div class="flex">
                    <div class="h-6 w-44 bg-gris mr-2"></div>
                    <p class="font-semibold">DATOS DE EMPRESA</p>
                </div>

                <div class=" w-11/12 mt-4 mx-auto border border-2 border-black">
                    <div class="flex p-2">
                        <p class="text-sm text-center">Nombre comercial </p>
                        <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-3/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Razón Social </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">CIF/NIF </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-7/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Dirección </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/6 border-r-2 border-black p-2">
                            <p class="text-sm text-center">CP </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">Teléfono </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-5/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Localidad </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Provincia </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/3 border-r-2 p-2">
                            <p class="text-sm text-center">CNAE </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-5/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Cuenta cotización S.S. <span class="text-naranja text-xs">(1)</span></p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="w-1/6 border-r-2 border-black p-2">
                            <p class="text-sm text-center my-auto"> Nº Trabajadores <span class="text-xs text-naranja">(2)</span> </p>
                        </div>
                        <div class="flex w-1/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center my-auto"> De 1 a 4 </p>
                            <input class="ml-2 w-5 h-5 my-auto" type="checkbox">
                        </div>
                        <div class="flex w-1/12  border-r-2 border-black p-2">
                            <p class="text-sm text-center my-auto"> más de 4 </p>
                            <input class="ml-2 w-5 h-5 my-auto" type="checkbox">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">
                                Jornada anual según convenio 
                                <span class="text-xs text-naranja">(3)</span> 
                                <span class="text-xs">(1800 si no lo especifica)</span></p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-3/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Representante Legal </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">NIF/NIE </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <p></p>
                    </div>
                    <div class="flex border-t-2 border-black p-2">
                        <p class="text-sm text-center">IBAN </p>
                        <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                    </div>
                </div>
            </article>

            <!-- DATOS DEL ALUMNO -->
            <article class="mt-5">
                <div class="flex">
                    <div class="h-6 w-44 bg-gris mr-2"></div>
                    <p class="font-semibold">DATOS DEL ALUMNO</p>
                </div>

                <div class=" w-11/12 mt-4 mx-auto border border-2 border-black">
                    <div class="flex">
                        <div class="flex w-3/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Nombre y apellidos </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">DNI/NIE</p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/3 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Dirección </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/3 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Localidad </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/3 p-2">
                            <p class="text-sm text-center">Provincia </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Fecha de nacimiento </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Nacionalidad </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Número S.S. </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">Teléfono <span class="text-xs text-naranja">(4)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/2 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Email <span class="text-xs text-naranja">(4)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/2 p-2">
                            <p class="text-sm text-center">Estudios Terminados <span class="text-xs text-naranja">(5)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/3 border-r-2 border-black">
                            <div class="w-1/2 border-r-2 border-black p-2">
                                <p class="text-sm text-center">¿Sistema de Garantía Juvenil? <span class="text-xs text-naranja">(6)</span></p>
                            </div>
                            <div class="w-1/2 flex">
                                <div class="w-1/2 border-r-2 border-black p-2">
                                    <p class="text-center">Si</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                                <div class="w-1/2 p-2">
                                    <p class="text-center">No</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                            </div>
                        </div>
                        <div class="flex w-1/3 border-r-2 border-black">
                            <div class="w-1/2 border-r-2 border-black p-2">
                                <p class="text-sm text-center">¿Trabajador con Discapacidad?</p>
                            </div>
                            <div class="w-1/2 flex">
                                <div class="w-1/2 border-r-2 border-black p-2">
                                    <p class="text-center">Si</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                                <div class="w-1/2 p-2">
                                    <p class="text-center">No</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                            </div>
                        </div>
                        <div class="flex w-1/3 ">
                            <div class="w-1/2 flex items-center justify-center border-r-2 border-black p-2">
                                <p class="text-sm">¿Exclusión Social?</p>
                            </div>
                            <div class="w-1/2 flex">
                                <div class="w-1/2 border-r-2 border-black p-2">
                                    <p class="text-center">Si</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                                <div class="w-1/2 p-2">
                                    <p class="text-center">No</p>
                                    <input class="w-5 h-5 ml-12" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA -->
            <article class="mt-5">
                <div class="flex">
                    <div class="h-6 w-44 bg-gris mr-2"></div>
                    <p class="font-semibold">DATOS DEL CONTRATO PARA LA FORMACIÓN EN ALTERNANCIA</p>
                </div>

                <div class=" w-11/12 mt-4 mx-auto border border-2 border-black">
                    <div class="flex">
                        <div class="flex w-7/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Duración <span class="text-xs text-naranja">(7)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-2/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Fecha de inicio <span class="text-xs text-naranja">(8)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-2/12 justify-center items-center border-r-2 border-black p-2">
                            <p class="text-sm ">¿Bonificado? <span class="text-xs text-naranja">(9)</span> </p>
                        </div>
                        <div class="flex w-1/12 justify-center items-center  border-r-2 border-black p-2">
                            <p class="text-sm ">SI</p>
                            <input class="ml-2 w-5 h-5" type="checkbox">
                        </div>
                        <div class="flex justify-center items-center w-1/12 p-2">
                            <p class="text-sm ">NO</p>
                            <input class="ml-2 w-5 h-5" type="checkbox">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-7/12 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Ocupación <span class="text-xs text-naranja">(10)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-5/12 p-2">
                            <p class="text-sm text-center">Nº Convenios Colectivos <span class="text-xs text-naranja">(11)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/2 border-r-2 border-black p-2">
                            <p class="text-sm text-center">Horario Formativo <span class="text-xs text-naranja">(12)</span> <span class="text-xs">(10 horas)</span></p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/2 p-2">
                            <p class="text-sm text-center">Horario Laboral <span class="text-xs text-naranja">(12)</span> <span class="text-xs">(20 horas)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black p-2">
                        <p class="text-sm text-center">Dirección del Centro de Trabajo <span class="text-xs text-naranja">(13)</span></p>
                        <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/2 border-r-2 border-black p-2">
                            <p class="text-sm text-center"> Vacaciones <span class="text-xs text-naranja">(15)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/2 p-2">
                            <p class="text-sm text-center">Periodo de prueba <span class="text-xs text-naranja">(16)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-3/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center"> Tutor de empresa <span class="text-xs text-naranja">(17)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/4 p-2">
                            <p class="text-sm text-center">DNI Tutor</p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                    <div class="flex border-t-2 border-black">
                        <div class="flex w-1/4 border-r-2 border-black p-2">
                            <p class="text-sm text-center"> Teléfono tutor  <span class="text-xs text-naranja">(18)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-3/4 p-2">
                            <p class="text-sm text-center">Email tutor <span class="text-xs text-naranja">(19)</span> </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                </div>

                <div class="w-11/12 mt-4 mx-auto ">
                    <p class="text-sm">Cualificación profesor/tutor: <span class="text-xs text-naranja">(19)</span></p>
                    <div class="flex border border-2 border-black mt-3">
                        <div class="flex w-1/2 border-r-2 border-black p-2">
                            <p class="text-sm text-center"> Experiencia </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                        <div class="flex w-1/2 p-2">
                            <p class="text-sm text-center"> Formación </p>
                            <input class="ml-2 bg-gray-200 rounded-md w-full" type="text">
                        </div>
                    </div>
                </div>

                <div class="w-11/12 mt-4 mx-auto ">
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

                <div>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">1.</span> <span class="font-bold">CCC Formación:</span> Indica el Código de Cuenta de Cotización específico para formación, que debe solicitarse previamente al
                        alta en Seguridad Social. Es el que deberá utilizar para dar el alta de todos los trabajadores con contrato de formación.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">2.</span> <span class="font-bold">Nº de trabajadores plantilla:</span> Marca la opción de 1 a 4, 
                        si tu empresa tiene como máximo 4 trabajadores (bonificación por tutorización de 80€ mensual), y más de 4, cuando la plantilla sea superior (bonificación de 60€ mensual).
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">3.</span> <span class="font-bold">Jornada anual según convenio:</span> 
                            La jornada anual que puede tener como máximo el contrato de formación para cada
                            ocupación debe ser consultada siempre en el convenio colectivo. Si no se establece, será de 1.800 horas, según el Estatuto de
                            Trabajadores. El 25% ( 1ª año de contrato ) o 15% ( 2º y 3º) sobre esta cantidad, serán las de formación que recibirá el
                            trabajador en modalidad de teleformación, y la cantidad total máxima que podrá bonificar la empresa en concepto de
                            formación.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">4.</span> <span class="font-bold">Teléfono y email del alumno:</span>
                            Imprescindibles para que podamos realizar la formación del trabajador. Necesitamos el
                            teléfono personal y email para poder contactar con él fuera de su horario de trabajo y enviarle información sobre el curso.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">5.</span> <span class="font-bold">Estudios terminados:</span> 
                            Especifica el nivel académico del alumno, que deberá acreditarse adjuntando una copia de su
                            titulación. Muy importante: el trabajador no podrá tener formación oficial relacionada con el puesto de trabajo a desempeñar.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">6.</span> <span class="font-bold">Inscrito en garantía juvenil:</span> 
                        Indicar si el alumno está inscrito en garantía juvenil.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">7.</span> <span class="font-bold">Duración:</span> 
                            1 año, pudiendo prorrogarse hasta 3 años, siempre que en convenio colectivo no se indique lo contrario. Si
                            permite una duración de 6 meses, se recomienda establecer un año igualmente, con el fin de evitar tener que repetir el proceso
                            de autorización a los 6 meses del contrato.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">8.</span> <span class="font-bold">Fecha de inicio:</span> 
                            Fecha en que se va a iniciar el contrato de formación. Deberá indicarse previendo al menos el margen de 1
                            mes, para que haya tiempo suficiente para solicitar la autorización de inicio de la actividad formativa.
                    </p>
                    <div class="mt-4 ">
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
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">10.</span> <span class="font-bold">Ocupación:</span> 
                            Ocupación que va a desempeñar el trabajador en la empresa, que deberá estar directamente relacionada
                            con la formación que va a recibir durante su contrato.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">11.</span> <span class="font-bold">Convenio colectivo:</span> Indica el convenio colectivo de referencia para la empresa, ya que en éste se dan condiciones básicas
                        para su contrato.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">12.</span> <span class="font-bold">Horario formativo:</span> 
                            Margen temporal que va a dedicar el trabajador a la semana para formarse (10 horas). Es muy
                            importante el horario que aquí se indique, porque será el notificado en la solicitud de autorización. El SEPE comprobará que el
                            trabajador se esté formando en ese periodo. Durante esas horas, el trabajador no podrá estar trabajando bajo ningún
                            concepto.
                            El horario podrá estar comprendido entre las 08:00 y 22:00 horas, en el que el trabajador tendrá un tutor a su disposición en
                            nuestro centro de formación.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">13.</span> <span class="font-bold">Horario de trabajo:</span> 
                            Horas de trabajo efectivo que va a desempeñar. El trabajador no podrá realizar trabajo nocturno, entre
                            las 22:00 y 06:00 horas, rotativo, ni a turnos.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">14.</span> <span class="font-bold">Dirección del centro de trabajo:</span>
                            La dirección del centro de trabajo donde va a trabajar el alumno es fundamental, se
                            presentará la solicitud en la Delegación Territorial de Empleo de su misma provincia.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">15.</span> <span class="font-bold">Vacaciones:</span> 
                            El período vacacional estipulado en el contrato.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">16.</span> <span class="font-bold">Período de prueba:</span> 
                            El periodo de prueba es un tiempo durante el cual la empresa y el trabajador se prueban mutuamente.
                            La empresa decide si el trabajador se ajusta al trabajo y el trabajador ve si lo que le ofrece la empresa es lo que buscaba, o si
                            las condiciones son las prometidas.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">17.</span> <span class="font-bold">Tutor de empresa:</span> 
                            Nombre de la persona que va a realizar el seguimiento del alumno en el mismo centro de trabajo y
                            horario.
                    </p>
                    <p class="mt-4 ">
                        <span class="text-naranja font-bold">18.</span> <span class="font-bold">Teléfono y email del tutor:</span> 
                            Nuestros tutores estarán en continua comunicación con él, por ello, necesitan su teléfono
                            directo y email para poder coordinar la labor formativa.
                    </p>
                    <p class="mt-4 ">
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