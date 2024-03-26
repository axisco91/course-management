<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Domiciliación - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
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
    </style>
</head>
<body class="p-5">
    <!-- LOGO -->
    <div class="d-flex justify-end mb-5">
        <img width="150" src="./V&R/logo.png" alt="">
    </div>

    <section>
        <h1 class="text-2xl text-green-100 p-4 bg-green-900 text-center font-semibold">ORDEN DE DOMICILIACIÓN DE ADEUDO DIRECTO SEPA</h1>

        <!-- INFORMACIÓN DEL ACREEDOR -->
        <article class="border border-1 border-black mt-5">
            <p class="p-2 bg-green-100 text-green-900 font-bold">A cumplimentar por el acreedor</p>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold p-2">Identificador del acreedor:</p>
                <p class="col-span-10 p-2">{{$company->nif}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold p-2">Nombre del acreedor:</p>
                <p class="col-span-10 p-2">{{$company->name}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2">Dirección:</p>
                <p class="col-span-10 p-2">{{$company->address}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2">Código Postal:</p>
                <p class=" p-2">{{$company->post_code}}</p>
                <p class="border-r-2 border-l-2 border-black font-semibold col-span p-2">Población:</p>
                <p class="col-span-10 p-2">{{$company->population}}</p>
                <p class="border-r-2 border-l-2 border-black font-semibold col-span p-2">Provincia:</p>
                <p class="col-span-10 p-2">{{$company->province->name}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2">País:</p>
                <p class="col-span-10 p-2">ESPAÑA</p>
            </div>
        </article>

        <article class="mt-5 mb-5">
            Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al Acreedor a enviar instrucciones a la entidad del deudor 
            para adeudar su cuenta y (B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Como 
            parte de sus derechos, el deudor está legitimado al reembolso por su entidad en los términos y condiciones del contrato suscrito 
            con la misma. La solicitud de reembolso deberá efectuarse dentro de las ocho semanas que siguen a la fecha de adeudo en cuenta. 
            Puede obtener información adicional sobre sus derechos en su entidad financiera
        </article>

        <!-- INFORMACIÓN DEL DEUDOR -->
        <article class="border border-1 border-black mt-5">
            <p class="p-2 bg-green-100 text-green-900 font-bold">A cumplimentar por el deudor</p>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <div class="border-r-2 border-black font-semibold p-2">
                    <p>Nombre del deudor/es:</p>
                    <p class="text-sm font-normal">{{$company->name}}</p>
                </div>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2">Dirección del deudor:</p>
                <p class="col-span-10 p-2">{{$trainingContract->center_of_work}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2">Código Postal:</p>
                <p class="p-2">{{$company->post_code}}</p>
                <p class="border-r-2 border-l-2 border-black font-semibold col-span p-2">Población:</p>
                <p class="col-10 p-2">{{$company->population}}</p>
                <p class="border-r-2 border-l-2 border-black font-semibold col-span p-2">Provincia:</p>
                <p class="col-span-10 p-2">{{$company->province->name}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold p-2">País:</p>
                <p class=" p-2">ESPAÑA</p>
                <p class="border-r-2 border-l-2 border-black font-semibold p-2"><strong>CIF/NIF</strong> del deudor:</p>
                <p class=" p-2">{{$company->nif}}</p>
            </div>
            <div class="border-t-2 border-black grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black font-semibold col-span p-2"><strong>Swift BIC</strong> (puede contener 8 u 11 posiciones):</p>
                <p class="col-span-10 p-2"></p>
            </div>
            <p class="p-2 border-t-2 border-black font-semibold"><strong>Número de cuenta – IBAN</strong>: {{$company->iban}}</p>
        </article>

        <!-- TIPO DE PAGO A REALIZAR -->
        <article class="w-1/2 border border-1 border-black mt-5">
            <div class="grid grid-cols grid-flow-col">
                <p class="border-r-2 border-black bg-green-100 text-green-900 p-2 font-bold">Tipo de pago: </p>
                <p class="my-auto border-black border-r-2 p-2 italic text-green-900 font-bold">Pago recurrente</p>
                <p class="my-auto p-2">X</p>
                <p class="my-auto border-black border-r-2 border-l-2 p-2 italic text-green-900 font-bold">Pago único</p>
                <p class="my-auto p-2"></p>
            </div>
        </article>  

        <!-- FIRMA -->
        <article class="container border border-1 border-black mt-5">
            <p class="bg-green-100 text-green-900 font-bold p-2">Firma:</p>
            <div class="row border-t-2 border-black">
                <div class="col-span d-flex">
                    <p class="text-green-900 font-bold border-r-2 border-black p-2">Fecha:</p>
                    <p class="p-2 border-black border-r-2">{{$fechaActual}}</p>
                </div>
                <div class="col-10 d-flex">
                    <p class="text-green-900 font-bold border-r-2 border-black p-2">Localidad:</p>
                    <p class="p-2">SANLUCAR DE BARRAMEDA</p>
                </div>
            </div>
            <div class="grid grid-cols grid-flow-col border-t-2 border-black">
                <p class="text-green-900 font-bold border-r-2 border-black pl-2 py-7">Firma del deudor:</p>
                <p class="col-span-5 pl-2 py-7">(Firma del deudor)</p>
            </div>
        </article>

        <div class="mt-5">
            <p class="text-green-900">Todos los campos han de ser cumplimentados obligatoriamente.</p>
            <p class="text-green-900">Una vez firmada eta orden de domiciliación debe ser enviada al acreedor para su custodia.</p>
        </div>
    </section>
</body>
</html>