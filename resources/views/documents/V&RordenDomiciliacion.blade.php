<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Domiciliación - V&R</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style type="text/css">
        h1{
            font-size: 1.3rem;
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

        .nif1{
            position: fixed;
            top: 19%;
            left: 40%;
            border-left: 2px solid black;
        }

        .name1{
            position: fixed;
            top: 25%;
            left: 40%;
            border-left: 2px solid black;
        }

        .address1{
            position: fixed;
            top: 31.6%;
            left: 23%;
            border-left: 2px solid black;
        }

        .cp1, .population1, .population1-text, .province1, .province1-text{
            position: fixed;
            top: 37.4%;
            border-left: 2px solid black;
        }

        .cp1{
            left: 23%;
        }

        .population1-text{
            left: 35%;
        }

        .population1{
            right: 36%;
        }

        .province1-text{
            right: 22%;
        }

        .province1{
            right: 6%;
        }

        .country1{
            position: fixed;
            top: 42.9%;
            left: 23%;
            border-left: 2px solid black;
        }

        .salto-pagina{
            page-break-after: always;
        }

        .text-sm{
            font-size: 0.8rem;
            font-weight: normal; 
            position: fixed; 
            top: 14.8%;
        }

        .name2{
            position: fixed;
            top: 10.2%;
            left: 50%;
            border-left: 2px solid black;
        }

        .address2{
            position: fixed;
            top: 17.5%;
            left: 32%;
            border-left: 2px solid black;
        }

        .cp2, .population2, .population2-text, .province2, .province2-text{
            position: fixed;
            top: 23.1%;
            border-left: 2px solid black;
        }

        .cp2{
            left: 23%;
        }

        .population2-text{
            left: 35%;
        }

        .population2{
            right: 36%;
        }

        .province2-text{
            right: 22%;
        }

        .province2{
            right: 6%;
        }

        .nif2, .nif2-text, .country2{
            position: fixed;
            top: 28.6%;
            border-left: 2px solid black;
        }

        .country2{
            left: 23%;
        }

        .nif2-text{
            left: 50%;
        }

        .nif2{
            right: 10%;
        }

        .swift{
            position: fixed;
            top: 34.4%;
            left: 55%;
            border-left: 2px solid black;
        }

        .recurrente, .unico, .recurrente2, .unico2, .pago{
            position: fixed;
            bottom: 42.7%;
            background-color: white; 
            border: 2px solid black;
        }

        .recurrente{
            left: 23%;
            font-style: italic; 
        }

        .recurrente2{
            left: 42.9%;
        }

        .unico{
            left: 49.4%;
            font-style: italic; 
        }

        .unico2{
            width: 11px; 
            height: 23.5px;
            right: 28.4%;
        }

        .pagos{
            margin-top: 100px
        }

        .fecha, .localidad-text, .localidad{
            position: fixed;
            bottom: 27.65%;
            border-left: 2px solid black;
        }

        .fecha{
            left: 23%;
        }

        .localidad-text{
            left: 40%;
        }

        .localidad{
            right: 5%;
        }

        .firma{
            position: fixed;
            bottom: 20.6%;
            left: 40%;
            border-left: 2px solid black;
        }
    </style>
</head>
<body class="p-5">
    <!-- LOGO -->
    <div class="mb-5">
        <img class="logo" width="150" src="./V&R/logo.png" alt="">
    </div>

    <section>
        <h1 class="text-green-100 p-2 bg-green-900 text-center font-semibold">ORDEN DE DOMICILIACIÓN DE ADEUDO DIRECTO SEPA</h1>

        <!-- INFORMACIÓN DEL ACREEDOR -->
        <article class="border border-1 border-dark mt-3">
            <p class="p-2 bg-green-100 border-bottom border-2 border-dark text-green-900 font-bold">A cumplimentar por el acreedor</p>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold p-2">Identificador del acreedor:</p>
                <p class="nif1 p-4">{{$company->nif}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold p-2">Nombre del acreedor:</p>
                <p class="name1 p-4">{{$company->name}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold p-2">Dirección:</p>
                <p class="address1 p-3">{{$company->address}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold p-2">Código Postal:</p>
                <p class="cp1 p-3">{{$company->post_code}}</p>
                <p class="font-semibold population1-text p-3">Población:</p>
                <p class="population1 p-3">{{$company->population}}</p>
                <p class="font-semibold province1-text p-3">Provincia:</p>
                <p class="province1 p-3">{{$company->province->name}}</p>
            </div>
            <div>
                <p class="p-2">País:</p>
                <p class="country1 p-3">ESPAÑA</p>
            </div>
        </article>

        <article class="mt-5 mb-5 salto-pagina">
            Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al Acreedor a enviar instrucciones a la entidad del deudor 
            para adeudar su cuenta y (B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Como 
            parte de sus derechos, el deudor está legitimado al reembolso por su entidad en los términos y condiciones del contrato suscrito 
            con la misma. La solicitud de reembolso deberá efectuarse dentro de las ocho semanas que siguen a la fecha de adeudo en cuenta. 
            Puede obtener información adicional sobre sus derechos en su entidad financiera
        </article>

        <!-- INFORMACIÓN DEL DEUDOR -->
        <article class="border border-1 border-dark mt-3">
            <p class="p-2 bg-green-100 border-bottom border-2 border-dark text-green-900 font-bold">A cumplimentar por el deudor</p>
            <div class="border-bottom border-2 border-dark">
                <div class="font-semibold p-2">
                    <p>Nombre del deudor/es: </p>
                    <p class="text-sm">(titulares de la cuenta a cargo)</p>
                </div>
                <p class="p-4 name2">{{$company->name}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="font-semibold p-2">Dirección del deudor:</p>
                <p class="address2 p-3">{{$trainingContract->center_of_work}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="p-2">Código Postal:</p>
                <p class="p-3 cp2">{{$company->post_code}}</p>
                <p class="font-semibold population2-text p-3">Población:</p>
                <p class="population2 p-3">{{$company->population}}</p>
                <p class=" font-semibold province2-text p-3">Provincia:</p>
                <p class="province2 p-3">{{$company->province->name}}</p>
            </div>
            <div class="border-bottom border-2 border-dark">
                <p class="border-r-2 border-black font-semibold p-2">País:</p>
                <p class="country2 p-3">ESPAÑA</p>
                <p class="nif2-text p-3"><strong>CIF/NIF</strong> del deudor:</p>
                <p class="nif2 p-3">{{$company->nif}}</p>
            </div>
            <div class="border-bottom border-2 border-dark ">
                <p class="p-2"><strong>Swift BIC</strong> (puede contener 8 u 11 posiciones):</p>
                <p class="swift p-3"> </p>
            </div>
            <p class="p-2 border-t-2 border-black"><strong>Número de cuenta – IBAN</strong>: {{$company->iban}}</p>
        </article>

        <!-- TIPO DE PAGO A REALIZAR -->
        <article class="pagos">
            <p class="border-right border-2 border-dark bg-green-100 text-green-900 p-3 pago font-bold">Tipo de pago: </p>
            <p class="p-3 text-green-900 font-bold recurrente">Pago recurrente</p>
            <p class="recurrente2 p-3">X</p>
            <p class="text-green-900 unico p-3 font-bold">Pago único</p>
            <p class="unico2 p-3"></p>
        </article>  

        <!-- FIRMA -->
        <article class=" border border-2 border-dark mt-5">
            <p class="bg-green-100 text-green-900 font-bold border-bottom border-2 border-dark p-2">Firma:</p>
            <div class="border-bottom border-2 border-dark">
                <div>
                    <p class="text-green-900 font-bold p-2">Fecha:</p>
                    <p class="p-4 fecha">{{$fechaActual}}</p>
                </div>
                <div>
                    <p class="text-green-900 font-bold localidad-text p-4">Localidad:</p>
                    <p class="localidad p-4">SANLUCAR DE BARRAMEDA</p>
                </div>
            </div>
            <div>
                <p class="text-green-900 font-bold p-3">Firma del deudor:</p>
                <p class="firma p-4">           </p>
            </div>
        </article>

        <div class="mt-5">
            <p class="text-green-900">Todos los campos han de ser cumplimentados obligatoriamente.</p>
            <p class="text-green-900">Una vez firmada esta orden de domiciliación debe ser enviada al acreedor para su custodia.</p>
        </div>
    </section>
</body>
</html>