<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Contrato de Formación</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/factura.css">
    </head>

    <body>
        <table style="width:100%;">
            <tr>
                <td style="width:30%; border: none;">
                    <img src="img-factura/login-pagevr.png" alt="Logo" style="max-width: 80%;">
                </td>
                <td style="width:70%; border: none; text-align: right">
                    <p class="letra-verde menos-margin-bottom"><b>MV & JAR CONSULTORES SL</b></p>
                    <p class="letra-verde menos-margin-bottom">B-72132988</p>
                    <p class="letra-verde menos-margin-bottom">C/ Real Fernando, Local 4</p>
                    <p class="letra-verde menos-margin-bottom">11540 Sanlúcar de Barrameda (Cádiz)</p>
                </td>
            </tr>
        </table>


        <h1 class="letra-verde mt-4" style="text-align: center">FACTURA</h1>

        <table style="width:100%" class="mt-4">
            <tr>
                <td style="width:50%; border: none; vertical-align: top;">
                    <p class="letra-gris menos-margin-bottom"><b>Nº Factura</b> <span class="letra-verde"> {{$trainingContractSeries->series}}{{str_pad($trainingContractBill->number, 3, '0', STR_PAD_LEFT)}}/{{$trainingContractBill->year}}</span></p>
                    <p class="letra-gris menos-margin-bottom"><b>Fecha</b><span class="letra-verde"> {{now()->format('d/m/Y')}} </span></p>
                </td>
                <td  style="width:50%;border: none; text-align: right" class="letra-verde">
                    <p class="menos-margin-bottom-2"><b>{{$company->name}}</b></p>
                    <p class="menos-margin-bottom-2"> {{$company->address}} </p>
                    <p class="menos-margin-bottom-2"> {{$company->post_code}} {{$company->population}} </p>
                    <p class="menos-margin-bottom-2"> {{$company->province->name}}</p>
                    <p class="menos-margin-bottom-2"><b>CIF {{$company->nif}}</b></p>
                </td>
            </tr>
        </table>


        <table style="width:100%" class="menos-margin-bottom-2 mt-4">
            <tr>
                <td class="menos-margin-bottom-2" style="width:70%; border: none;">
                    <p><b><span class="letra-verde">Concepto: </span></b>IMPARTICION DE FORMACION DE LOS SIGUIENTES ALUMNOS/TRABAJADORES</p>
                </td>
                <td class="menos-margin-bottom-2" style="width:30%; border: none; text-align: center">
                    <p><b>Importe</b></p>
                </td>
            </tr>
        </table>

        <hr class="menos-margin-bottom" style="border-top: 1px solid black;">

        <table style="width:100%">
            <tr>
                <td class="menos-margin-bottom-2" style="width:70%; border: none;">
                    <p><b><span class="letra-verde">Modalidad: </span></b> Teleformación </p>
                    <p><b><span class="letra-verde">Ocupación: </span></b> {{$occupation->name}} </p>
                    <p><b><span class="letra-verde">Trabajador/ a: </span></b> {{$student->name}} {{$student->surname}} <b><span class="letra-verde">DNI: </span></b> {{$student->dni}} </p>
                    <p><b><span class="letra-verde">Fecha inicio y fecha fin de la formación: </span></b> {{$trainingContractBonus->start}} - {{$trainingContractBonus->end}} </p>
                    <p><b><span class="letra-verde">Horas: </span></b> {{$trainingContractBill->hours}} </p>

                </td>
                <td class="menos-margin-bottom-2" style="width:30%; border: none; vertical-align: top; text-align: center">
                    <p><b>{{$trainingContractBill->amount}}</b></p>
                </td>
            </tr>
        </table>

        <table style="width:100%" class="mt-4">
            <tr>
                <td style="text-align: right">
                    <p>Forma de pago: DOMICILIADO</p>
                </td>
            </tr>
        </table>



        <table style="width:100%">
            <tr>
                <td style="width: 50%">
                    <img src="img-factura/v&firma.PNG" alt="firma" style="max-width: 40%;">
                </td>
                <td style="width: 50%; text-align:right">
                    <table style="width:100%">
                        <tr>
                            <td class="fondo-gris" style="width: 50%; border-right: 2px solid white;">
                                <p class="menos-margin-bottom ml-1" > Base imponible:</p>
                                <hr class="menos-margin-bottom" style="border-top: 2px solid white;">
                                <p class="menos-margin-bottom ml-1"> I.V.A. (21%)</p>
                                <hr class="menos-margin-bottom" style="border-top: 2px solid white;">
                                <p class="menos-margin-bottom ml-1"><b> Total Factura:</b></p>

                            </td>
                            <td class="fondo-gris" style="width: 50%">
                                <p class="menos-margin-bottom ml-1"> {{$trainingContractBill->amount}} €</p>
                                <hr class="menos-margin-bottom" style="border-top: 2px solid white;">
                                <p class="menos-margin-bottom ml-1"> 0,00 €</p>
                                <hr class="menos-margin-bottom" style="border-top: 2px solid white;">
                                <p class="menos-margin-bottom ml-1"><b> {{$trainingContractBill->amount}} €</b></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fondo-gris" style="width: 100%;border-top: 2px solid white;">
                                <p class="menos-margin-bottom ml-1"><span class="fondo-gris">Exento de I.V.A. según Ley 37/92- Art. 20-9º</span></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        

        <p class="mt-4"><b>Aplicar en los seguros sociales del mes de:</b> enero del 2024 (que se presentan en el mes siguiente)</p>
        <p> Los costes de la formación objeto de bonificación deberán quedar expresamente identificados en la 
            contabilidad de la empresa (Orden ESS/2518/2013 art. 9.3).</p>
        <p class="letra-verde-pequena"> <b>MV & JAR CONSULTORES, S.L. es Responsable del tratamiento de conformidad con el GDPR con la finalidad de mantener una 
            relación comercial y conservarlos mientras exista un- interés mutuo para ello. Los datos podrán ser comunicados a terceros. Puede 
            ejercer los derechos de acceso, rectificación, portabilidad, supresión, limitación y oposición en Calle Real Fernando, local, 4 - 11540 
            Sanlúcar de Barrameda (Cádiz). Email: <u>info@vrconsultores.es</u> y el de reclamación a <u>www.agpd.es</u></b></p>
        
    </body>

</html>
