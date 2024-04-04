<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Domiciliación - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
        h1{
            font-size: 1rem;
        }

        .logo{
            position: fixed;
            top: 0%; 
            right: 5%;
        }

        .font-semibold{
            font-weight: 600;
        }

        .font-bold{
            font-weight: bold;
        }

        .bg-green-100{
            background-color: rgb(184, 206, 198) !important; 
        }

        .text-green-100{
            color: rgb(184, 206, 198) !important; 
        }

        .bg-green-900{
            background-color: rgb(24, 57, 46) !important; 
        }

        .text-green-900{
            color: rgb(24, 57, 46) !important; 
        }

        .salto-pagina{
            page-break-after: always;
        }

        .text-sm{
            font-size: 0.8rem;
            font-weight: normal; 
        }

        .text-xs{
            font-size: 0.7rem;
        }

        .firma{
            position: fixed;
            bottom: 20.6%;
            left: 40%;
            border-left: 2px solid black;
        }

        .font-normal{
            font-weight: normal;
        }

        .border-right{
            border-right: 2px solid black;
        }

        .name{
            position: fixed;
            top: 45%; 
            left: 35%; 
            border-left: 2px solid black;
        }
        
    </style>
</head>
<body class="p-5">
    <!-- LOGO -->
    <div class="mb-2">
        <img class="logo" width="110" src="./V&R/logo.png" alt="">
    </div>

    <section>
        <h1 class="text-green-100 p-2 bg-green-900 text-center font-semibold">ORDEN DE DOMICILIACIÓN DE ADEUDO DIRECTO SEPA</h1>

        <!-- INFORMACIÓN DEL ACREEDOR -->
        <article class="border border-1 border-dark mt-2 text-sm">
            <p class="p-1 bg-green-100 border-bottom border-2 border-dark text-green-900 font-bold">A cumplimentar por el acreedor</p>
            <div class="border-bottom border-2 border-dark">
                <p>
                    <span class="font-semibold px-4">Identificador del acreedor:</span>
                    <span class="border-start border-2 border-dark ps-1" style="padding-top:3.8%; padding-bottom:3%;">{{$company->nif}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold">
                    <span class="px-4">Nombre del acreedor:</span>
                    <span class="border-start border-2 border-dark font-normal ps-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->name}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold">
                    <span class="px-4">Dirección:</span>
                    <span class="border-start border-2 border-dark font-normal ps-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->address}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p>
                    <span class="font-semibold px-4">Código Postal:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->post_code}}</span>
                    <span class="font-semibold border-start border-2 border-dark px-1" style="padding-top: 1%; padding-bottom:3%;">Población:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->population}}</span>
                    <span class="font-semibold border-start border-2 border-dark px-1" style="padding-top: 1%; padding-bottom:3%;">Provincia:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->province->name}}</span>
                </p>
            </div>
            <div>  
                <p>
                    <span class="font-semibold px-4">País:</span>
                    <span class="border-start border-2 border-dark font-normal ps-2" style="padding-top: 1%; padding-bottom:3%;">ESPAÑA</span>
                </p>
            </div>
        </article>

        <article class="mt-2 mb-2 text-xs">
            Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al Acreedor a enviar instrucciones a la entidad del deudor 
            para adeudar su cuenta y (B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Como 
            parte de sus derechos, el deudor está legitimado al reembolso por su entidad en los términos y condiciones del contrato suscrito 
            con la misma. La solicitud de reembolso deberá efectuarse dentro de las ocho semanas que siguen a la fecha de adeudo en cuenta. 
            Puede obtener información adicional sobre sus derechos en su entidad financiera
        </article>

        <!-- INFORMACIÓN DEL DEUDOR -->
        <article class="border border-1 border-dark mt-3 text-sm">
            <p class="p-1 bg-green-100 border-bottom border-2 border-dark text-green-900 font-bold">A cumplimentar por el deudor</p>
            <div class="border-bottom border-2 border-dark">
                <div class="ps-4">
                    <p class="font-bold mb-0">Nombre del deudor/es: </p>
                    <p class="text-xs">(titulares de la cuenta a cargo)</p>
                </div>
                <p class="name px-1" style="padding-top: 4%; padding-bottom: 3.2%; ">{{$company->name}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p>
                    <span class="font-semibold px-4">Dirección del deudor:</span>
                    <span class="border-start border-2 border-dark ps-2" style="padding-top:1%; padding-bottom: 3%;">{{$trainingContract->center_of_work}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p>
                    <span class="font-semibold px-4">Código Postal:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->post_code}}</span>
                    <span class="font-semibold border-start border-2 border-dark px-1" style="padding-top: 1%; padding-bottom:3%;">Población:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->population}}</span>
                    <span class="font-semibold border-start border-2 border-dark px-1" style="padding-top: 1%; padding-bottom:3%;">Provincia:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom:3%;">{{$company->province->name}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p>
                    <span class="font-semibold px-4" >País del deudor:</span>
                    <span class="border-start border-2 border-dark font-normal ps-2 pe-4" style="padding-top: 1%; padding-bottom: 3.3%;">ESPAÑA</span>
                    <span class="font-semibold border-start border-2 border-dark px-1" style="padding-top: 1%; padding-bottom: 3.3%;">CIF/NIF del deudor:</span>
                    <span class="border-start border-2 border-dark font-normal px-1" style="padding-top: 1%; padding-bottom: 3.3%;">{{$company->nif}}</span>
                </p>
            </div>
            <div class="border-bottom border-2 border-dark ">
                <p class="ps-4">
                    <span><strong>Swift BIC</strong> (puede contener 8 u 11 posiciones):</span>
                    <span class="border-start border-2 border-dark ps-2" style="padding-top:1%; padding-bottom:3%;">(swift BIC)</span>
                </p>
            </div>
            <p class="ps-4"><strong>Número de cuenta – IBAN</strong>: {{$company->iban}}</p>
        </article>

        <!-- TIPO DE PAGO A REALIZAR -->
        <article class="mt-2">
            <div>
                <p class="border border-2 border-dark text-sm" style="padding-bottom: 8px; padding-top: 8px; width: 49%;">
                    <span class="bg-green-100 text-green-900 font-bold my-auto border-top border-2 border-dark border-right" style="padding-top: 12.5px; padding-bottom: 9.6px;">Tipo de pago: </span>
                    <span class="text-green-900 font-bold border-right px-1" style="padding-top: 14.2px; padding-bottom: 9.6px;">Pago recurrente</span>
                    <span class="text-green-900 font-bold border-right px-1" style="padding-top: 14.2px; padding-bottom: 9.6px;">X</span>
                    <span class="text-green-900 font-bold border-right px-1" style="padding-top: 14.2px; padding-bottom: 9.6px;">Pago Único</span>
                    <span class="text-green-900 px-1"> </span>
                </p>
            </div>
        </article>  

        <!-- FIRMA -->
        <article class=" border border-2 border-dark text-sm">
            <p class="bg-green-100 text-green-900 font-bold border-bottom border-2 border-dark p-1">Firma:</p>
            <div>        
                <div class="border-bottom border-2 border-dark">
                    <p>
                        <span class="text-green-900 font-bold border-right ps-2 pe-2" style="padding-top: 3.7%; padding-bottom: 3%;">Fecha:</span>
                        <span class="text-green-900 border-right ps-2 pe-2" style="padding-top: 3.7%; padding-bottom: 3%;">{{$fechaActual}}</span>
                        <span class="text-green-900 font-bold border-right ps-2 pe-2" style="padding-top: 3.7%; padding-bottom: 3%;">Localidad:</span>
                        <span class="text-green-900 ps-2 pe-2">SANLUCAR DE BARRAMEDA</span>
                    </p>
                </div>
                <div>
                    <p>
                        <span class="pe-5 ps-2 font-bold border-right" style="padding-top: 7px; padding-bottom: 19px">Firma del deudor: </span>
                        <span></span>
                    </p>
                </div>
            </div>
        </article>

        <div class="text-xs">
            <p class="text-green-900">Todos los campos han de ser cumplimentados obligatoriamente.</p>
            <p class="text-green-900">Una vez firmada esta orden de domiciliación debe ser enviada al acreedor para su custodia.</p>
        </div>
    </section>
</body>
</html>